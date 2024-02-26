<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('admins')->delete();

        \DB::table('admins')->insert(array(
            0 =>
            array(
                'id' => 1,
                'name' => 'Crow Admin',
                'email' => 'contato@agenciacrow.com.py',
                'phone' => '000',
                'role_id' => 0,
                'photo' => null,
                'password' => '$2y$10$THm8Qu99Ita7FPE3.ztzsOQyttJ1eEepR67ZTBfJ4xERAU8b0tjbW',
                'status' => 1,
                'remember_token' => 'fcF7oeBr0YbnY0g11er5lwt6QaEgQ7k2rKYjXiBnzdj0PGrA5TJow93AaA63',
                'created_at' => '2018-02-28 20:27:08',
                'updated_at' => '2020-11-11 17:19:17',
                'shop_name' => '',
            ),
            1 =>
            array(
                'id' => 2,
                'name' => 'Suporte',
                'email' => 'suporte@agenciacrow.com.br',
                'phone' => '000',
                'role_id' => 0,
                'photo' => null,
                'password' => '$2y$10$BcIm5KGBN0tcDmWNFw4m5.qZcnOPeyd//SKMK/ml8UsRJJQdtqGjq',
                'status' => 1,
                'remember_token' => 'fcF7oeBr0YbnY0g11er5lwt6QaEgQ7k2rKYjXiBnzdj0PGrA5TJow93AaA63',
                'created_at' => now(),
                'updated_at' => now(),
                'shop_name' => '',
            )
        ));
    }
}
