<?php
class AssignedRolesSeeder extends Seeder {

    public function run()
    {
        Assigned::truncate();
        
        Assigned::create([
            'id'        => '1',
            'user_id'    => '1',
            'role_id' => '3', // 3 is for admin
        ]);

        Assigned::create([
            'id'        => '2',
            'user_id'    => '2',
            'role_id' => '2', // 2 is for personnel
        ]);

        Assigned::create([
            'id'        => '3',
            'user_id'    => '3',
            'role_id' => '1',  // 3 is for requisitoner
        ]);


    }
}