<?php

namespace App\Http\Controllers;

use App\Product;
use App\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

use App\Brief;

class ExportController extends Controller
{
    public function exportBriefData($brief_id){

        // retrieve brief
        $brief      = Brief::findOrFail($brief_id);

        // check user is allowed to access this brief form
        if(\Baselib::isAgencyUser() || \Baselib::isVodUser() || \Baselib::isActivationUser() || \Baselib::isActivationLineManager()){
            $logged_in_user = \Baselib::getUser(\Auth::user()->id);

            $users_clients = $logged_in_user->permittedClients;

            $campaign_client = $brief->client;

            if($users_clients->contains('id', $campaign_client->id) == false){
                // redirect to dashboard
                return redirect()->route('dashboard');
            }
        }

        // check brief has been submitted?
        if($brief->campaign->status->id < Status::BRIEF_SUBMITTED){
            // redirect to dashboard
            return redirect()->route('dashboard');
        }

        // write to excel file
        // tidy up campaign name to use as file name
        $safe_campaign_name = $this->clean($brief->campaign_name);

        Excel::create($safe_campaign_name.'-brief-data', function($excel) use ($brief){
            $excel->setTitle('Brief Data');

            // create key campaign information worksheet
            $excel->sheet('Brief Data', function($sheet) use ($brief){
                $row = 1;

                // headings
                $sheet->row($row, ['', 'Key Campaign Information 1']);

                $sheet->cells('B1', function($cells) {

                    $cells->setBackground('#33cc33');
                });

                $row++;
                $row++;

                // names of products
                $product_names = '';

                foreach ($brief->campaign->products as $product){
                    $product_names .= $product->name.', ';
                }

                $sheet->row($row, ['', 'Products', $product_names]);
                $row++;
                $row++;

                $sheet->row($row, ['', 'Advertiser', $brief->client->name]);
                $row++;
                $row++;

                $sheet->row($row, ['', 'Agency Contact', $brief->user->name]);
                $row++;
                $row++;

                $sheet->row($row, ['', 'Campaign Name', $brief->campaign_name]);
                $row++;
                $row++;

                $sheet->row($row, ['', 'Campaign Type', $brief->campaign_type]);
                $row++;
                $row++;

                $sheet->row($row, ['', 'Dates', $brief->start_date.' - '.$brief->end_date]);
                $row++;
                $row++;

                $sheet->row($row, ['', 'Flighting Considerations', $brief->flighting_considerations]);
                $row++;
                $row++;

                // styling for column headings
                $sheet->cells('B1:B200', function($cells) {

                    $cells->setFontWeight('bold');
                });
                $row++;

                // headings
                $sheet->row($row, ['', 'Key Campaign Information 2']);

                // styling for column headings
                $sheet->cells('B'.$row, function($cells) {

                    $cells->setBackground('#33cc33');
                });
                $row++;
                $row++;

                // product budget headings
                $product_budget_headings = [];
                $product_budget_values = [];
                foreach ($brief->campaign->products as $product){
                    $product_budget_headings[] = $product->name.' Budget';
                    $product_budget_values[] = $product->pivot->budget;
                }

                foreach ($product_budget_headings as $key => $product_name){
                    $sheet->row($row, ['', $product_name, $product_budget_values[$key]]);
                    $row++;
                }

                $key_campaign_info_headings = ['Total Budget', 'Background to Brief', 'Target Audience profile'];
                $key_campaign_info_values   = ['£'.$brief->latest_total_budget, $brief->background, $brief->target_audience_profile];

                foreach ($key_campaign_info_headings as $key => $heading){
                    $sheet->row($row, ['', $heading, $key_campaign_info_values[$key]]);
                    $row++;
                    $row++;
                }

                $row++;

                // product data
                $products = $brief->campaign->products;

                $products = $products->sortBy('id');

                if($products->contains('id', Product::DISPLAY) || $products->contains('id', Product::MOBILE) || $products->contains('id', Product::RICH_MEDIA)){

                    $drm_product = $products->first();

                    $sheet->row($row, ['', $brief->drm_products_names]);
                    $sheet->cells('B'.$row, function($cells) {

                        $cells->setBackground('#33cc33');
                    });

                    $row++;
                    $row++;

                    $drm_headings = [
                        'Campaign Objective',
                        'Primary Campaign Metric',
                        'Metric Goal Value',
                        'Activity 1',
                        'Activity Metric',
                        'Metric Goal Value',
                        'Activity 2',
                        'Activity Metric',
                        'Metric Goal Value',
                        'Activity 3',
                        'Activity Metric',
                        'Metric Goal Value',
                        'Geo Targeting',
                        'Geo Targeting Details',
                        'Inventory/Screen Type(s)',
                        'Any specific activity we should consider in a response',
                        'Environments/Publisher partners to consider in response'
                    ];

                    // parse json fields
                    $inventory_screentypes = '';
                    if($drm_product->pivot->inventory_screentypes !== null){
                        $inventory_screentypes_array = json_decode($drm_product->pivot->inventory_screentypes);
                        foreach ($inventory_screentypes_array as $screentype => $selected){
                            if($selected == 'Y'){
                                $inventory_screentypes .= $screentype.', ';
                            }
                        }
                    }

                    $drm_values = [
                        $drm_product->pivot->campaign_objective,
                        $drm_product->pivot->primary_metric,
                        $drm_product->pivot->primary_metric_goal_value,
                        $drm_product->pivot->display_media_mobile_activity_1,
                        $drm_product->pivot->display_media_mobile_metric_1,
                        $drm_product->pivot->display_media_mobile_value_1,
                        $drm_product->pivot->display_media_mobile_activity_2,
                        $drm_product->pivot->display_media_mobile_metric_2,
                        $drm_product->pivot->display_media_mobile_value_2,
                        $drm_product->pivot->display_media_mobile_activity_3,
                        $drm_product->pivot->display_media_mobile_metric_3,
                        $drm_product->pivot->display_media_mobile_value_3,
                        $drm_product->pivot->geo_targeting,
                        $drm_product->pivot->geo_targeting_details,
                        $inventory_screentypes,
                        $drm_product->pivot->specific_activity_response,
                        $drm_product->pivot->contextual_env_pp_response,
                    ];

                    foreach ($drm_headings as $key => $heading){
                        $sheet->row($row, ['', $heading, $drm_values[$key]]);
                        $row++;
                        $row++;
                    }

                    $row++;
                }

                if($products->contains('id', Product::AUDIO)){
                    $sheet->row($row, ['', 'Audio']);
                    $sheet->cells('B'.$row, function($cells) {

                        $cells->setBackground('#33cc33');
                    });
                    $row++;
                    $row++;

                    $audio_headings = [
                        'Campaign Objective',
                        'Primary Campaign Metric',
                        'Metric Goal Value',
                        'Geo Targeting',
                        'Geo Targeting Details',
                        'Includes Companion Banner?',
                        'Any specific activity we should consider in a response',
                        'Environments/Publisher partners to consider in response',
                        'Copy Length'
                    ];

                    $audio_product = $products->where('id', Product::AUDIO)->first();

                    $has_companion_banner = 'No';
                    if($audio_product->pivot->has_companion_banner == 1){
                        $has_companion_banner = 'Yes';
                    }

                    // parse json fields
                    $creative_lengths = '';
                    if($audio_product->pivot->creative_lengths !== null){
                        $creative_lengths_array = json_decode($audio_product->pivot->creative_lengths);
                        $creative_lengths = implode(", ", $creative_lengths_array);
                    }

                    $audio_values = [
                        $audio_product->pivot->campaign_objective,
                        $audio_product->pivot->primary_metric,
                        $audio_product->pivot->primary_metric_goal_value,
                        $audio_product->pivot->geo_targeting,
                        $audio_product->pivot->geo_targeting_details,
                        $has_companion_banner,
                        $audio_product->pivot->specific_activity_response,
                        $audio_product->pivot->contextual_env_pp_response,
                        $creative_lengths
                    ];

                    foreach ($audio_headings as $key => $heading){
                        $sheet->row($row, ['', $heading, $audio_values[$key]]);
                        $row++;
                        $row++;
                    }

                    $row++;
                }
                if($products->contains('id', Product::VOD)){
                    $sheet->row($row, ['', 'VOD']);
                    $sheet->cells('B'.$row, function($cells) {
                        $cells->setBackground('#33cc33');
                    });
                    $row++;
                    $row++;

                    $vod_headings = [
                        'Campaign Objective',
                        'Primary Campaign Metric',
                        'Metric Value',
                        'Secondary Campaign Metric',
                        'Secondary Metric Value',
                        'Geo Targeting',
                        'Demo Target',
                        'Inventory/Screen Types',
                        'Copy Length',
                        'Video Creative Type',
                        'Interactive Creative Provider',
                    ];

                    $vod_product    = $products->where('id', Product::VOD)->first();

                    // parse json fields
                    $inventory_screentypes = '';
                    if($vod_product->pivot->inventory_screentypes !== null){
                        $inventory_screentypes_array = json_decode($vod_product->pivot->inventory_screentypes);
                        foreach ($inventory_screentypes_array as $screentype => $selected){
                            if($selected == 'Y'){
                                $inventory_screentypes .= $screentype.', ';
                            }
                        }
                    }
                    $creative_lengths = '';
                    if($vod_product->pivot->creative_lengths !== null){
                        $creative_lengths_array = json_decode($vod_product->pivot->creative_lengths);
                        $creative_lengths = implode(", ", $creative_lengths_array);
                    }
                    $creative_types = '';
                    if($vod_product->pivot->creative_lengths !== null){
                        $creative_types_array = json_decode($vod_product->pivot->video_creative_type);
                        $creative_types = implode(", ", $creative_types_array);
                    }

                    $vod_values = [
                        $vod_product->pivot->campaign_objective,
                        $vod_product->pivot->primary_metric,
                        $vod_product->pivot->primary_metric_goal_value,
                        $vod_product->pivot->metric_2,
                        $vod_product->pivot->metric_2_goal_value,
                        $vod_product->pivot->geo_targeting,
                        $vod_product->pivot->video_demo_target,
                        $inventory_screentypes,
                        $creative_lengths,
                        $creative_types,
                        $vod_product->pivot->interactive_creative_provider
                    ];

                    foreach ($vod_headings as $key => $heading){
                        $sheet->row($row, ['', $heading, $vod_values[$key]]);
                        $row++;
                        $row++;
                    }

                    $row++;
                }

                $sheet->row($row, ['', 'Additional Info']);
                $sheet->cells('B'.$row, function($cells) {
                    $cells->setBackground('#33cc33');
                });
                $row++;
                $row++;

                $sheet->row($row, ['', 'Additional Notes', $brief->additional_info]);
                $row++;

                $brief_deadline_timestamp = strtotime($brief->brief_response_deadline);
                $sheet->row($row, ['', 'Deadline for Brief Response', date('d/m/Y', $brief_deadline_timestamp)]);
                $row++;
                $row++;

            });


        })->download('xls');

        // return excel file
    }

