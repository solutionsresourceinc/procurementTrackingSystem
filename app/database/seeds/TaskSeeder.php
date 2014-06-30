<?php
class TaskSeeder extends Seeder {

    public function run()
    {
        DB::table('tasks')->delete();
        // TASK TYPE : 0 = ADHOC, 1 = REVIEW TASK

        // WORKFLOW 1 | SECTION 1 <-- BELOW 50,000
        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '1';
        $task->taskName = 'PPMP CERTIFICATION';
        $task->taskType = '1';
        $task->maxDuration = '3';
        $task->order_id = '1';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '1';
        $task->taskName = 'GSD';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '2';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '1';
        $task->taskName = 'BUDGET / ACTG';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '3';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '1';
        $task->taskName = 'PA';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '4';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '1';
        $task->taskName = 'PGO';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '5';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '1';
        $task->taskName = 'GSD';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '6';
        $task->save();


        // WORKFLOW 1 | SECTION 2
        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '2';
        $task->taskName = '3 RFQ/CANVASS';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '1';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '2';
        $task->taskName = 'ABSTRACT OF QUOTES';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '2';
        $task->save();

        // WORKFLOW 1 | SECTION 3
        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '3';
        $task->taskName = 'GSD';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '1';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '3';
        $task->taskName = 'ACTG';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '2';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '3';
        $task->taskName = 'PA';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '3';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '3';
        $task->taskName = 'PGO';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '4';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '3';
        $task->taskName = 'BAC (DELIVERY)';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '5';
        $task->save();

        // WORKFLOW 1 | SECTION 4
        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '4';
        $task->taskName = 'BUDGET';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '1';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '4';
        $task->taskName = 'ACCOUNTING';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '2';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '4';
        $task->taskName = 'TREASURY';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '3';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '4';
        $task->taskName = 'PA';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '4';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '4';
        $task->taskName = 'PGO';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '5';
        $task->save();

        //WORKFLOW 2 | SECTION 1 <-- ABOVE 50,000 BELOW 500,000

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '1';
        $task->taskName = 'PPMP CERTIFICATION';
        $task->taskType = '1';
        $task->maxDuration = '3';
        $task->order_id = '1';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '1';
        $task->taskName = 'GSD';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '2';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '1';
        $task->taskName = 'BUDGET / ACTG';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '3';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '1';
        $task->taskName = 'PA';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '4';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '1';
        $task->taskName = 'PGO';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '5';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '1';
        $task->taskName = 'GSD';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '6';
        $task->save();

        //WORKFLOW 2 | SECTION 2 ====================================================================================================================================================
        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '2';
        $task->taskName = 'PGEPS POSTING';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '1';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '2';
        $task->taskName = '3 RFQ/CANVASS';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '2';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '2';
        $task->taskName = 'ABSTRACT OF QUOTES';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '3';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '2';
        $task->taskName = 'BAC RESOLUTION';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '4';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '2';
        $task->taskName = 'NOTICE OF AWARD';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '5';
        $task->save();

        //WORKFLOW 2 | SECTION 3
        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '3';
        $task->taskName = 'GSD';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '1';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '3';
        $task->taskName = 'ACTG';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '2';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '3';
        $task->taskName = 'PA';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '3';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '3';
        $task->taskName = 'PGO';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '4';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '3';
        $task->taskName = 'BAC (DELIVERY)';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '5';
        $task->save();

        //WORKFLOW 2 | SECTION 4
        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '4';
        $task->taskName = 'BUDGET';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '1';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '4';
        $task->taskName = 'ACCOUNTING';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '2';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '4';
        $task->taskName = 'TREASURY';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '3';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '4';
        $task->taskName = 'PA';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '4';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '4';
        $task->taskName = 'PGO';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '5';
        $task->save();

        //WORKFLOW 3 | SECTION 1 <-- ABOVE 500,000
        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '1';
        $task->taskName = 'RECEIVED GSD';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '1';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '1';
        $task->taskName = 'BUDGET/ACCTNG';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '2';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '1';
        $task->taskName = 'P.A. OFFICE';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '3';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '1';
        $task->taskName = 'PGO';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '4';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '1';
        $task->taskName = 'GSD RELEASE';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '5';
        $task->save();

        //WORKFLOW 3 | SECTION 2
        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'PHILGEPS DATE PUBLISHED';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '1';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'ITB DATE PUBLISHED';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '2';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'ELIGIBILITY DOCUMENTS';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '3';
        $task->save();

       	$task = new Task;
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'TWG EVALUATION DATE';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '4';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'AOB DATE (AFTER TWG EVALUATION)';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '5';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'BAC RESO DATE (SIGNED BY ALL BAC MEMBERS)';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '6';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'NOTICE OF AWARD DATE';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '7';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'NOTICE TO PROCEED';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '8';
        $task->save();
    	
    	$task = new Task;
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'PHILGEPS AWARD PUBLISHED';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '9';
        $task->save();

        //WORKFLOW 3 | SECTION 3
        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '3';
        $task->taskName = 'RECEIVED GSD';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '1';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '3';
        $task->taskName = 'ACCOUNTING OFFICE';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '2';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '3';
        $task->taskName = 'P.A. OFFICE';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '3';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '3';
        $task->taskName = 'GOVERNORS OFFICE';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '4';
        $task->save();

        //WORKFLOW 3 | SECTION 4
        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '4';
        $task->taskName = 'BUDGET';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '1';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '4';
        $task->taskName = 'ACCOUNTING';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '2';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '4';
        $task->taskName = 'TREASURY';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '3';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '4';
        $task->taskName = 'P.A. OFFICE';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '4';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '4';
        $task->taskName = 'PGO';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '5';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '4';
        $task->taskName = 'ACCOUNTING';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '6';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '4';
        $task->taskName = 'CHECK RELEASED';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = '7';
        $task->save();
    }
}