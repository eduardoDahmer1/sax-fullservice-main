<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleTranslationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('role_translations')->delete();

        \DB::table('role_translations')->insert(array (
            0 =>
            array (
                'id' => 1,
                'role_id' => 16,
                'locale' => 'pt-br',
                'name' => 'Administrador',
            ),
            1 =>
            array (
                'id' => 2,
                'role_id' => 17,
                'locale' => 'pt-br',
                'name' => 'Moderador',
            ),
            2 =>
            array (
                'id' => 3,
                'role_id' => 18,
                'locale' => 'pt-br',
                'name' => 'Funcionário',
            ),
        ));


    }
}
