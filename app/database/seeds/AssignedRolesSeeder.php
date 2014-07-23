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

        // FOR TESTING PURPOSES

        Assigned::create([
            'id'        => '4',
            'user_id'    => '4',
            'role_id' => '2', // 2 is for personnel
        ]);

        Assigned::create([
            'id'        => '5',
            'user_id'    => '5',
            'role_id' => '2', // 2 is for personnel
        ]);

        Assigned::create([
            'id'        => '6',
            'user_id'    => '6',
            'role_id' => '2', // 2 is for personnel
        ]);

        Assigned::create([
            'id'        => '7',
            'user_id'    => '7',
            'role_id' => '2', // 2 is for personnel
        ]);

        Assigned::create([
            'id'        => '8',
            'user_id'    => '8',
            'role_id' => '1', // 1 is for requisitioner
        ]);
    }
}