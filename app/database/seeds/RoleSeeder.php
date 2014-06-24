<?php
class RoleSeeder extends Seeder {

    public function run()
    {
        Role::truncate();
        
        Role::create([
            'id'        => '1',
            'name'    => 'Requisitioner',
        ]);

        Role::create([
            'id'        => '2',
            'name'    => 'Procurement Personnel',
        ]);

        Role::create([
            'id'        => '3',
            'name'    => 'Administrator',
        ]);
    }
}