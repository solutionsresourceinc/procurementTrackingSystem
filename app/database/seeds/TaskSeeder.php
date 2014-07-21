<?php
class TaskSeeder extends Seeder {

    public function run()
    {
        DB::table('tasks')->delete();
        // TASK TYPE : 0 = ADHOC, 1 = REVIEW TASK

        // WORKFLOW 1 | SECTION 1 <-- BELOW 50,000
        $orderID = 0; // controller for order_id

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '1';
        $task->taskName = 'PPMP CERTIFICATION';
        $task->taskType = 'certification';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '1';
        $task->taskName = 'GSD';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = '2';
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '1';
        $task->taskName = 'BUDGET / ACTG';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '1';
        $task->taskName = 'PA';
        $task->taskType = 'normal';;
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '1';
        $task->taskName = 'PGO';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '1';
        $task->taskName = 'GSD';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();


        // WORKFLOW 1 | SECTION 2
        $orderID = 0; // controller for order_id

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '2';
        $task->taskName = '3 RFQ/CANVASS';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '2';
        $task->taskName = 'ABSTRACT OF QUOTES';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        // WORKFLOW 1 | SECTION 3
        $orderID = 0; // controller for order_id

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '3';
        $task->taskName = 'LCRB / HRB / SUPPLIER';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '3';
        $task->taskName = 'GSD';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '3';
        $task->taskName = 'ACTG';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '3';
        $task->taskName = 'PA';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '3';
        $task->taskName = 'PGO';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '3';
        $task->taskName = 'BAC (DELIVERY)';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        // WORKFLOW 1 | SECTION 4
        $orderID = 0; // controller for order_id

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '4';
        $task->taskName = 'CHEQUE';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '4';
        $task->taskName = 'BUDGET';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '4';
        $task->taskName = 'ACCOUNTING';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '4';
        $task->taskName = 'TREASURY';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '4';
        $task->taskName = 'PA';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '1';
        $task->section_id = '4';
        $task->taskName = 'PGO';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        //WORKFLOW 2 | SECTION 1 <-- ABOVE 50,000 BELOW 500,000
        $orderID = 0; // controller for order_id

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '1';
        $task->taskName = 'PPMP CERTIFICATION';
        $task->taskType = 'certification';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '1';
        $task->taskName = 'GSD';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '1';
        $task->taskName = 'BUDGET / ACTG';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '1';
        $task->taskName = 'PA';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = '4';
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '1';
        $task->taskName = 'PGO';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '1';
        $task->taskName = 'GSD';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        //WORKFLOW 2 | SECTION 2 ====================================================================================================================================================
        $orderID = 0; // controller for order_id

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '2';
        $task->taskName = 'PGEPS POSTING';
        $task->taskType = 'posting';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '2';
        $task->taskName = '3 RFQ/CANVASS';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '2';
        $task->taskName = 'ABSTRACT OF QUOTATION';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '2';
        $task->taskName = 'BAC RESOLUTION';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '2';
        $task->taskName = 'NOTICE OF AWARD';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        //WORKFLOW 2 | SECTION 3
        $orderID = 0; // controller for order_id

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '3';
        $task->taskName = 'GSD';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '3';
        $task->taskName = 'ACTG';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '3';
        $task->taskName = 'PA';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '3';
        $task->taskName = 'PGO';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '3';
        $task->taskName = 'BAC (DELIVERY)';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        //WORKFLOW 2 | SECTION 4
        $orderID = 0; // controller for order_id

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '4';
        $task->taskName = 'CHEQUE';
        $task->taskType = 'cheque';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '4';
        $task->taskName = 'BUDGET';
        $task->taskType = '0';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '4';
        $task->taskName = 'ACCOUNTING';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '4';
        $task->taskName = 'TREASURY';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '4';
        $task->taskName = 'PA';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '2';
        $task->section_id = '4';
        $task->taskName = 'PGO';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        //WORKFLOW 3 | SECTION 1 <-- ABOVE 500,000
        $orderID = 0; // controller for order_id

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '1';
        $task->taskName = 'GSD';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '1';
        $task->taskName = 'BUDGET / ACTG';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '1';
        $task->taskName = 'P.A. OFFICE';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '1';
        $task->taskName = 'PGO';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '1';
        $task->taskName = 'GSD RELEASE';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        //WORKFLOW 3 | SECTION 2
        $orderID = 0; // controller for order_id

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'PRE-PROCUREMENT CONFERENCE';
        $task->taskType = 'conference';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'PHILGEPS DATE PUBLISHED';
        $task->taskType = 'published';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'ADVERTISMENT';
        $task->taskType = 'conference';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'PRE-BID CONFERENCE';
        $task->taskType = 'conference';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'ITB DATE PUBLISHED';
        $task->taskType = 'published';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'ELIGIBILITY DOCUMENTS';
        $task->taskType = 'documents';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

       	$task = new Task;
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'TWG EVALUATION DATE';
        $task->taskType = 'evaluation';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'AOB DATE (AFTER TWG EVALUATION)';
        $task->taskType = 'evaluation';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'BAC RESO DATE (SIGNED BY ALL BAC MEMBERS)';
        $task->taskType = 'evaluation';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'NOTICE OF AWARD DATE';
        $task->taskType = 'contract';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'NOTICE TO PROCEED';
        $task->taskType = 'meeting';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();
    	
    	$task = new Task;
        $task->wf_id = '3';
        $task->section_id = '2';
        $task->taskName = 'PHILGEPS AWARD PUBLISHED';
        $task->taskType = 'published';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        //WORKFLOW 3 | SECTION 3
        $orderID = 0; // controller for order_id

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '3';
        $task->taskName = 'LCRB / HRB / SUPPLIER';
        $task->taskType = 'supplier';
        $task->maxDuration = '3';
        $task->order_id = '1';
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '3';
        $task->taskName = 'GSD';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '3';
        $task->taskName = 'ACTG';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '3';
        $task->taskName = 'P.A.';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '3';
        $task->taskName = 'PGO';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '3';
        $task->taskName = 'BAC (DELIVERY)';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        //WORKFLOW 3 | SECTION 4
        $orderID = 0; // controller for order_id

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '4';
        $task->taskName = 'BUDGET';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '4';
        $task->taskName = 'ACCOUNTING';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '4';
        $task->taskName = 'TREASURY';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '4';
        $task->taskName = 'P.A.';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '4';
        $task->taskName = 'PGO';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '3';
        $task->section_id = '4';
        $task->taskName = 'CHECK RELEASED';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        // WORKFLOW 4 | SECTION 1
        $orderID = 0; // controller for order_id

        $task = new Task;
        $task->wf_id = '4';
        $task->section_id = '1';
        $task->taskName = 'DATE OF P.R.';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '4';
        $task->section_id = '1';
        $task->taskName = 'RECEIVED GSD';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();
        
        $task = new Task;
        $task->wf_id = '4';
        $task->section_id = '1';
        $task->taskName = 'BUDGET/ACCTNG';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '4';
        $task->section_id = '1';
        $task->taskName = 'P.A. OFFICE';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();
    
        $task = new Task;
        $task->wf_id = '4';
        $task->section_id = '1';
        $task->taskName = 'PGO';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        // WORKFLOW 4 | SECTION 2
        $orderID = 0; // controller for order_id

        $task = new Task;
        $task->wf_id = '4';
        $task->section_id = '2';
        $task->taskName = 'LABOR PROPOSAL DATE';
        $task->taskType = 'conference';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '4';
        $task->section_id = '2';
        $task->taskName = 'NOTICE OF AWARD DATE';
        $task->taskType = 'conference';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '4';
        $task->section_id = '2';
        $task->taskName = 'NOTICE TO PROCEED DATE';
        $task->taskType = 'conference';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '4';
        $task->section_id = '2';
        $task->taskName = 'PAKYAW CONTRACT DATE';
        $task->taskType = 'conference';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '4';
        $task->section_id = '2';
        $task->taskName = 'ACCOMPLISHMENT REPORT DATE';
        $task->taskType = 'conference';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        // WORKFLOW 4 | SECTION 3
        $orderID = 0; // controller for order_id

        $task = new Task;
        $task->wf_id = '4';
        $task->section_id = '3';
        $task->taskName = 'BUDGET';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '4';
        $task->section_id = '3';
        $task->taskName = 'ACCOUNTING';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '4';
        $task->section_id = '3';
        $task->taskName = 'TREASURY';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '4';
        $task->section_id = '3';
        $task->taskName = 'P.A. OFFICE';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '4';
        $task->section_id = '3';
        $task->taskName = 'PGO';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '4';
        $task->section_id = '3';
        $task->taskName = 'ACCOUNTING';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();

        $task = new Task;
        $task->wf_id = '4';
        $task->section_id = '3';
        $task->taskName = 'CHECK RELEASED';
        $task->taskType = 'normal';
        $task->maxDuration = '3';
        $task->order_id = $orderID++;
        $task->description = 'This is a task description';
        $task->save();
    }
}