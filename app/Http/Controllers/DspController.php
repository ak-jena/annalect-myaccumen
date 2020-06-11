<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Http\Requests\ProcessDsp;
use DB;

use App\BookingDetail;
use App\BookingStatus;
use App\Campaign;
use App\Comment;
use App\Dsp;
use App\DspBudget;
use App\Log;
use App\Product;
use App\Status;
use App\User;

use App\Repositories\CommentRepository;

use App\Mail\BFSubmittedByAgency;
use App\Mail\BFUpdated;


class DspController extends Controller
{
    //
    public function processDateChange(Request $request)
    {
        // check user permission
        if(\Baselib::canCreateBooking() == false){
            return response()->json([
                'message' => 'Permission denied. Only agency users can add and edit the campaign start and end dates.'
            ]);
        }

        $user   = User::findorFail(\Auth::id());
        $input  = $request->all();

        $campaign_id    = $input['campaign_id'];
        $campaign       = Campaign::findOrFail($campaign_id);
        $brief          = $campaign->brief;

        // format/prepare dates
        $input['edit_campaign_dates']       = explode(' - ', $input['edit_campaign_dates']);
        $input['start_date']                = date('Y-m-d', strtotime($input['edit_campaign_dates'][0]));
        $input['end_date']                  = date('Y-m-d', strtotime($input['edit_campaign_dates'][1]));

        $date_changed = false;
        if($input['start_date'] !== $brief->start_date){
            $brief->start_date = $input['start_date'];
            $brief->save();
            $date_changed = true;
        }
        if($input['end_date'] !== $brief->end_date){
            $brief->end_date = $input['end_date'];
            $brief->save();
            $date_changed = true;
        }

        if($date_changed){
            // reason for date change
            if($input['date_change_reason'] !== ''){
                // insert reason into comment
                // add comment
                $comment = new Comment();
                $comment->title = 'Brief start/end dates changed';
                $comment->body  = '<p><strong>Brief start/end dates changed by '.$user->name.' because: </strong></p>
        <p>'.$input['date_change_reason'].'</p>';

                $system_comment_user = User::where('name', 'System Message')->first();
                $comment->brief()->associate($brief);
                $comment->author()->associate($system_comment_user);
                $comment->save();
            }
        }

        return response()->json([
            'message' => 'Date changed successfully',
        ]);
    }

