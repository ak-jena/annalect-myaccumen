<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Agency;
use DB;
use Hash;
use Baselib;
use Mail;

class AgencyController extends Controller
{

    /**
     * Ajax load agencies
     */
    public function indexAjaxData()
    {
        $agency = DB::table('agencies')
            ->leftJoin('users', 'agencies.contact_user_id', '=', 'users.id')
            ->select('agencies.*', 'users.name as contact_person')
            ->get();

        return Datatables::of($agency)
                ->editColumn('name', function($agency){
                    return "<span rel='popover' data-trigger='hover' data-container='body' data-placement='top' data-original-title='' data-content=''><a href='". url("agency/".$agency->id."/edit")."'>".$agency->name."</a></span>";
                })
                ->addColumn('action', function ($agency) {
                    return "
                        <a href='". url("agency/".$agency->id."/edit")."' class='btn btn-sm btn-warning' rel='tooltip' title='Edit'><i class='fa fa-edit'></i> </a>
                        <button class='btn-delete btn btn-danger btn-sm' data-remote='/agency/" . $agency->id . "' rel='tooltip' title='Delete'><i class='fa fa-trash-o'></i> </button>
                    ";
                })
                ->remove_column('id')
                ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      
        \View::share('breadcrumbs', [
            ['name' => 'Agencies']
        ]);          
        return view('agency.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        \View::share('breadcrumbs', [
            ['url' => route('agency.index'), 'name' => 'Agencies']
        ]);          
        return view('agency.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'logo' => 'image'
        ]);
        
        $input = $request->all();

        // process logo upload
        $logoFile = $request->file('logo');

        $originalLogoFilename       = $logoFile->getClientOriginalName();
        $unprocessedLogoFilename    = $input['name'].'-'.$originalLogoFilename;
        $unix_timestamp             = time();
        $processedLogoFilename      = $this->clean($unprocessedLogoFilename).'-'.$unix_timestamp.'.'.$logoFile->guessExtension();

        $logoFilePath = $logoFile->storePubliclyAs('public/agency-logos', $processedLogoFilename);

        $input['logo'] = $logoFilePath;

        $agency = new Agency();
        $agency->fill($input)->save();
        
        //check if record is created and get id
        $new_id = DB::table("agencies")->where("name", $input['name'])->value("id");

        if(!empty($new_id)){
            return \Redirect::to('agency')->with('success', 'Agency <b>'.$input['name'].'</b> has been successfully created.</b>');
        }
        
        return \Redirect::to('agency')->with('error', 'Problem creating agency, please try again!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
        \View::share('breadcrumbs', [
            ['url' => route('agency.index'), 'name' => 'Agencies']
        ]);        
        $agency = Agency::findOrFail($id);
        return view('agency.edit')->withAgency($agency);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
  
        $agency = Agency::findOrFail($id);

        $this->validate($request, [
            'name' => 'required',
            'logo' => 'image'
        ]);

        $input = $request->all();

        $logoFile = $request->file('logo');

        // if a logo has been uploaded then existing logo will be replaced
        if($logoFile !== null){
            $originalLogoFilename       = $logoFile->getClientOriginalName();
            $unprocessedLogoFilename    = $input['name'].'-'.$originalLogoFilename;
            $unix_timestamp             = time();
            $processedLogoFilename      = $this->clean($unprocessedLogoFilename).'-'.$unix_timestamp.'.'.$logoFile->guessExtension();

            $logoFilePath = $logoFile->storePubliclyAs('public/agency-logos', $processedLogoFilename);
        }else{
            // use existing logo
            $logoFilePath = $agency->logo;
        }

        $input['logo'] = $logoFilePath;


        $agency->fill($input)->save();

        return \Redirect::to('agency')->with('success', 'Agency <b>'.$input['name'].'</b> has been successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $agency = Agency::findOrFail($id);
        $agency->delete();
        return \Redirect::to('agency')->with('success', 'Agency successfully deleted!');
    }
    
    public function delete($id)
    {
        $agency = Agency::findOrFail($id);
        $agency->delete();
        return \Redirect::to('agency')->with('success', 'Agency successfully deleted!');
    }
    
    public function autocomplete(){
        $term = \Input::get('term');
        $results = array();
        $queries = DB::table('agencies')
                ->where('name', 'ILIKE', '%'.$term.'%')
                ->orderBy('name', 'asc')
                ->get();

        foreach ($queries as $query)
        {
            $results[] = [ 'id' => $query->id, 'value' => $query->name ];
        }
        
        return \Response::json($results);
    }    

    private function sendMail($user){
            // Prepare the e-mail to be sent and send it
            Mail::send('emails.newuser', $user, function($message) use ($user)
            {
                $message->to($user['email']);
                $message->subject('Your Minerva credentials');
            });        
    }

    function clean($string) {

        // remove extension
        $string = strtolower(pathinfo($string, PATHINFO_FILENAME)); // remove extension and convert to lower case
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }

}
