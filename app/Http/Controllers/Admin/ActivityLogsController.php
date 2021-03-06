<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;
use App\Http\Models\Admin\ActivityLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use DB;
use Response;

class ActivityLogsController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getActivityLogsList(Request $request)
    {
        $limit = $request->has('limit') ? $request->limit : 10;
        $sort_by = $request->has('sort_by') ? $request->sort_by : 'id';
        $sort = $request->has('sort') ? strtolower($request->sort) : 'desc';
        $page = $request->has('page') ? $request->page : 1;
        $url = URL::current();
        $sorting_url = "$url";
        // ?page=$page&sort_by=$sort_by&sort=$sort&limit=$limit
        $activities = new ActivityLogs;

        $display_count = $page*$limit;
        if($display_count>$activities->count()){
            $display_count = $activities->count();
        }

        $paginate_msg = "Showing ".((($page-1)*$limit)+1)." to ".(int) $display_count." of ".$activities->count();

        $last_updated = $activities->latest("updated_at")->first();
        if($last_updated){
            $last_updated = $last_updated->updated_at->format('d F, Y @ H:i A');
        }else{
            $last_updated = '';
        }

        $activities = $activities->with(['user' => function($q){
            $q->with('role');
        }])->orderBy($sort_by, $sort)->paginate($limit);


//        dd($activities);
//        echo "<pre>".print_r($activities)."</pre>";exit;
        //['role'][0]['role']
        return view('admin.activity_logs.index')->with(compact('activities', 'last_updated', 'limit', 'sort', 'sort_by', 'sorting_url', 'paginate_msg', 'page'));

    }

    public function removeRow(Request $request, $type)
    {
        if($type == 'selected'){
            if(!$request->has('activities') || !$request->activities){
                return redirect()->back()->with('warning', 'Please select at least one product before delete.');
            }

            $activities = json_decode($request->activities, true);

            if(ActivityLogs::whereIn('id', $activities)->delete()){
                return redirect()->back()->with('success', 'Selected items deleted successfully!');
            }
        }

        if($type == 'simple'){
            if(!$request->has('simple') || !$request->simple){
                return redirect()->back()->with('warning', 'Please select at least one product before delete.');
            }

            $activity = ActivityLogs::find($request->simple);

            if($activity->delete()){
                return redirect()->back()->with('success', 'Selected items deleted successfully!');
            }
        }

        if($type == 'all'){
            ActivityLogs::truncate();
            return redirect()->back()->with('success', 'All items deleted successfully!');
        }



        return redirect()->back()->with('warning', 'Something is wrong!');

    }

    public function getDetails($id)
    {
        $activity = ActivityLogs::with(['user' => function($q){
            $q->with('role');
        }])->find($id);
        return view('admin.activity_logs.modal_content.details')->with(compact('activity'));
    }

    function csv(){

        $table = ActivityLogs::with(['user' => function($q){
            $q->with(['role' => function($query){
                $query->first();
            }]);
        }])
        ->get();
error_reporting(0);
        $filename = "administrators.csv";
        $handle = fopen($filename, 'w+');
        fputcsv($handle, array('name','date', 'type', 'ip', 'action', 'activity'));

        foreach($table as $row) {
//echo '<pre>'.$row->user->role[0]->role;print_r($row);exit;
$data = array();
if(!empty($row->user->name))
	$data[] = $row->user->name;
else
$data[] = '';
if(!empty($row->created_at))
	$data[] = $row->created_at;
else
$data[] = '';
if(!empty($row->user->role[0]->role))
	$data[] = ucfirst($row->user->role[0]->role);
else
$data[] = '';
if(!empty($row->ip))
	$data[] = $row->ip;
else
$data[] = '';
if(!empty($row->action))
	$data[] = $row->action;
else
$data[] = '';
if(!empty($row->activity))
	$data[] = $row->activity;
else
$data[] = '';
fputcsv($handle, $data);

            //fputcsv($handle, array($row->user->name, $row->created_at, ucfirst($row->user->role->first()->role), $row->ip, $row->action, $row->activity));
        }

        fclose($handle);

        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        );

        return Response::download($filename, $filename, $headers);
    }
}