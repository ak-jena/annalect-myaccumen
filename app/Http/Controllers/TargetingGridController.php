<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TargetingGridController extends Controller
{
    /*
     * This controller contains experimental/proof of concept functionality
     * for the future targeting grid. Its buggy and probably full of errors. Can be deleted if not required.
     */


    //
    public function poc()
    {
        return view('targeting-grid.poc');
    }

    public function retrieveTargetingGrid(Request $request)
    {

        $data = [
            [
                'id'=>2,
                'dsp'=>'Oli Bob',
                'targeting_type'=>'Audience',
                'targeting_tactic'=>'red',
                'goal'=>'',
                'kpi_value'=>'4%',
                'role'=>'blah blah',
                'screens'=> 'desktop, mobile, tablet',
                'format'=> 'expandable something',
                'inventory'=> 'truview',
                'est_budget_percentage'=> '43%',
                'budget'=> '0.00',
                'est_avg_cpm_cpv'=> '0.00',
                'views'=> '',
                'est_impressions'=> '4343624',
                'format_details'=> 'Random url, dont know what else, some extra text, yo yo yo',
                'data_fee'=> '£232',
                'tech_fee'=> '£22442',
                'start'=> '',
                'end'=> '',
                'actions' => '<button type="button" data-tg-id="1" class="delete-tg-row btn btn-default btn-xs"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> </button>  <button type="button" data-tg-id="1" class="copy-tg-row btn btn-default btn-xs"><span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span> </button>'
            ],
            [
                'id'=>1,
                'dsp'=>'dsvs',
                'targeting_type'=>'Audience',
                'targeting_tactic'=>'red',
                'goal'=>'',
                'kpi_value'=>'4%',
                'role'=>'blah blah',
                'screens'=> 'desktop, mobile, tablet',
                'format'=> 'expandable something',
                'inventory'=> 'truview',
                'est_budget_percentage'=> '43%',
                'budget'=> '0.00',
                'est_avg_cpm_cpv'=> '0.00',
                'views'=> '',
                'est_impressions'=> '4343624',
                'format_details'=> 'Random url, dont know what else, some extra text, yo yo yo',
                'data_fee'=> '£232',
                'tech_fee'=> '£22442',
                'start'=> '',
                'end'=> '',
                'actions' => '<button type="button" data-tg-id="2" class="delete-tg-row btn btn-default btn-xs"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> </button> <button type="button" data-tg-id="2" class="copy-tg-row btn btn-default btn-xs"><span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span> </button>'
            ],
            [
                'id'=> 3,
                'dsp'=>'dsdsvs',
                'targeting_type'=>'Audience',
                'targeting_tactic'=>'red',
                'goal'=>'',
                'kpi_value'=>'4%',
                'role'=>'blah blah',
                'screens'=> 'desktop, mobile, tablet',
                'format'=> 'expandable something',
                'inventory'=> 'truview',
                'est_budget_percentage'=> '43%',
                'budget'=> '0.00',
                'est_avg_cpm_cpv'=> '0.00',
                'views'=> '',
                'est_impressions'=> '4343624',
                'format_details'=> 'Random url, dont know what else, some extra text, yo yo yo',
                'data_fee'=> '£232',
                'tech_fee'=> '£22442',
                'start'=> '',
                'end'=> '',
                'actions' => '<button type="button" data-tg-id="3" class="delete-tg-row btn btn-default btn-xs"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> </button> <button type="button" data-tg-id="3" class="copy-tg-row btn btn-default btn-xs"><span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span> </button>'
            ]

        ];

        return json_encode($data);
        
    }

    public function grid($campaign_id = null)
    {
        // check if campaign exists
        $campaign = \App\Campaign::where('id', $campaign_id)->first();
        if($campaign == null){
            return redirect()->route('dashboard');
        }

        $brief          = $campaign->brief;
        $product_names  = $campaign->products()->pluck('name')->all();

        return view('targeting-grid.grid', ['brief' => $brief, 'product_names' => $product_names]);
    }

    /**
     * @param int $campaign_id
     *
     * @return json $data
     */
    public function retrieveGrid($campaign_id = null)
    {
        // check if campaign exists
        $campaign = \App\Campaign::where('id', $campaign_id)->first();
        if($campaign == null){
            return redirect()->route('dashboard');
        }

        $data = [];

        // get product names in campaign
        $data = $campaign->products()->pluck('name')->all();

//        var_dump($data);die;


        // get targeting grids in campaign
        $targeting_grids = $campaign->targetingGrids;

        foreach ($targeting_grids as $targeting_grid){
            $product                = $targeting_grid->product;
            $data[$product->name]   = ['grid_data' => $targeting_grid->grid_data];

        }


        return json_encode($data);
    }

    public function saveGrid(Request $request)
    {

    }
}
