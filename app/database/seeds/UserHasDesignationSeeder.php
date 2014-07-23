<?php
class UserHasDesignationSeeder extends Seeder {

    public function run()
    {
        UserHasDesignation::truncate();
        
        UserHasDesignation::create([
            'users_id'        => '1',
            'designation_id'    => '0',
        ]);

        UserHasDesignation::create([
            'users_id'        => '2',
            'designation_id'    => '0',
        ]);

        UserHasDesignation::create([
            'users_id'        => '3',
            'designation_id'    => '0',
        ]);

        // FOR TESTING PURPOSES
        UserHasDesignation::create([
            'users_id'        => '4',
            'designation_id'    => '0',
        ]);

        UserHasDesignation::create([
            'users_id'        => '5',
            'designation_id'    => '0',
        ]);

        UserHasDesignation::create([
            'users_id'        => '6',
            'designation_id'    => '0',
        ]);

        UserHasDesignation::create([
            'users_id'        => '7',
            'designation_id'    => '0',
        ]);

        UserHasDesignation::create([
            'users_id'        => '8',
            'designation_id'    => '0',
        ]);
    }
}