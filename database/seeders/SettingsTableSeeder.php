<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('settings')->delete();

        \DB::table('settings')->insert(array (
            0 =>
            array (
                'id' => 1,
                'key' => 'first_installment',
                'name' => 'القسط الاول',
                'description' => '',
                'value' => 1900,
                'field' => '{"name":"value","label":"Value","type":"number"}',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'key' => 'second_installment',
                'name' => 'القسط الثاني',
                'description' => '',
                'value' => 2500,
                'field' => '{"name":"value","label":"Value","type":"number"}',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'key' => 'third_installment',
                'name' => 'القسط الثالث',
                'description' => '',
                'value' => 2500,
                'field' => '{"name":"value","label":"Value","type":"number"}',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 =>
            array (
                'id' => 4,
                'key' => 'fourth_installment',
                'name' => 'القسط الرابع',
                'description' => '',
                'value' => 2500,
                'field' => '{"name":"value","label":"Value","type":"number"}',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 =>
            array (
                'id' => 5,
                'key' => 'total',
                'name' => 'الأجمالي',
                'description' => '',
                'value' => 9400,
                'field' => '{"name":"value","label":"Value","type":"number"}',
                'active' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),

        ));


    }
}
