<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        $data = array('Админ', 'Менеджер', 'Ресепшн');

        foreach ($data as $role)
        {
        	DB::table('CRM_roles')->insert([
		      'name' => $role
		    ]);
        }
    }
}
