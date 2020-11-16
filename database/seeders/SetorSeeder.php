<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setor;
use App\Models\User;

class SetorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cod_unidade = getenv('REPLICADO_CODUNDCLG');
        if (config('chamados.usar_replicado')) {
            $setores = \Uspdev\Replicado\Estrutura::listarSetores();

            foreach ($setores as $setor) {
                //tira o código da unidade da sigla
                $set['sigla'] = str_replace('-' . $cod_unidade, '', $setor['nomabvset']);
                $set['nome'] = $setor['nomset'];
                $set['cod_set_replicado'] = $setor['codset'];
                $set['cod_set_pai_replicado'] = $setor['codsetspe'];

                //atualiza quem é o setor pai se não for Unidade (pai == 0)
                if ($set['cod_set_pai_replicado'] != 0) {
                    $setor_pai = Setor::where('cod_set_replicado', $set['cod_set_pai_replicado'])->first();
                    if ($setor_pai) {
                        $set['setor_id'] = $setor_pai->id;
                    }
                }

                $s = Setor::create($set);

                //Atualiza o gerente com as chefias de cada setor
                $chefes = \Uspdev\Replicado\Estrutura::getChefiaSetor($s->cod_set_replicado);

                foreach ($chefes as $chefe) {
                    //verifica se o usuário já é cadastrado
                    $u = User::where('codpes', $chefe['codpes'])->first();
                    //se não for cadastra e vincula ao setor
                    if (!$u) {
                        $u = User::storeByCodpes($chefe['codpes']);
                        $s->users()->attach($u->id, ['funcao' => 'Gerente']);
                        //se for cadastrado vincula ao setor
                    } else {
                        if ($u['id'] != null) {
                            $s->users()->attach($u['id'], ['funcao' => 'Gerente']);
                        }
                    }
                }
            }
        } else {
            $setores = [
                [
                    'sigla' => 'UND',
                    'nome' => 'Nome da unidade',
                    'setor_id' => null
                ],
                [
                    'sigla' => 'ATFN',
                    'nome' => 'Assistência Financeira',
                    'setor_id' => 1
                ],
                [
                    'sigla' => 'ATAC',
                    'nome' => 'Assistência Acadêmica',
                    'setor_id' => 1
                ],
                [
                    'sigla' => 'ATAD',
                    'nome' => 'Assistência Administrativa',
                    'setor_id' => 1
                ],
                [
                    'sigla' => 'STI',
                    'nome' => 'Seção técnica de informática',
                    'setor_id' => 1
                ],
                [
                    'sigla' => 'SVBIBL',
                    'nome' => 'Serviço de biblioteca',
                    'setor_id' => 1
                ],
                [
                    'sigla' => 'DEPTO1',
                    'nome' => 'Departamento acadêmico 1',
                    'setor_id' => 1
                ],
                [
                    'sigla' => 'SVMANOB',
                    'nome' => 'Serviço de manutenção e obras',
                    'setor_id' => 4
                ],
                [
                    'sigla' => 'SVCompras',
                    'nome' => 'Serviço de compras',
                    'setor_id' => 2
                ],
                [
                    'sigla' => 'SVGRAD',
                    'nome' => 'Serviço de graduação',
                    'setor_id' => 3
                ],

            ];

            foreach ($setores as $setor) {
                $s = Setor::create($setor);
                # Vamos colocar gerente em alguns setores apenas
                if (rand(0, 2)) $s->users()->attach(User::inRandomOrder()->first()->id, ['funcao' => 'Gerente']);
            }
        }
    }
}
