<?php
/**
 * Created by PhpStorm.
 * User: saeed.bhuta
 * Date: 28/11/2016
 * Time: 11:09
 */

namespace App\Http\Controllers;

use App\BookingDetail;
use App\BookingStatus;
use App\Http\Requests\ProcessBooking;
use App\Mail\BFUpdated;
use App\Product;
use App\Campaign;

use App\Http\Controllers\Controller;

use App\Status;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use DB;
use Alert;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    /**
     * Show booking form page
     *
     * @param  int  $campaign_id
     * @param  string  $product_ids
     * @return Response
     */
    public function showBookingForm($campaign_id = null, $product_ids = null)
    {
        $campaign       = Campaign::findOrFail($campaign_id);
        $product        = Product::findorFail($product_ids);

        // redirect back to dashboard page
        if(($campaign == null) || ($product == null)){
            return redirect()->route('dashboard');
        }

        $booking_detail = DB::table('booking_details')->where([
            ['campaign_id', $campaign->id],
            ['product_id', $product->id]
        ])->first();

        if($booking_detail == null){
            return redirect()->route('workflow', ['campaign_id' => $campaign->id]);
        }

        // parse json fields correctly
        if(in_array($product->id, array(Product::RICH_MEDIA, Product::DISPLAY, Product::MOBILE, Product::AUDIO))){
            $budget_silos = json_decode($booking_detail->budget_silos);
            $booking_detail->budget_silos = $budget_silos;

            $supplied_creative_formats = json_decode($booking_detail->supplied_creative_formats);
            $booking_detail->supplied_creative_formats = $supplied_creative_formats;

            // rich media, mobile, display specific fields
            if(in_array($product->id, array(Product::RICH_MEDIA, Product::DISPLAY, Product::MOBILE))){
                $tracking_tag = json_decode($booking_detail->tracking_tag);
                $booking_detail->tracking_tag = $tracking_tag;
            }
        }elseif($product->id == Product::VOD){
            $tracking_tag_dsp = json_decode($booking_detail->tracking_tag_dsp);

            $booking_detail->tracking_tag_dsp = $tracking_tag_dsp;
        }

        return view('booking.form', ['campaign' => $campaign, 'product' => $product, 'booking_detail' => $booking_detail]);
    }

    /**
     * Process booking form
     *
     * @param  \App\Http\Requests\ProcessBooking  $request
     * @param  int  $campaign_id
     * @param  string  $product_ids
     * @return \Illuminate\Http\Response
     */
    public function processBookingForm(ProcessBooking $request, $campaign_id = null, $product_ids = null)
    {
        $input = $request->all();

        $campaign       = Campaign::findOrFail($campaign_id);

        // parse the product id(s)
        if(strpos($product_ids, ',') !== false){
            $product_ids = explode(',', $product_ids);
        }else{
            $product_ids = array($product_ids);
        }

        // retrieve collection of products
        if(count($product_ids) > 0){
            $products = Product::find($product_ids);
        }

        $product_name = '';
        foreach ($products as $product){
            if(($campaign !== null) && ($product !== null)) {
                // check if a booking exists for the given campaign and product
                $booking_detail = BookingDetail::where([
                    ['campaign_id', $campaign->id],
                    ['product_id', $product->id]
                ])->first();

            }else{
                // otherwise create new booking object and assign to the given campaign and product
                $booking_detail = new BookingDetail();
                $booking_detail->product()->associate($campaign);
                $booking_detail->product()->associate($product);
            }

            // format product specific json fields
            if(in_array($product->id, [Product::RICH_MEDIA, Product::DISPLAY, Product::MOBILE, Product::AUDIO])){
                // tracking is only for rm, media and mobile
                if(in_array($product->id, [Product::RICH_MEDIA, Product::DISPLAY, Product::MOBILE])){
                    $tracking_tag           = json_encode($input['tracking_tag']);
                    $input['tracking_tag']  = $tracking_tag;

//                    var_dump($input['rm_creative_format']);die;
                    if(array_key_exists('rm_creative_format', $input)){
                        $rm_creative_formats            = json_encode($input['rm_creative_format']);
                        $input['rm_creative_format']   = $rm_creative_formats;
                    }

                }

                $budget_silos           = json_encode($input['budget_silos']);
                $input['budget_silos']  = $budget_silos;

                $supplied_creative_formats              = json_encode($input['supplied_creative_formats']);
                $input['supplied_creative_formats']     = $supplied_creative_formats;
            }elseif(in_array($product->id, [Product::VOD])){
                $tracking_tag_dsp = json_encode($input['tracking_tag_dsp']);
                $input['tracking_tag_dsp'] = $tracking_tag_dsp;
            }

            // set status to submitted
            $booking_status = BookingStatus::findOrFail(BookingStatus::SUBMITTED);
            $booking_detail->bookingStatus()->associate($booking_status);

            // 'fill' booking object and save!
            $booking_detail->fill($input)->save();

            $product_name .= $product->name.', ';
        }

        // if booking is being edited after being submitted then send a booking updated email
        if($campaign->status->id >= Status::BOOKING_FORM_SUBMITTED && $campaign->status->id <= Status::UPLOADED_CREATIVE_TAGS){
            // if the booking has been submitted then send a booking updated email
            $email = new BFUpdated($campaign);

            // get email recipients
            $activation_users   = $campaign->brief->getActivationUsers();
            $vod_users          = $campaign->brief->vodUsers;

            Mail::to($activation_users->merge($vod_users))->send($email);
        }

        return \Redirect::to('workflow/'.$campaign_id)->with('success', 'Booking form for '.$product_name.' updated.');
        // write status (?)
    }

}