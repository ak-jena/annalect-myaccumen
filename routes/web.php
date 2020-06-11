<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();
Route::group(['middleware' => 'auth'], function(){
    Route::group(['middleware' => 'lock'], function(){
        Route::get('/', ['as' => 'dashboard', 'uses' => 'DashboardController@filterDashboard']);
        Route::get('dashboard', ['uses' => 'DashboardController@filterDashboard'])->name('dashboard');
        Route::get('cancelled-campaigns', ['uses' => 'DashboardController@cancelledCampaigns'])->name('cancelled-campaigns');
        Route::get('live-campaigns', ['uses' => 'DashboardController@liveDashboard'])->name('live-campaigns');
        Route::get('completed-campaigns', ['uses' => 'DashboardController@completedDashboard'])->name('completed-campaigns');

        Route::get('/sample', ['as' => 'sample', 'uses' => 'IndexController@index']);

        Route::get('completed-campaigns-check', function() {
            Artisan::call('campaigns:set-complete');
//            dd($exit_code);
            return redirect()->route('completed-campaigns');
        })->name('completed-campaigns-check');

        Route::get('live-campaigns-check', function() {
            Artisan::call('campaigns:set-live');
//            dd($exit_code);
            return redirect()->route('live-campaigns');
        })->name('live-campaigns-check');

//        Route::get('filter-dashboard', ['uses' => 'DashboardController@filterDashboard'])->name('filter-dashboard');
        Route::get('campaigns-tiles', ['uses' => 'DashboardController@retrieveCampaigns'])->name('campaigns-tiles');

        Route::get('live-campaigns-tiles', ['uses' => 'DashboardController@retrieveLiveCampaigns'])->name('live-campaigns-tiles');
        Route::get('live-campaigns-old', ['uses' => 'DashboardController@liveCampaigns'])->name('live-campaigns-old');

        Route::get('completed-campaigns-tiles', ['uses' => 'DashboardController@retrieveCompletedCampaigns'])->name('completed-campaigns-tiles');
        Route::get('completed-campaigns-old', ['uses' => 'DashboardController@completedCampaigns'])->name('completed-campaigns-old');

        /* workflow */
        /* brief */
        Route::get('workflow/{campaign_id?}/{existing_brief_id?}', 'WorkflowController@showWorkflowForm')->name('workflow');
        Route::post('process-brief-form', 'WorkflowController@processBriefForm');
        Route::post('process-campaign-info-1', 'WorkflowController@processKeyCampaignInfo1')->name('campaign-info-1');
        Route::post('process-campaign-info-2', 'WorkflowController@processKeyCampaignInfo2')->name('campaign-info-2');
        Route::post('process-display-media-mobile-1', 'WorkflowController@processDisplayMediaMobile1')->name('display-media-mobile-1');
        Route::post('process-display-media-mobile-2', 'WorkflowController@processDisplayMediaMobile2')->name('display-media-mobile-2');
        Route::post('process-display-media-mobile-3', 'WorkflowController@processDisplayMediaMobile3')->name('display-media-mobile-3');
        Route::post('process-audio-1', 'WorkflowController@processAudio1')->name('audio-1');
        Route::post('process-audio-2', 'WorkflowController@processAudio2')->name('audio-2');
        Route::post('process-video-1', 'WorkflowController@processVideo1')->name('video-1');
        Route::post('process-video-2', 'WorkflowController@processVideo2')->name('video-2');
        Route::post('process-file-upload', 'WorkflowController@processFileUpload')->name('brief-file-upload');
        Route::post('process-brief-submission', 'WorkflowController@processBriefSubmission')->name('submit-brief');

        Route::get('export-brief/{brief_id}', 'ExportController@exportBriefData')->name('export-brief');

        /* grid */
        Route::post('process-grid', 'WorkflowController@processGrid')->name('process-grid');
        Route::post('process-grid-approval-form', 'WorkflowController@processGridApprovalForm')->name('grid-approval');

        /* booking */
//        Route::post('process-dsp-submission', 'WorkflowController@processDspBudget')->name('process-dsp-submission');
        Route::post('process-dsp-submission', 'DspController@processDspBudget')->name('process-dsp-submission');
        Route::post('process-date-change', 'DspController@processDateChange')->name('process-date-change');

        Route::get('booking/{campaign_id}/{product_ids}', 'BookingController@showBookingForm')->name('booking');
        Route::post('process-booking/{campaign_id}/{product_ids}', 'BookingController@processBookingForm')->name('process-booking');

        Route::post('process-booking-approval-form', 'WorkflowController@processBookingApprovalForm')->name('booking-approval');

        Route::get('export-booking/{brief_id}', 'ExportController@exportBookingData')->name('export-booking');

//        Route::post('process-io-form', 'WorkflowController@processIoForm');
        Route::post('process-creative-tags-form', 'WorkflowController@processCreativeTagsForm');

        Route::post('delete-product', 'DspController@deleteProduct')->name('delete-product');

        /* io */
        Route::post('process-io', 'WorkflowController@processIo')->name('process-io');

        /* io */
        Route::post('process-tags', 'WorkflowController@processTags')->name('process-tags');

        /* Profile */
        Route::get('/profile','ProfileController@index');
        Route::post('/profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
        Route::post('/user/pref','UserController@updatePref');

        /* Comments */
        Route::get('/comments/{brief_id}/{redirect?}','CommentController@index')->name('comments');
        Route::post('/comments/add','CommentController@addComment')->name('add-comment');
        Route::get('/comments/edit/{brief_id}/{comment_id}','CommentController@editComment')->name('edit-comment');
        Route::post('/comments/update','CommentController@updateComment')->name('update-comment');
        Route::delete('/comments/destroy/{brief_id}/{comment_id}','CommentController@destroyComment')->name('destroy-comment');

        Route::get('campaign/cancel/{campaign_id}','CommentController@cancelCampaign')->name('cancel-campaign-form');
        Route::post('campaign/process-cancellation','CommentController@processCampaignCancellation')->name('process-campaign-cancellation');        Route::get('campaign/cancel/{campaign_id}','CommentController@cancelCampaign')->name('cancel-campaign-form');

        Route::get('campaign/reject-tg/{campaign_id}','CommentController@rejectTargetingGrid')->name('reject-tg');
        Route::post('campaign/process-tg-rejection','CommentController@processTargetingGridRejection')->name('process-tg-rejection');        Route::get('campaign/reject-tg/{campaign_id}','CommentController@rejectTargetingGrid')->name('reject-tg');

        Route::get('campaign/reject-bf/{campaign_id}','CommentController@rejectBookingForm')->name('reject-bf');
        Route::post('campaign/process-bf-rejection','CommentController@processBookingFormRejection')->name('process-bf-rejection');

        /* View as Functionality */
        Route::get('/viewas','Custom\ViewasController@setViewAs');

        // *** NOTE: only developers and users with 'can manage users privilege' can access user managements  *** //
        Route::group(['middleware' => 'user.access'], function() {
            /* Users Management */
            Route::get('/user/autocomplete', ['as' => 'user.autocomplete', 'uses' => 'UserController@autocomplete']);
            Route::get('/user/delete/{id}', 'UserController@delete');
            Route::delete('/user/{id}', 'UserController@destroy');
            Route::post('/user/getusers', 'UserController@indexAjaxData');
            Route::resource('user', 'UserController');
        });

        // *** NOTE: ONLY MANAGERS CAN ACCESS THESE ROUTES *** //
        Route::group(['middleware' => 'manager.access'], function(){

            /* Agency Management */
//            Route::get('/user/autocomplete', ['as' => 'user.autocomplete', 'uses' => 'UserController@autocomplete'] );
//            Route::get('/user/delete/{id}','UserController@delete');
//            Route::delete('/user/{id}','UserController@destroy');
            Route::post('/agency/getagencies','AgencyController@indexAjaxData');
            Route::resource('agency', 'AgencyController');

            /* Client Management */
//            Route::get('/client/autocomplete', ['as' => 'client.autocomplete', 'uses' => 'ClientController@autocomplete'] );
//            Route::get('/client/delete/{id}','ClientController@delete');
//            Route::delete('/client/{id}','ClientController@destroy');
            Route::post('/client/getclients','ClientController@indexAjaxData');
            Route::resource('client', 'ClientController');

            /* Announcement */
            //Route::get('/announcement/{id}','AnnouncementController@destroy');
            Route::delete('/announcement/{id}','AnnouncementController@destroy');
            Route::post('/announcement/getdata','AnnouncementController@indexAjaxData');    
            Route::resource('announcement', 'AnnouncementController');
        });

        /* Only developers and HoA users can access export report */
        Route::group(['middleware' => 'head.of.activation'], function(){

            Route::get('/reporting', 'ReportingController@index')->name('reporting');
            Route::post('/reporting/update', 'ReportingController@update')->name('update-reporting');
            Route::post('/reporting/export', 'ReportingController@export')->name('export-report');
        });


        // *** NOTE: ONLY DEVELOPERS CAN ACCESS THESE ROUTES *** //
        Route::group(['middleware' => 'developer.access'], function(){
            /* poc grid */
            Route::get('/targeting-grid-poc', 'TargetingGridController@poc')->name('tg-poc');
            Route::get('targeting-grid/{targeting_grid_id?}', 'TargetingGridController@retrieveTargetingGrid')->name('retrieve-grid-data');


            Route::get('/grid/{campaign_id}', 'TargetingGridController@grid')->name('show-targeting-grid');
            Route::get('/retrieve-grid/{campaign_id}/{product_id}', 'TargetingGridController@retrieveGrid')->name('retrieve-grid');

            Route::get('/ag-grid', 'TargetingGridController@agGrid')->name('show-ag-targeting -grid');
            Route::get('/ag-grid-poc', 'TargetingGridController@pocV2')->name('ag-grid-poc');
//            Route::get('/retrieve-grid/{campaign_id}/{product_id}', 'TargetingGridController@retrieveGrid')->name('retrieve-grid');



            Route::get('/ttd-api','Debug\TradeDeskAPIController@index');
            Route::get('/apn-api','Debug\AppNexusAPIController@index');
         
            /* Debug */
            Route::get('/debug','Debug\DebugController@index');
            Route::get('/debug/error403','Debug\DebugController@error403');
            Route::get('/debug/error500','Debug\DebugController@error500');
            Route::get('/debug/error503','Debug\DebugController@error503');
            Route::get('/debug/identity','Debug\DebugController@showIdentity');
            Route::get('/debug/session','Debug\DebugController@showSession');
            Route::get('/debug/path','Debug\DebugController@showPath');
            Route::get('/debug/clear-cache','Debug\DebugController@clearCache');
            Route::get('/debug/clear-session','Debug\DebugController@clearSession');
            Route::get('/debug/clear-view','Debug\DebugController@clearView');
            Route::get('/debug/action1','Debug\DebugController@action1');
            Route::get('/debug/action2','Debug\DebugController@action2');  
            Route::get('/debug/action3','Debug\DebugController@action3');
        }); 
     });
    
    Route::post('/auth/lock','Auth\LockScreenController@post');
    Route::get('/auth/lock','Auth\LockScreenController@get');
    Route::get('/logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);
});


