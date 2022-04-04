<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\Checklist;
use Session;

class CheckListController extends Controller
{
    public function index(){
       
        $data = [];

        $current_id = Auth::id();
        if($current_id == 1) {
            $list_data = Checklist::all();
        } else {
            $list_data = Checklist::where('user_id', $current_id)->get();
        }
        
        if(!empty($list_data)) {
            foreach($list_data as $item) {
                $data[] = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'full_name' => Checklist::full_name($item['user_id']),
                    'created_at' => $item['created_at'],
                ];
            }   
        }

        return view('online-checklist/index', ['current_id' => $current_id, 'data' => $data]);
    }

    public function create(){
        return view('online-checklist/create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
       
        $validator = Validator::make($request->all(), [
            'floatingName' => 'required',
            'floatingSelect' => 'required',
            'manager_id' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();
            return redirect('boarding-unterlagen')->withErrors($messages);
        } else {

            $name = $request->input('floatingName');
            $user_id = $request->input('manager_id');
            $position_id = $request->input('floatingSelect');

            $data = [
                'name' => $name,
                'user_id' => $user_id,
                'position_id' => $position_id,
            ];
            
            if ($request->has('status')) {
                $status = $request->input('status');
                $data['status'] = $status;
            }

            if ($request->has('category_date_detail')) {
                $category_date_detail = $request->input('category_date_detail');
                $data['category_date_detail'] = json_encode($category_date_detail);
            }

            if ($request->has('category_sub_detail')) {
                $category_sub_detail = $request->input('category_sub_detail');
                $data['category_sub_detail'] = json_encode($category_sub_detail);
            }

            if ($request->has('task_detail')) {
                $task_detail = [];
                $request->collect('task_detail')->each(function ($task) use(&$task_detail) {
                    if(isset($task["checkbox"]) && !empty($task["checkbox"])) {
                        $task_detail[$task["task_id"]] = $task;
                    }
                });
                $data['task_detail'] = json_encode($task_detail);
            }

            if ($request->has('note_detail')) {
                $note_detail = $request->input('note_detail');
                $data['note_detail'] = json_encode($note_detail);
            }

            if ($request->has('signature_detail')) {
                $signature_detail = $request->input('signature_detail');
                $data['signature_detail'] = json_encode($signature_detail);
            }

            $create = Checklist::create($data);
            if($create) {
                $msg_create = [
                    'msg' => 'Create success!',
                    'code' => 200
                ];
            } else {
                $msg_create = [
                    'msg' => 'Create failure!',
                    'code' => 400
                ];
            }
            
            return redirect('boarding-unterlagen')->with('msg_create', $msg_create);
        }
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
       
        $validator = Validator::make($request->all(), [
            'floatingName' => 'required',
            'floatingSelect' => 'required',
            'manager_id' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();
            return redirect('boarding-unterlagen')->withErrors($messages);
        } else {

            $name = $request->input('floatingName');
            $user_id = $request->input('manager_id');
            $position_id = $request->input('floatingSelect');

            $data = [
                'name' => $name,
                'user_id' => $user_id,
                'position_id' => $position_id,
            ];
            
            if ($request->has('status')) {
                $status = $request->input('status');
                $data['status'] = $status;
            }

            if ($request->has('category_date_detail')) {
                $category_date_detail = $request->input('category_date_detail');
                $data['category_date_detail'] = json_encode($category_date_detail);
            }

            if ($request->has('category_sub_detail')) {
                $category_sub_detail = $request->input('category_sub_detail');
                $data['category_sub_detail'] = json_encode($category_sub_detail);
            }

            if ($request->has('task_detail')) {
                $task_detail = [];
                
                $request->collect('task_detail')->each(function ($task) use(&$task_detail) {
                    if(isset($task["checkbox"]) && !empty($task["checkbox"])) {
                        $task_detail[$task["task_id"]] = $task;
                    }
                });
                $data['task_detail'] = json_encode($task_detail);
            }

            if ($request->has('note_detail')) {
                $note_detail = $request->input('note_detail');
                $data['note_detail'] = json_encode($note_detail);
            }

            if ($request->has('signature_detail')) {
                $signature_detail = $request->input('signature_detail');
                $data['signature_detail'] = json_encode($signature_detail);
            }
  
            $update = Checklist::where(['id' => $id])->update($data);
            if($update) {
                $msg_update = [
                    'msg' => 'Update success!',
                    'code' => 200
                ];
            } else {
                $msg_update = [
                    'msg' => 'Update failure!',
                    'code' => 400
                ];
            }
            
            return redirect('boarding-unterlagen')->with('msg_update', $msg_update);
        }
    }

    public function destroy(Request $request, $id)
    {

    }

    public function edit($id)
    {
        if(!empty($id)) {

            $current_id = Auth::id();

            $user_info = DB::table('users')->where('id', $current_id)->select('abbreviations', 'full_name')->first();

            $checklist = Checklist::where('id', $id)->first();

            $list_category = DB::table('category')->get();
            $list_tab_form = DB::table('tab_form')->get();
            $list_position = DB::table('position')->get();

            $list_task = [];
            $tasks = DB::table('list_task')->get();
            if(!empty($tasks)){
                foreach($tasks as $task){
                    $list_task[$task->category_id][] = [
                        'id' => $task->id,
                        'name' => $task->name,
                        'category_id' => $task->category_id,
                    ];
                }
            }

            $group_users_task = [];
            $task_detail = json_decode($checklist->task_detail, true);
            if($task_detail) {
                foreach($task_detail as $key => $task){
                    $list_ids = explode(',', $task["ids"]);
                    
                    foreach($list_ids as $user_id_task) {
                        $first_user_info = DB::table('users')->where('id', $user_id_task)->select('full_name', 'group_id')->first();
                        if(isset($first_user_info->group_id)){
                            $user_group_id = $first_user_info->group_id;
                            $first_user_group = DB::table('group_users')->where('group_id', $user_group_id)->first();
                            if(!empty($first_user_info) && !empty($first_user_group)) {
                                $group_users_task[$key][] = [
                                    'sub_user_id' => $user_id_task,
                                    'sub_full_name' => $first_user_info->full_name,
                                    'sub_group_id' => $first_user_group->group_id ? $first_user_group->group_id : '',
                                    'sub_group_name' => $first_user_group->group_name ? $first_user_group->group_name : '',
                                ];
                            }
                        } 
                    }
                }
            }
            
            $list_group_users = [];
            $group_users = DB::table('group_users')->get();
            if(!empty($group_users)){
                foreach($group_users as $group_user){
                    $list_group_users[$group_user->group_id] = [
                        'id' => $group_user->group_id,
                        'name' => $group_user->group_name,
                    ];

                    $user_id_group = DB::table('users')->where('group_id', $group_user->group_id)->get();
                    if($user_id_group) {
                        foreach($user_id_group as $user_id){
                            $list_group_users[$group_user->group_id]['users'][] = $user_id->id;
                        }    
                    }
                    
                }
            }
            
            
            $data = [
                'current_id' => $current_id, 
                'form_id' => $id,
                'name_checklist' => $checklist->name,
                'abbreviations' => $user_info->abbreviations,
                'full_name_checklist' => Checklist::full_name($checklist->user_id),
                'manager_id' => $checklist->user_id,
                'list_category' => $list_category,
                'list_tab_form' => $list_tab_form,
                'list_position' => $list_position,
                'list_task' => $list_task,
                'list_group_users' => $list_group_users,
                'group_users_task' => $group_users_task,
                'checklist' => $checklist,
            ];

            return view('online-checklist/edit', $data);
        } else {
            return redirect('boarding-unterlagen')->with('msg_edit', 'Cannot be edited, please check again.');
        }
    }
}