    //
    public function exportBookingData($brief_id){

        // retrieve brief
        $brief      = Brief::findOrFail($brief_id);
        $campaign   = $brief->campaign;

        if(\Baselib::isAgencyUser() || \Baselib::isVodUser() || \Baselib::isActivationUser() || \Baselib::isActivationLineManager()){
            $logged_in_user     = \Baselib::getUser(\Auth::user()->id);
            $users_clients      = $logged_in_user->permittedClients;
            $campaign_client    = $brief->client;

            // check if user is allowed to work with this client
            if($users_clients->contains('id', $campaign_client->id) == false){
                // redirect to dashboard
                return redirect()->route('dashboard');
            }
        }

        // check status
        if($campaign->status->id < Status::BOOKING_FORM_SUBMITTED){
            // redirect to dashboard
            return redirect()->route('dashboard');
        }


        // write to excel file
        // tidy up campaign name to use as file name
        $safe_campaign_name = $this->clean($brief->campaign_name);

        // write to excel file
        Excel::create($safe_campaign_name.'-booking-data', function($excel) use ($brief){
            $excel->setTitle('Booking Data');

            // create key campaign information worksheet
            $excel->sheet('Booking Data', function($sheet) use ($brief){
                $row = 1;

                $sheet->cells('B1:B200', function($cells) {

                    $cells->setFontWeight('bold');
                });

                // booking data
                $bookings = $brief->campaign->bookingDetails;

//                dump($bookings);

                $bookings = $bookings->sortBy('product_id');

                // write dsp budgets for each product

                foreach ($bookings as $booking){
                    // write product name as heading
                    $sheet->row($row, ['', $booking->product->name]);
                    $sheet->cells('B'.$row, function($cells) {

                        $cells->setBackground('#33cc33');
                    });

                    $sheet->getStyle('B'.$row)->getAlignment()->setWrapText(true);

                    $row++;

                    // write dsp budgets
                    foreach ($booking->dspBudgets as $dsp_budget){
                        $sheet->row($row, ['', $dsp_budget->dsp->dsp_name, number_format($dsp_budget->budget, 2,'.', '')]);
                        $row++;
                    }
                    $row++;

                }

                // export booking form details (rich media, mobile and display
                // use the same booking form) so should only be written once
                $processed_rmd_product = false;
                foreach ($bookings as $booking){

                    // booking form for rich media, mobile and display
                    if(in_array($booking->product->id, [Product::RICH_MEDIA, Product::MOBILE, Product::DISPLAY])){
                        if($processed_rmd_product == false){
                            // product heading
                            $sheet->row($row, ['', $brief->drm_products_names]);
                            $sheet->cells('B'.$row, function($cells) {

                                $cells->setBackground('#33cc33');
                            });

                            $row++;
                            $row++;

                            $rmd_headings = [
                                '',
                                'Pricing Model',
                                'Is Budgets Silos?',
                            ];

                            $has_budget_silo = 'No';
                            if($booking->has_budget_silo == 1){
                                $has_budget_silo = 'Yes';
                            }

                            $rmd_values = [
                                '',
                                $booking->pricing_model,
                                $has_budget_silo
                            ];

                            foreach ($rmd_headings as $key => $heading){
                                $sheet->row($row, ['', $heading, $rmd_values[$key]]);
                                $row++;
                            }
                            $row++;

                            $rmd_headings   = [];
                            $rmd_values     = [];

                            // budget silos
                            $sheet->row($row, ['', 'Budget Silos']);
                            $row++;

                            $sheet->row($row, ['', 'Silo Description', 'Budget', 'Planning CPM']);
                            $sheet->cells('C'.$row.':D'.$row, function($cells) {

                                $cells->setFontWeight('bold');
                            });
                            $row++;

                            $budget_silos = json_decode($booking->budget_silos);
                            foreach ($budget_silos as $budget_silo){
                                $sheet->row($row, ['', $budget_silo->silo_description, $budget_silo->silo_budget, $budget_silo->planning_cpm]);
                                $row++;
                            }

                            // total
                            $sheet->row($row, ['', 'Total', $booking->budget_silos_total]);
                            $row++;

                            $requested_tracking_pixels = 'No';
                            if($booking->requested_tracking_pixels == 0){
                                $requested_tracking_pixels = 'Yes';
                            }

                            // rmd booking headings
                            $rmd_headings   = [
                                '',
                                'Targeting Requirements',
                                'Have you requested necessary onsite tracking pixels from OMG Programmatic?',
                                'If yes, please give names/IDs of the pixels',
                                'What are the events?',
                            ];

                            $rmd_values     = [
                                '',
                                $booking->targeting_requirements,
                                $requested_tracking_pixels,
                                $booking->tracking_pixel_details,
                                $booking->tracking_pixel_events,
                            ];

                            foreach ($rmd_headings as $key => $heading){
                                $sheet->row($row, ['', $heading, $rmd_values[$key]]);
                                $sheet->getStyle('B'.$row)->getAlignment()->setWrapText(true);
                                $row++;
                            }
                            $row++;

                            $tracking_tags = $booking->tracking_tag;


                            if($tracking_tags !== null){

                                $tracking_tags = json_decode($tracking_tags);

                                $sheet->row($row, ['', 'Tracking Tag']);
                                $row++;

                                $sheet->row($row, ['', 'DSP Pixel Name', 'Metric Tracking']);
                                $sheet->cells('C'.$row, function($cells) {

                                    $cells->setFontWeight('bold');
                                });
                                $row++;

                                foreach($tracking_tags as $tracking_tag){
                                    $sheet->row($row, ['', $tracking_tag->dsp_pixel_name, $tracking_tag->metric_tracking]);
                                    $row++;
                                }
                                $row++;
                            }

                            $is_rich_media = 'No';
                            if($booking->is_rich_media == 1){
                                $is_rich_media = 'Yes';
                            }

                            $sheet->row($row, ['', 'Are you running Rich media?', $is_rich_media]);
                            $row++;

                            if($booking->rm_creative_format !== null){
                                $rm_creative_formats = json_decode($booking->rm_creative_format);

                                $sheet->row($row, ['', 'What rich media creative formats will be supplied?']);
                                $row++;
                                $row++;

                                foreach ($rm_creative_formats as $rm_format){
                                    $sheet->row($row, ['', '', $rm_format]);
                                    $row++;
                                }
                                $row++;
                            }

                            $sheet->row($row, ['', 'Other', $booking->rm_creative_format_other]);
                            $row++;

                            $is_1x1_supplied = 'No';
                            if($booking->is_1x1_supplied == 1){
                                $is_1x1_supplied = 'Yes';
                            }
                            $sheet->row($row, ['', 'If yes, have you supplied 1x1 impression & click trackers to the 3rd Party?', $is_1x1_supplied]);
                            $row++;

                            $sheet->row($row, ['', 'Any additional notes for Rich Media creative', $booking->rm_creative_notes]);
                            $row++;

                            if($booking->supplied_creative_formats !== null){
                                $supplied_creative_formats = json_decode($booking->supplied_creative_formats);

                                $sheet->row($row, ['', 'What creative formats will be supplied?']);
                                $row++;

                                $sheet->row($row, ['', 'Format Type', 'Dimensions']);
                                $sheet->cells('C'.$row.':D'.$row, function($cells) {

                                    $cells->setFontWeight('bold');
                                });

                                $row++;

                                foreach ($supplied_creative_formats as $creative_format){
                                    $sheet->row($row, ['', $creative_format->format_type, $creative_format->dimension]);
                                    $row++;
                                }
                                $row++;
                            }

                            $rmd_headings   = [];
                            $rmd_values     = [];

                            $specific_activity_tags = 'No';
                            if($booking->specific_activity_tags == 1){
                                $specific_activity_tags = 'Yes';
                            }
                            $rmd_headings[] = 'Please select Yes and indicate if tags should be targeted to specific activity rather than all tags in rotation';
                            $rmd_values[]   = $specific_activity_tags;

                            $rmd_headings[] = 'Conversion ID (Adserver)';
                            $rmd_values[]   = $booking->data_collection_code;

                            $reporting = 'No';
                            if($booking->is_reporting == 1){
                                $reporting = 'Yes';
                            }
                            $rmd_headings[] = 'Reporting';
                            $rmd_values[]   = $reporting;

                            $rmd_headings[] = 'Reporting (Weekly updates - specific requirements to be discussed with the OMG Programmatic team.)';
                            $rmd_values[]   = $booking->weekly_updates;

                            $rmd_headings[] = 'Metrics Required';
                            $rmd_values[]   = $booking->metrics_required;

                            $rmd_headings[] = 'Adserver (please specify which Adserver is being used)';
                            $rmd_values[]   = $booking->adserver;

                            $rmd_headings[] = 'Please confirm the metric/conversion event to be used on the Adserver';
                            $rmd_values[]   = $booking->adserver_metric;

                            $rmd_headings[] = 'Site List';
                            $rmd_values[]   = $booking->site_list;

                            $rmd_headings[] = 'Audience segment examples';
                            $rmd_values[]   = $booking->audience_segment_examples;

                            $rmd_headings[] = 'Other information';
                            $rmd_values[]   = $booking->other_info;

                            $rmd_headings[] = 'OMG Programmatic Assessment (to be completed by OMG Programmatic for any campaign where specifics of the campaign or activity to be used by not deliver or perform as expected)';
                            $rmd_values[]   = $booking->omg_programmatic_assessment;

                            foreach ($rmd_headings as $key => $heading){
                                $sheet->row($row, ['', $heading, $rmd_values[$key]]);
                                $row++;
                            }

                            $processed_rmd_product = true;
                        }

                    }

                    // export booking form for audio
                    if(in_array($booking->product->id, [Product::AUDIO])){
                        $row++;
                        $row++;

//                        // product heading
                        $sheet->row($row, ['', $booking->product->name]);
                        $sheet->cells('B'.$row, function($cells) {
                            $cells->setBackground('#33cc33');
                        });

                        $sheet->getStyle('B'.$row)->getAlignment()->setWrapText(true);

                        $row++;
                        $row++;

                        $audio_headings = [
                            '',
                            'Pricing Model',
                            'Is Budgets Silos?',
                        ];

                        $has_budget_silo = 'No';
                        if($booking->has_budget_silos == 1){
                            $has_budget_silo = 'Yes';
                        }

                        $audio_values = [
                            '',
                            $booking->pricing_model,
                            $has_budget_silo
                        ];

                        foreach ($audio_headings as $key => $heading){
                            $sheet->row($row, ['', $heading, $audio_values[$key]]);
                            $row++;
                        }
                        $row++;

                        $audio_headings   = [];
                        $audio_values     = [];

                        // budget silos
                        $sheet->row($row, ['', 'Budget Silos']);
                        $row++;

                        $sheet->row($row, ['', 'Silo Description', 'Budget']);
                        $sheet->cells('C'.$row, function($cells) {

                            $cells->setFontWeight('bold');
                        });
                        $row++;

                        $budget_silos = json_decode($booking->budget_silos);
                        foreach ($budget_silos as $budget_silo){
                            $sheet->row($row, ['', $budget_silo->silo_description, $budget_silo->silo_budget]);
                            $row++;
                        }

                        // total
                        $sheet->row($row, ['', 'Total', $booking->budget_silos_total]);
                        $row++;
                        $row++;

                        $sheet->row($row, ['', 'Targeting Requirements', $booking->targeting_requirements]);
                        $row++;

                        if($booking->supplied_creative_formats !== null){
                            $supplied_creative_formats = json_decode($booking->supplied_creative_formats);

                            $sheet->row($row, ['', 'What creative formats will be supplied?']);
                            $row++;

                            $sheet->row($row, ['', 'Format Type', 'Dimensions']);
                            $row++;

                            $format_types = [
                                0 => '15s',
                                1 => '20s',
                                2 => '30s',
                                3 => '40s',
                                4 => '60s',
                                5 => 'Companion banner',
                            ];
                            foreach ($supplied_creative_formats as $creative_format){
                                $sheet->row($row, ['', $format_types[$creative_format->format_type], $creative_format->dimension]);
                                $row++;
                            }
                            $row++;
                        }
                        $specific_activity_tags = 'No';
                        if($booking->specific_activity_tags == 1){
                            $specific_activity_tags = 'Yes';
                        }
                        $audio_headings[] = 'Please select Yes and indicate if tags should be targeted to specific activity rather than all tags in rotation';
                        $audio_values[]   = $specific_activity_tags;

                        $audio_headings[]   = '1x1 Ad Server Trackers';
                        $audio_values[]     = $booking->{'1x1_adserver_trackers'};

                        $audio_headings[] = 'Conversion ID (Adserver)';
                        $audio_values[]   = $booking->data_collection_code;

                        $audio_headings[] = 'Reporting';
                        $audio_values[]   = $booking->reporting_description;

                        $audio_headings[] = 'Other information';
                        $audio_values[]   = $booking->other_info;

                        $audio_headings[] = 'OMG Programmatic Assessment (to be completed by OMG Programmatic for any campaign where specifics of the campaign or activity to be used by not deliver or perform as expected)';
                        $audio_values[]   = $booking->omg_programmatic_assessment;

                        foreach ($audio_headings as $key => $heading){
                            $sheet->row($row, ['', $heading, $audio_values[$key]]);
                            $row++;
                        }

                    }

                    // export booking form for VOD
                    if(in_array($booking->product->id, [Product::VOD])){
                        $row++;
                        $row++;

//                        // product heading
                        $sheet->row($row, ['', $booking->product->name]);
                        $sheet->cells('B'.$row, function($cells) {
                            $cells->setBackground('#33cc33');
                        });

                        $sheet->getStyle('B'.$row)->getAlignment()->setWrapText(true);

                        $row++;
                        $row++;

                        $requested_tracking_pixels = 'No';
                        if($booking->requested_tracking_pixels == 1){
                            $requested_tracking_pixels = 'Yes';
                        }
                        $sheet->row($row, ['', 'Have you requested necessary onsite tracking pixels from OMG Programmatic?', $requested_tracking_pixels]);
                        $row++;


                        $implemented_pixels = 'No';
                        if($booking->implemented_pixels == 1){
                            $implemented_pixels = 'Yes';
                        }
                        $sheet->row($row, ['', 'Have the OMG Programmatic pixels been implemented?', $implemented_pixels]);
                        $row++;

                        $sheet->row($row, ['', 'Data collection code', $booking->data_collection_code]);
                        $row++;


                        $tracking_tags = $booking->tracking_tag_dsp;

                        if($tracking_tags !== null){
                            $row++;

                            $tracking_tags = json_decode($tracking_tags);

                            $sheet->row($row, ['', 'Tracking Tag']);
                            $row++;

                            $sheet->row($row, ['', 'DSP Pixel Name', 'Dimensions']);
                            $sheet->cells('C'.$row, function($cells) {

                                $cells->setFontWeight('bold');
                            });
                            $row++;

                            foreach($tracking_tags as $tracking_tag){
                                $sheet->row($row, ['', $tracking_tag->dsp_pixel_name, $tracking_tag->metric_tracking]);
                                $row++;
                            }
                            $row++;
                        }

                        $vod_headings[] = 'Other information';
                        $vod_values[]   = $booking->other_info;

                        $vod_headings[] = 'OMG Programmatic Assessment (to be completed by OMG Programmatic for any campaign where specifics of the campaign or activity to be used by not deliver or perform as expected)';
                        $vod_values[]   = $booking->omg_programmatic_assessment;

                        foreach ($vod_headings as $key => $heading){
                            $sheet->row($row, ['', $heading, $vod_values[$key]]);
                            $row++;
                        }

                    }

                    $sheet->setWidth('B', 70);
                    $sheet->setWidth('C', 35);
                    $sheet->setWidth('D', 35);
                    $sheet->getStyle('B'.$row)->getAlignment()->setWrapText(true);
                    $sheet->getStyle('C'.$row)->getAlignment()->setWrapText(true);
                }

            });


        })->download('xls');

    }

    function clean($string) {

        // remove extension
        $string = strtolower(pathinfo($string, PATHINFO_FILENAME)); // remove extension and convert to lower case
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = str_replace('_', '-', $string); // Replaces all underscores with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }
}
