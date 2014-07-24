<?php
class DesignationSeeder extends Seeder {
    public function run()
    {
        // THIS SEEDS ARE FOR TESTING PURPOSES ONLY
        DB::table('designation')->delete();

        $designation = new Designation;
        $designation->designation = 'PP Conference Personnel';
        $designation->save();

        $designation = new Designation;
        $designation->designation = 'Philgeps Personnel';
        $designation->save();

        $designation = new Designation;
        $designation->designation = 'Advertisement Personnel';
        $designation->save();

        $designation = new Designation;
        $designation->designation = 'Pre-Bid Personnel';
        $designation->save();

        $designation = new Designation;
        $designation->designation = 'ITB Personnel';
        $designation->save();

        $designation = new Designation;
        $designation->designation = 'Eligibility Personnel';
        $designation->save();

        $designation = new Designation;
        $designation->designation = 'TWG Personnel';
        $designation->save();

        $designation = new Designation;
        $designation->designation = 'AOB Personnel';
        $designation->save();

        $designation = new Designation;
        $designation->designation = 'BAC Reso Personnel';
        $designation->save();

        $designation = new Designation;
        $designation->designation = 'NOA Personnel';
        $designation->save();

        $designation = new Designation;
        $designation->designation = 'NOP Personnel';
        $designation->save();

        $designation = new Designation;
        $designation->designation = 'PA Personnel';
        $designation->save();
    }
}