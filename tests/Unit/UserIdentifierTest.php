<?php

namespace Tests\Unit;

use App\Models\Chamado;
use App\Models\Fila;
use App\Models\Setor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserIdentifierTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Os testes não devem depender do Replicado real.
        config(['chamados.usar_replicado' => false]);
    }

    public function test_it_resolves_a_replication_person_by_prefixed_codpes(): void
    {
        $user = User::factory()->create(['codpes' => 17971882]);

        $resolved = User::obterOuCriarPorIdentificador('codpes-17971882');

        $this->assertTrue($resolved->is($user));
    }

    public function test_it_resolves_a_local_user_by_prefixed_id(): void
    {
        $user = User::factory()->create(['codpes' => null, 'local' => true]);

        $resolved = User::obterOuCriarPorIdentificador('id-' . $user->id);

        $this->assertTrue($resolved->is($user));
    }

    public function test_it_creates_a_user_with_a_prefixed_codpes_identifier(): void
    {
        $user = User::obterOuCriarPorIdentificador('codpes-17971882');

        $this->assertSame(17971882, (int) $user->codpes);
        $this->assertDatabaseHas('users', ['codpes' => 17971882]);
    }

    public function test_it_passes_an_integer_codpes_to_the_replication_client(): void
    {
        $pessoa = \Mockery::mock('alias:Uspdev\\Replicado\\Pessoa');
        $pessoa->shouldReceive('email')->once()->with(17971882)->andReturn('pessoa@example.com');
        $pessoa->shouldReceive('dump')->once()->with(17971882)->andReturn([
            'nompesttd' => 'Pessoa do Replicado',
        ]);
        $pessoa->shouldReceive('obterRamalUsp')->once()->with(17971882)->andReturn(false);
        $pessoa->shouldReceive('email')->once()->with(17971883)->andReturn('outra-pessoa@example.com');
        $pessoa->shouldReceive('dump')->once()->with(17971883)->andReturn([
            'nompesttd' => 'Outra pessoa do Replicado',
        ]);
        $pessoa->shouldReceive('obterRamalUsp')->once()->with(17971883)->andReturn(false);

        config(['chamados.usar_replicado' => true]);

        $user = User::obterOuCriarPorIdentificador('codpes-17971882');
        $legacyUser = User::obterOuCriarPorCodpes('17971883');

        $this->assertSame(17971882, (int) $user->codpes);
        $this->assertSame(17971883, (int) $legacyUser->codpes);
    }

    public function test_it_adds_a_replication_person_as_a_sector_manager(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $target = User::factory()->create(['codpes' => 17971882]);
        $setor = Setor::factory()->create();

        $response = $this->withSession(['perfil' => 'admin'])
            ->actingAs($admin)
            ->post('/setores/' . $setor->id . '/pessoas', [
                'codpes' => 'codpes-17971882',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('user_setor', [
            'user_id' => $target->id,
            'setor_id' => $setor->id,
            'funcao' => 'Gerente',
        ]);
        $this->assertDatabaseCount('users', 2);
    }

    public function test_it_adds_a_local_user_as_a_sector_manager(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $localUser = User::factory()->create(['codpes' => null, 'local' => true]);
        $setor = Setor::factory()->create();

        $response = $this->withSession(['perfil' => 'admin'])
            ->actingAs($admin)
            ->post('/setores/' . $setor->id . '/pessoas', [
                'codpes' => 'id-' . $localUser->id,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('user_setor', [
            'user_id' => $localUser->id,
            'setor_id' => $setor->id,
            'funcao' => 'Gerente',
        ]);
        $this->assertDatabaseCount('users', 2);
    }

    public function test_it_rejects_an_invalid_sector_user_identifier(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $setor = Setor::factory()->create();

        $response = $this->withSession(['perfil' => 'admin'])
            ->actingAs($admin)
            ->post('/setores/' . $setor->id . '/pessoas', [
                'codpes' => 'id-999999',
            ]);

        $response->assertSessionHasErrors('codpes');
    }

    public function test_it_adds_a_local_user_to_a_queue(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $localUser = User::factory()->create(['codpes' => null, 'local' => true]);
        $setor = Setor::factory()->create();
        $fila = Fila::factory()->create(['setor_id' => $setor->id]);

        $response = $this->withSession(['perfil' => 'admin'])
            ->actingAs($admin)
            ->post('/filas/' . $fila->id . '/pessoas', [
                'codpes_id' => 'id-' . $localUser->id,
                'funcao' => 'Gerente',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('user_fila', [
            'user_id' => $localUser->id,
            'fila_id' => $fila->id,
            'funcao' => 'Gerente',
        ]);
    }

    public function test_it_keeps_supporting_the_legacy_queue_codpes_field(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $target = User::factory()->create(['codpes' => 17971882]);
        $setor = Setor::factory()->create();
        $fila = Fila::factory()->create(['setor_id' => $setor->id]);

        $response = $this->withSession(['perfil' => 'admin'])
            ->actingAs($admin)
            ->post('/filas/' . $fila->id . '/pessoas', [
                'codpes' => (string) $target->codpes,
                'funcao' => 'Atendente',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('user_fila', [
            'user_id' => $target->id,
            'fila_id' => $fila->id,
            'funcao' => 'Atendente',
        ]);
    }

    public function test_it_rejects_an_unknown_local_user_in_a_queue(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $setor = Setor::factory()->create();
        $fila = Fila::factory()->create(['setor_id' => $setor->id]);

        $response = $this->withSession(['perfil' => 'admin'])
            ->actingAs($admin)
            ->post('/filas/' . $fila->id . '/pessoas', [
                'codpes_id' => 'id-999999',
                'funcao' => 'Gerente',
            ]);

        $response->assertSessionHasErrors('codpes_id');
    }

    public function test_it_adds_a_local_user_to_a_ticket(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $localUser = User::factory()->create(['codpes' => null, 'local' => true]);
        $setor = Setor::factory()->create();
        $fila = Fila::factory()->create(['setor_id' => $setor->id]);
        $chamado = Chamado::withoutEvents(function () use ($fila) {
            return Chamado::factory()->create([
                'fila_id' => $fila->id,
                'status' => 'Triagem',
            ]);
        });
        Mail::fake();

        $response = $this->withSession(['perfil' => 'admin'])
            ->actingAs($admin)
            ->post('/chamados/' . $chamado->id . '/pessoas', [
                'codpes' => 'id-' . $localUser->id,
                'papel' => 'Observador',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('user_chamado', [
            'user_id' => $localUser->id,
            'chamado_id' => $chamado->id,
            'papel' => 'Observador',
        ]);
    }

    public function test_it_adds_a_replication_person_from_the_users_screen(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $target = User::factory()->create(['codpes' => 17971882]);

        $response = $this->actingAs($admin)->post('/users', [
            'codpes' => 'codpes-17971882',
        ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', ['id' => $target->id]);
        $this->assertDatabaseCount('users', 2);
    }
}
