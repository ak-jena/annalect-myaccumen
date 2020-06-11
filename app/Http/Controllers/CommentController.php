<?php
/**
 * Created by PhpStorm.
 * User: saeed.bhuta
 * Date: 16/06/2017
 * Time: 11:03
 */

namespace App\Http\Controllers;

use App\Brief;
use App\Campaign;
use App\Log;
use App\Mail\CampaignCancelled;
use App\Mail\NewComment;
use App\Status;
use App\User;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

use App\Mail\TgRejectedByAgency;
use App\Mail\TgRejectedByALM;
use App\Mail\TgRejectedByHoA;
use App\Mail\ATRejectedBF;
use App\Mail\ALMRejectedBF;

class CommentController extends Controller
{

    public function index(Request $request, $brief_id, $redirect = null){

        $brief      = Brief::findOrFail($brief_id);

        return view('comment.index', ['brief' => $brief, 'redirect' => $redirect]);
    }


    public function addComment(Request $request){

        $this->validate($request, [
            //
            'comment' => 'required',
        ]);

        $title      = null;
        $comment_body    = $request->input('comment');

        $user_id    = $request->input('user_id');
        $user       = User::findOrFail($user_id);

        $brief_id   = $request->input('brief_id');
        $brief      = Brief::findOrFail($brief_id);

        $comment = new Comment();
        $comment->title = $title;
        $comment->body = $comment_body;
        $comment->author()->associate($user);
        $comment->brief()->associate($brief);

        $comment->save();

        // send email to agency users notifying them of comment

        // get agency users working on this brief's client
        $client_users = $brief->client_users;

        // remove the user making the comment
        $filtered_client_users = $client_users->filter(function ($value, $key) use ($user) {
            if($value->id !== $user->id){
                return $value;
            }

        });

        $email = new NewComment($brief->campaign);

        // send email
        Mail::to($filtered_client_users)->send($email);

        $request = $request->input('redirect');

        if($request == 'dashboard'){
            return \Redirect()->route('dashboard')->with('success', 'Comment has been added!');
        }elseif($request == 'workflow'){
            return \Redirect()->route('workflow', ['campaign_id' => $comment->brief->campaign->id])->with('success', 'Comment has been added!');
        }else{
            return \Redirect()->route('comments', ['brief_id' => $comment->brief->id])->with('success', 'Comment has been added!');
        }

    }

    public function editComment(Request $request, $brief_id, $comment_id){

        $brief      = Brief::findOrFail($brief_id);

        // check that user is allowed to edit comment (ie is it the latest one)
        $comments = $brief->comments->sortByDesc('created_at');

        $first_comment = $comments->first();

        if ($first_comment->author->id == \Auth::id()) {
            if ($comment_id != $first_comment->id) {
                return \Redirect()->route('comments', ['brief_id' => $brief->id])->with('error', 'Comment cannot be edited!');
            }
        }

        $comment    = Comment::findOrFail($comment_id);

        return view('comment.edit', ['comment' => $comment, 'brief' => $brief]);
    }

    public function updateComment(Request $request){

        $this->validate($request, [
            //
            'comment' => 'required',
        ]);

        $comment_id     = $request->input('comment_id');
        $comment          = Comment::findOrFail($comment_id);

        $title  = null;
        $body   = $request->input('comment');

        $comment->body = $body;
        $comment->save();

        return \Redirect()->route('comments', ['brief_id' => $comment->brief->id])->with('success', 'Comment has been updated!');
    }

    public function destroyComment(Request $request, $brief_id, $comment_id){
        $brief = Brief::findOrFail($brief_id);

        // check that user is allowed to delete comment (ie is it the latest one) and are they the author
        $comments       = $brief->comments->sortByDesc('created_at');
        $first_comment  = $comments->first();

        if ($first_comment->author->id == \Auth::id()) {
            if ($comment_id != $first_comment->id) {
                return \Redirect()->route('comments', ['brief_id' => $brief->id])->with('error', 'Comment cannot be edited!');
            }
        }

        // delete comment
        $comment = Comment::find($comment_id);
        $comment->delete();

        return \Redirect()->route('comments', ['brief_id' => $brief->id])->with('success', 'Comment has been deleted!');
    }

