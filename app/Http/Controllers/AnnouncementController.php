<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Announcement;
use DB;

class AnnouncementController extends Controller
{
    
    /**
     * Ajax call to load index data
    */
    public function indexAjaxData()
    {
        $announcements = Announcement::select(['id','message','icon','url','start_date','end_date','is_active','user_group']);
        return Datatables::of($announcements)
                ->editColumn('is_active', function($announcement){
                    $now = date("Y-m-d H:i:s");
                    if($announcement->end_date < $now){
                        return "<span class='fa fa-circle text-black' data-original-title='Expired' data-toggle='tooltip' data-placement='left'></span>";
                    }                    
                    else if($announcement->is_active==0){
                        return "<span class='fa fa-circle text-red' data-original-title='Inactive' data-toggle='tooltip' data-placement='left'></span>";
                    }
                    else{
                        return "<span class='fa fa-circle text-green' data-original-title='Active' data-toggle='tooltip' data-placement='left'></span>";
                    }
                })
                ->editColumn('user_group', function($announcement){
                    switch($announcement->user_group){
                        case 0:
                            $txt="Everyone";
                            break;
                        case 1:
                            $txt="Traders";
                            break;
                        case 2:
                            $txt="Inventories";
                            break;
                        case 3:
                            $txt="Managers";
                            break;
                        case 4:
                            $txt="Developers";
                            break;
                    }
                    return "<b>".$txt."</b>";
                })
             
                ->addColumn('action', function ($announcement) {
                    return "
                        <a href='". url("announcement/".$announcement->id."/edit")."' class='btn btn-sm btn-warning' rel='tooltip' title='Edit'><i class='fa fa-edit'></i> </a>
                        <button class='btn-delete btn btn-danger btn-sm' data-remote='/announcement/" . $announcement->id . "' rel='tooltip' title='Delete'><i class='fa fa-trash-o'></i> </button>
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
            ['name' => 'Announcements']
        ]);          
        return view('announcement.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        \View::share('breadcrumbs', [
            ['url' => route('announcement.index'), 'name' => 'Announcements']
        ]);          
        return view('announcement.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!isset($request['start_date'])||empty($request['start_date'])||$request['start_date']='Now'){
            $request['start_date']=date('Y-m-d 00:00:00');
        }
        if(!isset($request['end_date'])||empty($request['end_date'])||$request['end_date']='Next week'){
            $request['end_date'] = date("Y-m-d 23:59:59", mktime(0, 0, 0, date("m"), date("d")+7, date("Y")));
        }
        if(!isset($request['is_active'])){
            $request['is_active'] = 0;
        }           
        
        $this->validate($request, [
            'message' => 'required|min:6|max:40',
            'icon' => 'required|min:4|max:40',
            'url' => 'required|max:100',
            'is_active' => 'required|digits:1',            
            'user_group' => 'required|digits:1',            
        ]);        
        
        $id = DB::table('announcements')->insert(
            [
                'message' => $request['message'],
                'icon' => $request['icon'],
                'url' => $request['url'],
                'start_date' => $request['start_date'],
                'end_date' => $request['end_date'],
                'is_active' => $request['is_active'],
                'user_group' => $request['user_group'],
            ]
        );        
       
        if($id){
            return \Redirect::to('announcement')->with('success', 'Announcement has been successfully created!');
        }
        
        return \Redirect::to('announcement')->with('error', 'Problem creating announcement, please try again!');
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
            ['url' => route('announcement.index'), 'name' => 'Announcements']
        ]);        
        $announcement = Announcement::findOrFail($id);
        return view('announcement.edit')->withAnnouncement($announcement);
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
        $announcement = Announcement::findOrFail($id);
        
        if(!isset($request['start_date'])||empty($request['start_date'])){
            $request['start_date']=NULL;
        }
        if(!isset($request['end_date'])||empty($request['end_date'])){
            $request['end_date']=NULL;
        }
        if(!isset($request['is_active'])){
            $request['is_active'] = 0;
        }          
        
        $this->validate($request, [
            'message' => 'required|min:6|max:40',
            'icon' => 'required|min:4|max:40',
            'url' => 'required|max:100',
            'is_active' => 'required|digits:1',            
            'user_group' => 'required|digits:1',            
        ]);        

        $input = $request->all();

        $announcement->fill($input)->save();

        return \Redirect::to('announcement')->with('success', 'Announcement has been successfully updated!');  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();
        return \Redirect::to('announcement')->with('success', 'Announcement successfully deleted!');
    }
}
