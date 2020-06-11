<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\ReportExport;
use App\Report;
use Illuminate\Support\Facades\Mail;

class ExportReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:export {criteria*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exports a CSV of all campaigns for the date range provided in reports table. Emails exported CSV to recipient.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $report_criteria = $this->argument('criteria');

        $report_type    = $report_criteria[0];

        if($report_type == 'scheduled'){
            // get the report record (get recipients)
            $report             = Report::find(1);
            $recipients         = $report->recipients;

            $date_sql_clause = '';
            $date_params = [];
        }elseif($report_type == 'one off'){
            $recipients     = $report_criteria[1];

            // date range
            $start_date_dt  = \DateTime::createFromFormat('d-m-Y', $report_criteria[2]);
            $start_date     = $start_date_dt->format('Y-m-d');

            $end_date_dt  = \DateTime::createFromFormat('d-m-Y',  $report_criteria[3]);
            $end_date     = $end_date_dt->format('Y-m-d');

            $date_sql_clause = ' where a.start_date BETWEEN :start_date AND :end_date ';
            $date_params = ['start_date' => $start_date, 'end_date' => $end_date];
        }

        $recipients_array = explode(';', $recipients);

        // run query
        $campaigns = \DB::select('SELECT
            a.campaign_id,
            g.name as Agencies,
            f.name as client_name,
            
            a.campaign_name ,
            a.campaign_type,
            a.start_date ,
            a.end_date,
            e.name as Statuses,
            sum(b.budget),
            products.name as Product,
            dsp_name,
            dds_code,
            sum(h.budget) as dsp_budget
            FROM booking.briefs as a left join campaigns_products as b
            on a.campaign_id = b.campaign_id left join booking_details as c
            on (b.campaign_id = c.campaign_id and b.product_id = c.product_id)
            left join (
            select i.campaign_id, j.status_id
            from (
            SELECT campaign_id,max(created_at) as created_at
            FROM booking.logs
            group by campaign_id
            order by campaign_id
            ) as i left join booking.logs as j
            on i.campaign_id = j.campaign_id
            and i.created_at = j.created_at
            ) as d
            on a.campaign_id = d.campaign_id
            left join statuses as e
            on d.status_id = e.id
            left join clients as f
            on a.client_id = f.id
            left join agencies as g on f.agency_id = g.id
            left join products on b.product_id = products.id
            left join dsp_budgets as h on h.booking_id = c.id
            left join dsps as j on j.id = h.dsp_id'.$date_sql_clause.'
            group by a.campaign_id,a.campaign_name, a.campaign_type, a.start_date , a.end_date,d.status_id,e.name, f.name , g.name , dsp_id, dsp_name, products.name,dds_code
            order by campaign_name',$date_params);

        $this->info('Exporting '.count($campaigns) .' campaign records.');
        
        // convert objects to array
        $campaigns_array = json_decode(json_encode($campaigns), true);

        $file_name = 'report-'.time().'.csv';

        $column_headings = ' ID , Agency, Client, Campaign Name, Campaign Type, Start Date, End Date, Status, Budget, Product, DSP, DDS Code, DSP Budget';

        // add column headers
        \Storage::disk('local')->append($file_name, $column_headings.PHP_EOL);

        // export to CSV
        foreach ($campaigns_array as $campaign_array) {
            # code...
            $csv_line = implode(",", $campaign_array);
            \Storage::disk('local')->append($file_name, $csv_line.PHP_EOL);
        }
        
        // email to recipient
        $email = new ReportExport($file_name);

        Mail::to($recipients_array)->send($email);
        
    }
}