    public function cancelCampaign(Request $request, $campaign_id){

        $campaign = Campaign::findorFail($campaign_id);

        if($campaign->status !== null){
            if(in_array($campaign->status->id, [Status::CAMPAIGN_CANCELLED, Status::UPLOADED_CREATIVE_TAGS])){
                // cant cancel campaign that has been already cancelled or tags submitted
                return \Redirect()->route('dashboard');
            }
        }

        $cancellation_reasons = [
            'Budget wasnt signed off'       => 'Budget wasnt signed off',
            'Budget cancelled by client'    => 'Budget cancelled by client',
            'External situations'           => 'External situations',
            'Other'                         => 'Other'
        ];

        return view('comment.cancel-campaign', [
            'campaign' => $campaign,
            'cancellation_reasons' => $cancellation_reasons
        ]);
    }

    public function processCampaignCancellation(Request $request){
        $this->validate($request, [
            'cancellation_reason' => 'required',
            'other_reason' => 'sometimes|required',
        ]);

        $input = $request->all();
        $brief_id   = $input['brief_id'];
        $brief      = Brief::findOrFail($brief_id);

        if($brief->campaign->status !== null){
            if(in_array($brief->campaign->status->id, [Status::CAMPAIGN_CANCELLED, Status::UPLOADED_CREATIVE_TAGS])){
                // cant cancel campaign that has been already cancelled or tags submitted
                return \Redirect()->route('dashboard');
            }
        }

        $user       = User::findorFail(\Auth::id());

        // set the campaign status to cancelled
        $cancelled_status = Status::findorFail(Status::CAMPAIGN_CANCELLED);

        $brief->campaign->is_active = 0;
        $brief->campaign->save();

        // write status to log table
        $log = new Log();
        $log->status()->associate($cancelled_status);
        $log->campaign()->associate($brief->campaign);

        $log->user()->associate($user);
        $log->save();

        // insert comment with reason and person who cancelled
        $title      = null;
        $comment_body = '<strong>Campaign has been cancelled by '.$user->name.' because: </strong>';
        if(array_key_exists('other_reason', $input)){
            $comment_body .= $input['other_reason'];
        }else{
            $comment_body .= $input['cancellation_reason'];
        }

        $brief_id   = $input['brief_id'];
        $brief      = Brief::findOrFail($brief_id);

        $system_comment_user = User::where('name', 'System Message')->first();

        $comment = new Comment();
        $comment->title = $title;
        $comment->body = $comment_body;
        $comment->author()->associate($system_comment_user);
        $comment->brief()->associate($brief);

        $comment->save();

        $recipients = new Collection();

        // send email to relevant users notifying them of campaign cancellation
        if($brief->campaign->isOver100k()) {
            $recipients->merge($brief->getHeadsOfActivationUsers());
        }

        $recipients->merge($brief->getActivationUsers());
        $recipients->merge($brief->getActivationLineManagers());
        $recipients->merge($brief->clientUsers);

        Mail::to($recipients)->send(new CampaignCancelled($brief->campaign));

        return \Redirect()->route('dashboard')->with('success', 'Campaign has been cancelled!');

    }

