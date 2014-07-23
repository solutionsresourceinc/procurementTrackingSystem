<?php
class OfficeSeeder extends Seeder {

    public function run()
    {
        Office::truncate();
        
        Office::create([
            'id'        => '1',
            'officeName'    => 'BAC',
        ]);

        Office::create([
            'id'        => '2',
            'officeName'    => 'OCA',
        ]);

        Office::create([
            'id'        => '3',
            'officeName'    => 'AERO',
        ]);

        // OFFICES FOR TESTING PURPOSES
        Office::create([
            'id'        => '4',
            'officeName'    => 'EHRO',
        ]);
    }
}