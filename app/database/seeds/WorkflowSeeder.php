<?php
class WorkflowSeeder extends Seeder {

    public function run()
    {
        DB::table('workflow')->delete();
        
        $flow = new Workflow;
        $flow->id = '1';
        $flow->workFlowName = 'Small Value Procurement (Below P50,000)';
        $flow->totalDays = '45';
        $flow->save();

        $flow = new Workflow;
        $flow->id = '2';
        $flow->workFlowName = 'Small Value Procurement (Above P50,000 Below P500,000)';
        $flow->totalDays = '45';
        $flow->save();

        $flow = new Workflow;
        $flow->id = '3';
        $flow->workFlowName = 'Bidding (Above P500,000)';
        $flow->totalDays = '45';
        $flow->save();
        
        $flow = new Workflow;
        $flow->id = '4';
        $flow->workFlowName = 'Pakyaw';
        $flow->totalDays = '45';
        $flow->save();

        $flow = new Workflow;
        $flow->id = '5';
        $flow->workFlowName = 'Contract Bidding';
        $flow->totalDays = '45';
        $flow->save();
    }
}