    public function rejectTargetingGrid(Request $request, $campaign_id){

        if(\Baselib::canApproveTargetingGrid() == false){
            return \Redirect()->route('dashboard');
        }

        $campaign           = Campaign::findorFail($campaign_id);
        $campaign_status    = $campaign->status;

        // determine operation type ie what stage of approval/rejection the campaign is at
        $op_type = null;

        switch ($campaign_status->id) {
            case Status::TARGETING_GRID_UPLOADED:
                $op_type = 'lm-reject-grid';
                break;
            case Status::TG_APPROVED_BY_LINE_MANAGER:
                if($campaign->requiresHeadOfActivationApproval()){
                    $op_type = 'hoa-reject-grid';
                }else{
                    $op_type = 'agency-reject-grid';
                }
                break;
            case Status::TG_APPROVED_BY_HEAD_OF_ACTIVATION:
                $op_type = 'agency-reject-grid';
                break;
            default:
                return \Redirect()->route('dashboard');
        }

        $rejection_reasons = [
            'Incorrect info'                => 'Incorrect info',
            'Below minimum spend'           => 'Below minimum spend',
            'Campaign cannot be delivered'  => 'Campaign cannot be delivered',
            'Other'                         => 'Other'
        ];

        return view('comment.reject-targeting-grid', [
            'campaign'          => $campaign,
            'op_type'           => $op_type,
            'rejection_reasons' => $rejection_reasons
        ]);
    }

    public function processTargetingGridRejection(Request $request){

        if(\Baselib::canApproveTargetingGrid() == false){
            return \Redirect()->route('dashboard');
        }

        $this->validate($request, [
            'rejection_reason' => 'required',
            'other_reason' => 'sometimes|required',
        ]);

        $input          = $request->all();
        $brief_id       = $input['brief_id'];
        $operation_type = $input['op_type'];

        $brief      = Brief::findOrFail($brief_id);
        $campaign   = $brief->campaign;

        if($campaign->status !== null){
            if(in_array($campaign->status->id, [Status::TARGETING_GRID_UPLOADED, Status::TG_APPROVED_BY_LINE_MANAGER, Status::TG_APPROVED_BY_HEAD_OF_ACTIVATION]) == false){
                // cant cancel campaign that has been already cancelled or tags submitted
                return \Redirect()->route('dashboard');
            }
        }

        $rejection_reason = '';
        if(array_key_exists('other_reason', $input)){
            $rejection_reason .= $input['other_reason'];
        }else{
            $rejection_reason .= $input['rejection_reason'];
        }

        $user           = User::findorFail(\Auth::id());
        $status         = null;
        $alert_message = 'Targeting Grid rejected.';
        // contains the emails to be sent along with their recipients
        $email_type    = null;
        $comment_body   = '<p><strong>Targeting Grid Rejected by '.$user->name.' because: </strong>'.$rejection_reason.'</p>';

        $activation_users               = $campaign->brief->getActivationUsers();
        $activation_line_manager_users  = $campaign->brief->getActivationLineManagers();
        $all_activation_users           = $activation_users->merge($activation_line_manager_users);
        $vod_users                      = $campaign->brief->vodUsers;
        $recipients                     = $all_activation_users->merge($vod_users);

        switch ($operation_type){
            case 'lm-reject-grid':
                $status     = Status::where('name', 'TG Rejected by Line Manager')->first();
                $email_type = new TgRejectedByALM($campaign);
                break;
            case 'hoa-reject-grid':
                $status     = Status::where('name', 'TG Rejected by Head of Activation')->first();
                $email_type = new TgRejectedByHoA($campaign);
                break;
            case 'agency-reject-grid':
                $status     = Status::where('name', 'TG Rejected by Agency User')->first();
                $email_type = new TgRejectedByAgency($campaign);
                break;
            default:
                return \Redirect()->route('dashboard');
        }

        // update campaign status
        // write status to log table
        if($status !== null){
            $log = new Log();
            $log->status()->associate($status);
            $log->campaign()->associate($campaign);

            $log->user()->associate($user);
            $log->save();

            // add comment
            $comment = new Comment();
            $comment->title = 'Targeting Grid';
            $comment->body  = $comment_body;
            $comment->brief()->associate($campaign->brief);

            $system_comment_user = User::where('name', 'System Message')->first();
            $comment->author()->associate($system_comment_user);
            $comment->save();
        }

        // Send email
        Mail::to($recipients)->send($email_type);

        return redirect('dashboard')->with('success', $alert_message);

    }

