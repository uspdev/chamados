<?php

namespace Database\Seeders;

use App\Models\Setor;
use App\Models\User;
use Illuminate\Database\Seeder;

class SetorReplicadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cod_unidade = getenv('REPLICADO_CODUNDCLG');

        # a lista de setores já vem ordenado de forma a criar os setores pai antes dos filhos
        $setores_repl = \Uspdev\Replicado\Estrutura::listarSetores();

        foreach ($setores_repl as $setor_repl) {
            //cria setores com base nos dados replicados
            $setor = new Setor;
            $setor->sigla = str_replace('-' . $cod_unidade, '', $setor_repl['nomabvset']);
            $setor->nome = $setor_repl['nomset'];
            $setor->cod_set_replicado = $setor_repl['codset'];
            $setor->cod_set_pai_replicado = $setor_repl['codsetspe'];

            //atualiza quem é o setor pai
            # talvez tenha de fazer 2 loops caso o setor pai nãp esteja cadastrado no BD. (Masaki 26-11-2020)
            $setor_pai = Setor::where('cod_set_replicado', $setor->cod_set_pai_replicado)->first();
            if ($setor_pai) {
                $setor->setor_id = $setor_pai->id;
            }

            $setor->save();

            # se for a unidade não vamos cadastrar pessoas, pois tem muita gente aqui
            if (!empty($setor->setor_id)) {
                //Atualiza o gerente com as chefias de cada setor
                $chefes = \Uspdev\Replicado\Estrutura::getChefiaSetor($setor_repl['codset']);
                foreach ($chefes as $chefe) {
                    $u = User::obterOuCriarPorCodpes($chefe['codpes']);
                    $setor->users()->attach($u->id, ['funcao' => 'Gerente']);
                }
            }

        }

    }
}
