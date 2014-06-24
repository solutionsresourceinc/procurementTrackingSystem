<?php
class TaskSeeder extends Seeder {

    public function run()
    {
        DB::table('tasks')->delete();
        // TASK TYPE : 0 = ADHOC, 1 = REVIEW TASK

        // WORKFLOW 1 | SECTION 1 <-- BELOW 50,000
        $task = new Task;
        $task->id = '1';
        $task->wf_id = '1';
        $task->section_id = '1';
        $task->taskName = 'PPMP CERTIFICATION';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '2';
        $task->wf_id = '1';
        $task->section_id = '1';
        $task->taskName = 'DATE OF PR';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '3';
        $task->wf_id = '1';
        $task->section_id = '1';
        $task->taskName = 'GSD';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '4';
        $task->wf_id = '1';
        $task->section_id = '1';
        $task->taskName = 'BUDGET / ACTG';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '5';
        $task->wf_id = '1';
        $task->section_id = '1';
        $task->taskName = 'PA';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '6';
        $task->wf_id = '1';
        $task->section_id = '1';
        $task->taskName = 'PGO';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '7';
        $task->wf_id = '1';
        $task->section_id = '1';
        $task->taskName = 'GSD';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();


        // WORKFLOW 1 | SECTION 2
        $task = new Task;
        $task->id = '8';
        $task->wf_id = '1';
        $task->section_id = '2';
        $task->taskName = '3 RFQ/CANVASS';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '9';
        $task->wf_id = '1';
        $task->section_id = '2';
        $task->taskName = 'ABSTRACT OF QUOTES';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        // WORKFLOW 1 | SECTION 3
        $task = new Task;
        $task->id = '10';
        $task->wf_id = '1';
        $task->section_id = '3';
        $task->taskName = 'GSD';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '11';
        $task->wf_id = '1';
        $task->section_id = '3';
        $task->taskName = 'ACTG';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '12';
        $task->wf_id = '1';
        $task->section_id = '3';
        $task->taskName = 'PA';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '13';
        $task->wf_id = '1';
        $task->section_id = '3';
        $task->taskName = 'PGO';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '14';
        $task->wf_id = '1';
        $task->section_id = '3';
        $task->taskName = 'BAC (DELIVERY)';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        // WORKFLOW 1 | SECTION 4
        $task = new Task;
        $task->id = '15';
        $task->wf_id = '1';
        $task->section_id = '4';
        $task->taskName = 'BUDGET';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '16';
        $task->wf_id = '1';
        $task->section_id = '4';
        $task->taskName = 'ACCOUNTING';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '17';
        $task->wf_id = '1';
        $task->section_id = '4';
        $task->taskName = 'TREASURY';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '18';
        $task->wf_id = '1';
        $task->section_id = '4';
        $task->taskName = 'PA';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '19';
        $task->wf_id = '1';
        $task->section_id = '4';
        $task->taskName = 'PGO';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        //WORKFLOW 2 | SECTION 1 <-- ABOVE 50,000 BELOW 500,000

        $task = new Task;
        $task->id = '20';
        $task->wf_id = '2';
        $task->section_id = '1';
        $task->taskName = 'PPMP CERTIFICATION';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '21';
        $task->wf_id = '2';
        $task->section_id = '1';
        $task->taskName = 'DATE OF PR';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '22';
        $task->wf_id = '2';
        $task->section_id = '1';
        $task->taskName = 'GSD';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '23';
        $task->wf_id = '2';
        $task->section_id = '1';
        $task->taskName = 'BUDGET / ACTG';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '24';
        $task->wf_id = '2';
        $task->section_id = '1';
        $task->taskName = 'PA';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '25';
        $task->wf_id = '2';
        $task->section_id = '1';
        $task->taskName = 'PGO';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '26';
        $task->wf_id = '2';
        $task->section_id = '1';
        $task->taskName = 'GSD';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        //WORKFLOW 2 | SECTION 2
        $task = new Task;
        $task->id = '27';
        $task->wf_id = '2';
        $task->section_id = '2';
        $task->taskName = '3 RFQ/CANVASS';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '28';
        $task->wf_id = '2';
        $task->section_id = '2';
        $task->taskName = 'ABSTRACT OF QUOTES';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '29';
        $task->wf_id = '2';
        $task->section_id = '2';
        $task->taskName = 'BAC RESOLUTION';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '30';
        $task->wf_id = '2';
        $task->section_id = '2';
        $task->taskName = 'NOTICE OF AWARD';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        //WORKFLOW 2 | SECTION 3
        $task = new Task;
        $task->id = '31';
        $task->wf_id = '2';
        $task->section_id = '3';
        $task->taskName = 'GSD';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '32';
        $task->wf_id = '2';
        $task->section_id = '3';
        $task->taskName = 'ACTG';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '33';
        $task->wf_id = '2';
        $task->section_id = '3';
        $task->taskName = 'PA';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '34';
        $task->wf_id = '2';
        $task->section_id = '3';
        $task->taskName = 'PGO';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '35';
        $task->wf_id = '2';
        $task->section_id = '3';
        $task->taskName = 'BAC (DELIVERY)';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        //WORKFLOW 2 | SECTION 4
        $task = new Task;
        $task->id = '36';
        $task->wf_id = '2';
        $task->section_id = '4';
        $task->taskName = 'BUDGET';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '37';
        $task->wf_id = '2';
        $task->section_id = '4';
        $task->taskName = 'ACCOUNTING';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '38';
        $task->wf_id = '2';
        $task->section_id = '4';
        $task->taskName = 'TREASURY';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '39';
        $task->wf_id = '2';
        $task->section_id = '4';
        $task->taskName = 'PA';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '40';
        $task->wf_id = '2';
        $task->section_id = '4';
        $task->taskName = 'PGO';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        //WORKFLOW 3 | SECTION 1 <-- ABOVE 500,000
        $task = new Task;
        $task->id = '41';
        $task->wf_id = '3';
        $task->section_id = '1';
        $task->taskName = 'DATE OF P.R.';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '42';
        $task->wf_id = '3';
        $task->section_id = '1';
        $task->taskName = 'RECEIVED GSD';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '43';
        $task->wf_id = '3';
        $task->section_id = '1';
        $task->taskName = 'BUDGET/ACCTNG';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '44';
        $task->wf_id = '3';
        $task->section_id = '1';
        $task->taskName = 'P.A. OFFICE';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '45';
        $task->wf_id = '3';
        $task->section_id = '1';
        $task->taskName = 'PGO';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '46';
        $task->wf_id = '3';
        $task->section_id = '1';
        $task->taskName = 'GSD RELEASE';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        //WORKFLOW 3 | SECTION 2
        $task = new Task;
        $task->id = '47';
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'PHILGEPS DATE PUBLISHED';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '48';
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'ITB DATE PUBLISHED';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '49';
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'ELIGIBILITY DOCUMENTS';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

       	$task = new Task;
        $task->id = '50';
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'TWG EVALUATION DATE';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '51';
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'AOB DATE (AFTER TWG EVALUATION)';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '52';
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'BAC RESO DATE (SIGNED BY ALL BAC MEMBERS)';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '53';
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'NOTICE OF AWARD DATE';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '54';
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'NOTICE TO PROCEED';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();
    	
    	$task = new Task;
        $task->id = '55';
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'PHILGEPS AWARD PUBLISHED';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        //WORKFLOW 3 | SECTION 3
        $task = new Task;
        $task->id = '56';
        $task->wf_id = '3';
        $task->section_id = '3';
        $task->taskName = 'RECEIVED GSD';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '57';
        $task->wf_id = '3';
        $task->section_id = '3';
        $task->taskName = 'ACCOUNTING OFFICE';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '58';
        $task->wf_id = '3';
        $task->section_id = '3';
        $task->taskName = 'P.A. OFFICE';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '59';
        $task->wf_id = '3';
        $task->section_id = '3';
        $task->taskName = 'GOVERNORS OFFICE';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        //WORKFLOW 3 | SECTION 4
        $task = new Task;
        $task->id = '60';
        $task->wf_id = '3';
        $task->section_id = '4';
        $task->taskName = 'BUDGET';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '61';
        $task->wf_id = '3';
        $task->section_id = '4';
        $task->taskName = 'ACCOUNTING';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '62';
        $task->wf_id = '3';
        $task->section_id = '4';
        $task->taskName = 'TREASURY';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '63';
        $task->wf_id = '3';
        $task->section_id = '4';
        $task->taskName = 'P.A. OFFICE';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '64';
        $task->wf_id = '3';
        $task->section_id = '4';
        $task->taskName = 'PGO';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '65';
        $task->wf_id = '3';
        $task->section_id = '4';
        $task->taskName = 'ACCOUNTING';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();

        $task = new Task;
        $task->id = '66';
        $task->wf_id = '3';
        $task->section_id = '4';
        $task->taskName = 'CHECK RELEASED';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->save();
    }
}