    public function rejectBookingForm(Request $request, $campaign_id){

        if(\Baselib::canApproveBooking() == false){
            return \Redirect()->route('dashboard');
        }

        $campaign           = Campaign::findorFail($campaign_id);
        $campaign_status    = $campaign->status;

        // determine operation type ie what stage of approval/rejection the campaign is at
        $op_type = null;

        switch ($campaign_status->id) {
            case Status::BOOKING_FORM_SUBMITTED:
                $op_type = 'at-reject-booking';
                break;
            case Status::BF_APPROVED_BY_ACT_TEAM:
                $op_type = 'lm-reject-booking';
                break;
            default:
                return \Redirect()->route('dashboard');
        }

        $rejection_reasons = [
            'Booking form does not match IO' => 'Booking form does not match IO',
            'Flight dates do not match IO' => 'Flight dates do not match IO',
            'Campaign cannot be delivered' => 'Campaign cannot be delivered',
            'Wrong DSP' => 'Wrong DSP',
            'Incorrect info' => 'Incorrect info',
            'Does not match Targeting grid' => 'Does not match Targeting grid',
            'Below minimum spend' => 'Below minimum spend',
            'Other' => 'Other'
        ];

        return view('comment.reject-booking-form', [
            'campaign'          => $campaign,
            'op_type'           => $op_type,
            'rejection_reasons' => $rejection_reasons
        ]);
    }

    public function processBookingFormRejection(Request $request){
        if(\Baselib::canApproveBooking() == false){
            return \Redirect()->route('dashboard');
        }

        $this->validate($request, [
            'rejection_reason' => 'required',
            'other_reason' => 'sometimes|required',
        ]);

        $input          = $request->all();
        $brief_id       = $input['brief_id'];
        $operation_type = $input['op_type'];

        $brief      = Brief::findOrFail($brief_id);
        $campaign   = $brief->campaign;

        if($campaign->status !== null){
            if(in_array($campaign->status->id, [Status::BOOKING_FORM_SUBMITTED, Status::BF_APPROVED_BY_ACT_TEAM]) == false){
                // Ensure campaign is at the right stage
                return \Redirect()->route('dashboard');
            }
        }

        $rejection_reason = '';
        if(array_key_exists('other_reason', $input)){
            $rejection_reason .= $input['other_reason'];
        }else{
            $rejection_reason .= $input['rejection_reason'];
        }

        $user           = User::findorFail(\Auth::id());
        $status         = null;
        $alert_message  = 'Booking form rejected.';
        // contains the emails to be sent along with their recipients
        $email_type     = null;
        $comment_body   = '<p><strong>Booking Form Rejected by '.$user->name.' because: </strong>'.$rejection_reason.'</p>';

        $recipients = null;

        switch ($operation_type){
            case 'at-reject-booking':
                $status     = Status::where('name', 'BF Rejected by Act. Team')->first();
                $email_type = new ATRejectedBF($campaign);
                $recipients = $campaign->brief->clientUsers;
                break;
            case 'lm-reject-booking':
                $status     = Status::where('name', 'BF Rejected by Act. Line Manager')->first();
                $email_type = new ALMRejectedBF($campaign);
                $recipients = $campaign->brief->getActivationUsers();
                break;
            default:
                return \Redirect()->route('dashboard');
        }

        // update campaign status
        // write status to log table
        if($status !== null){
            $log = new Log();
            $log->status()->associate($status);
            $log->campaign()->associate($campaign);

            $log->user()->associate($user);
            $log->save();

            // add comment
            $comment = new Comment();
            $comment->title = 'Booking Form';
            $comment->body  = $comment_body;
            $comment->brief()->associate($campaign->brief);

            $system_comment_user = User::where('name', 'System Message')->first();
            $comment->author()->associate($system_comment_user);
            $comment->save();
        }

        // Send email
        Mail::to($recipients)->send($email_type);

        return redirect('dashboard')->with('success', $alert_message);

    }

}
