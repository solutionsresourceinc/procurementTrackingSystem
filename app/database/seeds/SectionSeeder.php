<?php
class SectionSeeder extends Seeder {
    public function run()
    {
        DB::table('section')->delete();
        // WORKFLOW 1
        $sec = new Section;
        $sec->id = '1';
        $sec->section_order_id = '1';
        $sec->sectionName = 'Purchase Request';
        $sec->workflow_id = '1';
        $sec->save();

        $sec = new Section;
        $sec->id = '2';
        $sec->section_order_id = '2';
        $sec->sectionName = 'BAC Requirements';
        $sec->workflow_id = '1';
        $sec->save();

        $sec = new Section;
        $sec->id = '3';
        $sec->section_order_id = '3';
        $sec->sectionName = 'Purchase Order';
        $sec->workflow_id = '1';
        $sec->save();

        $sec = new Section;
        $sec->id = '4';
        $sec->section_order_id = '4';
        $sec->sectionName = 'Voucher';
        $sec->workflow_id = '1';
        $sec->save();

        // WORKFLOW 2
        $sec = new Section;
        $sec->id = '5';
        $sec->section_order_id = '1';
        $sec->sectionName = 'Purchase Request';
        $sec->workflow_id = '2';
        $sec->save();

        $sec = new Section;
        $sec->id = '6';
        $sec->section_order_id = '2';
        $sec->sectionName = 'BAC Requirements';
        $sec->workflow_id = '2';
        $sec->save();

        $sec = new Section;
        $sec->id = '7';
        $sec->section_order_id = '3';
        $sec->sectionName = 'Purchase Order';
        $sec->workflow_id = '2';
        $sec->save();

        $sec = new Section;
        $sec->id = '8';
        $sec->section_order_id = '4';
        $sec->sectionName = 'Voucher';
        $sec->workflow_id = '2';
        $sec->save();

        // WORKFLOW 3
        $sec = new Section;
        $sec->id = '9';
        $sec->section_order_id = '1';
        $sec->sectionName = 'Purchase Request';
        $sec->workflow_id = '3';
        $sec->save();

        $sec = new Section;
        $sec->id = '10';
        $sec->section_order_id = '2';
        $sec->sectionName = 'BAC Requirements';
        $sec->workflow_id = '3';
        $sec->save();

        $sec = new Section;
        $sec->id = '11';
        $sec->section_order_id = '3';
        $sec->sectionName = 'Purchase Order';
        $sec->workflow_id = '3';
        $sec->save();

        $sec = new Section;
        $sec->id = '12';
        $sec->section_order_id = '4';
        $sec->sectionName = 'Voucher';
        $sec->workflow_id = '3';
        $sec->save();
    }
}