    public function processDspBudget(ProcessDsp $request)
    {
        // check user permission
        if(\Baselib::canCreateBooking() == false){
            return response()->json([
                'message' => 'Permission denied. Only agency users can add and edit dsp budget data.'
            ]);
        }

        $input  = $request->all();

        $user   = User::findorFail(\Auth::id());

        // check if its a new booking or updating an existing booking
        $campaign_id    = $input['campaign_id'];
        $campaign       = Campaign::findOrFail($campaign_id);
        $brief          = $campaign->brief;
        $email          = null;

        // get the products for the campaign
        $campaign_products = $campaign->products;
        $updated_dsps = array();

        $message = '';

        /* 1) Process Booking Detail records */

        // Loop through each product,
        // Check if a booking detail record exists, if not create it
        foreach ($campaign_products as $product){
            $booking_detail = BookingDetail::where([
                ['campaign_id', $campaign->id],
                ['product_id', $product->id]
            ])->first();

            if($booking_detail == null){
                $booking_detail = new BookingDetail();

                // set default status to draft
                $booking_status = BookingStatus::find(BookingStatus::DRAFT);
                $booking_detail->bookingStatus()->associate($booking_status);

                $booking_detail->product()->associate($product);
                $booking_detail->campaign()->associate($campaign);
                $booking_detail->save();
            }
        }

        /* 2) Process DSP Budgets */

        // Loop through dsp form data
        foreach ($input['dsp_data'] as $product_name => $product_dsp_data){
            $updated_products[] = $product_name;
            $formatted_product_name = str_replace('_',' ',$product_name);
            $product = Product::where('name', $formatted_product_name)->first();
//            dd($product->name);

            foreach ($product_dsp_data as $dsp_id => $dsp_budget_value){

                $dsp = Dsp::find($dsp_id);

                $booking_detail = BookingDetail::where([
                    ['campaign_id', $campaign->id],
                    ['product_id', $product->id]
                ])->first();

                $dsp_budget = DspBudget::where([
                    ['booking_id', $booking_detail->id],
                    ['dsp_id', $dsp->id]
                ])->first();

                // check if it exists,
                if($dsp_budget == null){
                    if($dsp_budget_value > 0){
                        // create new dsp_budget record(s)
                        $dsp_budget             = new DspBudget();
                    }
                }else{
                    if($dsp_budget->budget !== $dsp_budget_value){
                        if($dsp_budget_value == ''){
                            $dsp_budget_value = 0;
                        }
                        $updated_dsps[] = 'Budget for '.$dsp_budget->dsp->dsp_name.' has changed from '.$dsp_budget->budget.' to '.$dsp_budget_value;
                    }
                }

                // only save it if budget value is >0
                if($dsp_budget_value > 0){
                    $dsp_budget->booking()->associate($booking_detail);
                    $dsp_budget->dsp()->associate($dsp);
                    $dsp_budget->user()->associate($user);
                    $dsp_budget->budget     = $dsp_budget_value;
                    $dsp_budget->save();
                    $message = 'DSP Budget updated successfully.';

                }else{
                    // delete dsps with 0 budget value
                    if($dsp_budget !== null){
                        $dsp_budget->delete();
                    }

                }
            }
        }

        // update misc info
        if($input['is_stack_client'] == 1){
            $brief->is_stack_client = 1;

            $google_audiences = null;
            if(array_key_exists('google_audiences', $input)){
                $google_audiences = $input['google_audiences'];
            }

            $google_audiences = json_encode($google_audiences);
            $brief->google_audiences = $google_audiences;

            if(array_key_exists('file', $input)) {
                $campaign_name = $campaign->brief->campaign_name;

                $uploaded_file = $request->file('file');

                $original_filename = $uploaded_file->getClientOriginalName();
                $unprocessed_filename = $campaign_name . '-' . $original_filename;
                $processed_filename = $this->clean($unprocessed_filename) . '.' . $uploaded_file->guessExtension();

                $file_path = $uploaded_file->storeAs('public/brief-files', $processed_filename, null, 'private');

                $brief->file_name    = $processed_filename;
                $brief->location     = $file_path;
            }

            $brief->save();
        }

        // get email recipients
        $activation_users   = $campaign->brief->getActivationUsers();
        $vod_users          = $campaign->brief->vodUsers;

        // check if booking was submitted
        $submission_type = trim($input['submission_type']);

        $submitted_booking = 0;
        if($submission_type === 'submit-booking'){
            $status = Status::where('name', 'Booking Form Submitted')->first();

            // write status to log table
            $log = new Log();
            $log->status()->associate($status);
            $log->campaign()->associate($campaign);

            $log->user()->associate($user);
            $log->save();

            // add comment
            $comment = new Comment();
            $comment->title = 'Booking Form Submitted';
            $comment->body  = '<p><strong>Booking Form Submitted by '.$user->name.'</strong></p>';

            $system_comment_user = User::where('name', 'System Message')->first();
            $comment->brief()->associate($brief);
            $comment->author()->associate($system_comment_user);
            $comment->save();

            // email
            $email = new BFSubmittedByAgency($campaign);

            $message = 'DSP Budget updated and booking submitted successfully.';
            $submitted_booking = 1;

        }else{
            if($campaign->status->id >= Status::BOOKING_FORM_SUBMITTED && $campaign->status->id <= Status::UPLOADED_CREATIVE_TAGS){
                // if the booking has been submitted then send a booking updated email
                $email = new BFUpdated($campaign);
            }
        }

        if($email != null){
            // send an email
            Mail::to($activation_users->merge($vod_users))->send($email);
        }

        // log comment if dsp was changed
        if(count($updated_dsps) > 0){
            // add comment
            $comment = new Comment();
            $comment->title = 'Booking Form DSP Budget(s) Changed';
            $comment->body  = '<p><strong>Booking Form DSP Budget(s) changed by '.$user->name.'. The following were changed:</strong></p>';

            foreach ($updated_dsps as $update_description){
                $comment->body .= '<p>'.$update_description.'</p>';
            }

            $system_comment_user = User::where('name', 'System Message')->first();
            $comment->brief()->associate($brief);
            $comment->author()->associate($system_comment_user);
            $comment->save();
        }

        return response()->json([
            'message'           => $message,
            'submitted_booking' => $submitted_booking,
            'updated_products'  => $updated_products
        ]);

    }

    public function deleteProduct(Request $request, CommentRepository $comment_repository){
        // check user permission
        if(\Baselib::canCreateBooking() == false){
            return response()->json([
                'message' => 'Permission denied. Only agency users can discard a product.'
            ]);
        }

        $parameters = $request->all();

        $campaign_id = $parameters['campaign_id'];
        $product_name = strtoupper($parameters['product_name']);

        $product_class = '\App\Product';

        $product_id = constant($product_class.'::'.$product_name);

        // find the product for the given campaign
        $campaign = Campaign::find($campaign_id);

        // ensure that the campaign has more than one product
        if($campaign->products()->count() <= 1){
            return response()->json([
                'message' => 'Cannot delete product from campaign. Only one product is currently in the campaign.'
            ]);
        }

        // delete the booking detail for the given campaign and product
        DB::table('booking_details')->where([
            ['campaign_id', '=', $campaign->id],
            ['product_id', '=', $product_id]
        ])->delete();

        // delete the product from the campaign
        $campaign->products()->detach($product_id);

        // insert comment
        // post brief updated comment
        $logged_in_user         = \Baselib::getUser(\Auth::user()->id);
        $system_comment_user    = User::where('name', 'System Message')->first();
        $comment_repository->create(['title' => 'Product removed from brief', 'body' => '<p>'.ucfirst(strtolower($product_name)).' product was removed by <strong>'.$logged_in_user->name.'.</strong></p>', 'author_id' => $system_comment_user->id, 'brief_id' => $campaign->brief->id]);

        return response()->json([
            'message'   => 'Deleted product successfully'
        ]);

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
