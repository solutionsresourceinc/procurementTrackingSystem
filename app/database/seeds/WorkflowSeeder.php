<?php
class WorkflowSeeder extends Seeder {

    public function run()
    {
        DB::table('workflow')->delete();
        
        $flow = new Workflow;
        $flow->id = '1';
        $flow->workFlowName = 'Below P50,000';
        $flow->totalDays = '0';
        $flow->save();

        $flow = new Workflow;
        $flow->id = '2';
        $flow->workFlowName = 'Above P50,000 Below P500,000';
        $flow->totalDays = '0';
        $flow->save();

        $flow = new Workflow;
        $flow->id = '3';
        $flow->workFlowName = 'Above P500,000';
        $flow->totalDays = '0';
        $flow->save();
    }
}