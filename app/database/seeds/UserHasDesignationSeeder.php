<?php
class UserHasDesignationSeeder extends Seeder {

    public function run()
    {
        UserHasDesignation::truncate();
        
        UserHasDesignation::create([
            'users_id'        => '1',
            'designation_id'    => '0',
        ]);
    }
}