<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('states')->delete();

        \DB::table('states')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'Asuncion',
                'country_id' => 173,
                'initial' => null
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'Concepcion',
                'country_id' => 173,
                'initial' => null
            ),
            2 =>
            array (
                'id' => 3,
                'name' => 'San Pedro',
                'country_id' => 173,
                'initial' => null
            ),
            3 =>
            array (
                'id' => 4,
                'name' => 'Cordillera',
                'country_id' => 173,
                'initial' => null
            ),
            4 =>
            array (
                'id' => 5,
                'name' => 'Guaira',
                'country_id' => 173,
                'initial' => null
            ),
            5 =>
            array (
                'id' => 6,
                'name' => 'Caaguazu',
                'country_id' => 173,
                'initial' => null
            ),
            6 =>
            array (
                'id' => 7,
                'name' => 'Caazapa',
                'country_id' => 173,
                'initial' => null
            ),
            7 =>
            array (
                'id' => 8,
                'name' => 'Itapua',
                'country_id' => 173,
                'initial' => null
            ),
            8 =>
            array (
                'id' => 9,
                'name' => 'Misiones',
                'country_id' => 173,
                'initial' => null
            ),
            9 =>
            array (
                'id' => 10,
                'name' => 'Paraguari',
                'country_id' => 173,
                'initial' => null
            ),
            10 =>
            array (
                'id' => 11,
                'name' => 'Alto Parana',
                'country_id' => 173,
                'initial' => null
            ),
            11 =>
            array (
                'id' => 12,
                'name' => 'Central',
                'country_id' => 173,
                'initial' => null
            ),
            12 =>
            array (
                'id' => 13,
                'name' => 'Ñe´embucu',
                'country_id' => 173,
                'initial' => null
            ),
            13 =>
            array (
                'id' => 14,
                'name' => 'Amambay',
                'country_id' => 173,
                'initial' => null
            ),
            14 =>
            array (
                'id' => 15,
                'name' => 'Canindeyu',
                'country_id' => 173,
                'initial' => null
            ),
            15 =>
            array (
                'id' => 16,
                'name' => 'Presidente Hayes',
                'country_id' => 173,
                'initial' => null
            ),
            16 =>
            array (
                'id' => 17,
                'name' => 'Boqueron',
                'country_id' => 173,
                'initial' => null
            ),
            17 =>
            array (
                'id' => 18,
                'name' => 'Alto Paraguay',
                'country_id' => 173,
                'initial' => null
            ),
            18 =>
            array (
                'id' => 97,
                'name' => 'Geral',
                'country_id' => 173,
                'initial' => null
            ),
            19 =>
            array (
                'id' => 98,
                'name' => 'Acre',
                'country_id' => 30,
                'initial' => 'AC'
            ),
            20 =>
            array (
                'id' => 99,
                'name' => 'Alagoas',
                'country_id' => 30,
                'initial' => 'AL'
            ),
            21 =>
            array (
                'id' => 100,
                'name' => 'Amazonas',
                'country_id' => 30,
                'initial' => 'AM'
            ),
            22 =>
            array (
                'id' => 101,
                'name' => 'Amapá',
                'country_id' => 30,
                'initial' => 'AP'
            ),
            23 =>
            array (
                'id' => 102,
                'name' => 'Bahia',
                'country_id' => 30,
                'initial' => 'BA'
            ),
            24 =>
            array (
                'id' => 103,
                'name' => 'Ceará',
                'country_id' => 30,
                'initial' => 'CE'
            ),
            25 =>
            array (
                'id' => 104,
                'name' => 'Distrito Federal',
                'country_id' => 30,
                'initial' => 'DF'
            ),
            26 =>
            array (
                'id' => 105,
                'name' => 'Espírito Santo',
                'country_id' => 30,
                'initial' => 'ES'
            ),
            27 =>
            array (
                'id' => 106,
                'name' => 'Goiás',
                'country_id' => 30,
                'initial' => 'GO'
            ),
            28 =>
            array (
                'id' => 107,
                'name' => 'Maranhão',
                'country_id' => 30,
                'initial' => 'MA'
            ),
            29 =>
            array (
                'id' => 108,
                'name' => 'Minas Gerais',
                'country_id' => 30,
                'initial' => 'MG'
            ),
            30 =>
            array (
                'id' => 109,
                'name' => 'Mato Grosso Do Sul',
                'country_id' => 30,
                'initial' => 'MS'
            ),
            31 =>
            array (
                'id' => 110,
                'name' => 'Mato Grosso',
                'country_id' => 30,
                'initial' => 'MT'
            ),
            32 =>
            array (
                'id' => 111,
                'name' => 'Pará',
                'country_id' => 30,
                'initial' => 'PA'
            ),
            33 =>
            array (
                'id' => 112,
                'name' => 'Paraíba',
                'country_id' => 30,
                'initial' => 'PB'
            ),
            34 =>
            array (
                'id' => 113,
                'name' => 'Pernambuco',
                'country_id' => 30,
                'initial' => 'PE'
            ),
            35 =>
            array (
                'id' => 114,
                'name' => 'Piauí',
                'country_id' => 30,
                'initial' => 'PI'
            ),
            36 =>
            array (
                'id' => 115,
                'name' => 'Paraná',
                'country_id' => 30,
                'initial' => 'PR'
            ),
            37 =>
            array (
                'id' => 116,
                'name' => 'Rio De Janeiro',
                'country_id' => 30,
                'initial' => 'RJ'
            ),
            38 =>
            array (
                'id' => 117,
                'name' => 'Rio Grande Do Norte',
                'country_id' => 30,
                'initial' => 'RN'
            ),
            39 =>
            array (
                'id' => 118,
                'name' => 'Rondônia',
                'country_id' => 30,
                'initial' => 'RO'
            ),
            40 =>
            array (
                'id' => 119,
                'name' => 'Roraima',
                'country_id' => 30,
                'initial' => 'RR'
            ),
            41 =>
            array (
                'id' => 120,
                'name' => 'Rio Grande Do Sul',
                'country_id' => 30,
                'initial' => 'RS'
            ),
            42 =>
            array (
                'id' => 121,
                'name' => 'Santa Catarina',
                'country_id' => 30,
                'initial' => 'SC'
            ),
            43 =>
            array (
                'id' => 122,
                'name' => 'Sergipe',
                'country_id' => 30,
                'initial' => 'SE'
            ),
            44 =>
            array (
                'id' => 123,
                'name' => 'São Paulo',
                'country_id' => 30,
                'initial' => 'SP'
            ),
            45 =>
            array (
                'id' => 124,
                'name' => 'Tocantins',
                'country_id' => 30,
                'initial' => 'TO'
            ),
            46 =>
            array (
                'id' => 129,
                'name' => 'Buenos Aires',
                'country_id' => 10,
                'initial' => null
            ),
            47 =>
            array (
                'id' => 130,
                'name' => 'Catamarca',
                'country_id' => 10,
                'initial' => null
            ),
            48 =>
            array (
                'id' => 131,
                'name' => 'Chaco',
                'country_id' => 10,
                'initial' => null
            ),
            49 =>
            array (
                'id' => 132,
                'name' => 'Chubut',
                'country_id' => 10,
                'initial' => null
            ),
            50 =>
            array (
                'id' => 133,
                'name' => 'Cordoba',
                'country_id' => 10,
                'initial' => null
            ),
            51 =>
            array (
                'id' => 134,
                'name' => 'Corrientes',
                'country_id' => 10,
                'initial' => null
            ),
            52 =>
            array (
                'id' => 135,
                'name' => 'Entre Rios',
                'country_id' => 10,
                'initial' => null
            ),
            53 =>
            array (
                'id' => 136,
                'name' => 'Formosa',
                'country_id' => 10,
                'initial' => null
            ),
            54 =>
            array (
                'id' => 137,
                'name' => 'Jujuy',
                'country_id' => 10,
                'initial' => null
            ),
            55 =>
            array (
                'id' => 138,
                'name' => 'La Pampa',
                'country_id' => 10,
                'initial' => null
            ),
            56 =>
            array (
                'id' => 139,
                'name' => 'La Rioja',
                'country_id' => 10,
                'initial' => null
            ),
            57 =>
            array (
                'id' => 140,
                'name' => 'Mendoza',
                'country_id' => 10,
                'initial' => null
            ),
            58 =>
            array (
                'id' => 141,
                'name' => 'Misiones',
                'country_id' => 10,
                'initial' => null
            ),
            59 =>
            array (
                'id' => 142,
                'name' => 'Neuquen',
                'country_id' => 10,
                'initial' => null
            ),
            60 =>
            array (
                'id' => 143,
                'name' => 'Rio Negro',
                'country_id' => 10,
                'initial' => null
            ),
            61 =>
            array (
                'id' => 144,
                'name' => 'Salta',
                'country_id' => 10,
                'initial' => null
            ),
            62 =>
            array (
                'id' => 145,
                'name' => 'San Juan',
                'country_id' => 10,
                'initial' => null
            ),
            63 =>
            array (
                'id' => 146,
                'name' => 'San Luis',
                'country_id' => 10,
                'initial' => null
            ),
            64 =>
            array (
                'id' => 147,
                'name' => 'Santa Cruz',
                'country_id' => 10,
                'initial' => null
            ),
            65 =>
            array (
                'id' => 148,
                'name' => 'Santa Fe',
                'country_id' => 10,
                'initial' => null
            ),
            66 =>
            array (
                'id' => 149,
                'name' => 'Santiago Del Estero',
                'country_id' => 10,
                'initial' => null
            ),
            67 =>
            array (
                'id' => 150,
                'name' => 'Tierra Del Fuego',
                'country_id' => 10,
                'initial' => null
            ),
            68 =>
            array (
                'id' => 151,
                'name' => 'Tucuman',
                'country_id' => 10,
                'initial' => null
            ),
            69 =>
            array (
                'id' => 160,
                'name' => 'Buenos Aires Ciudad Aut',
                'country_id' => 10,
                'initial' => null
            ),
        ));


    }
}
