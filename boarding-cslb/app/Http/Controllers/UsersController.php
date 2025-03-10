<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::latest();
        $users = $users->paginate(10)->appends(request()->all());
        return view('users/index',['users' => $users])->with('i', (request()->input('page', 1) - 1) * 10);
    }
    public function create(User $user)
    {
        return view('users/create');
    }
    public function edit(User $user)
    {
        return view('users/edit',['user' => $user]);
    }
    public function store(Request $request, User $user){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);
        $password = md5($request->password);
        $request->except(['password']);
        $request->request->add(['password' => $password]);
        $user->create($request->all());
        return back()->with('success','User created successfully');
    }
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'nullable|min:8'
        ]);

        $data = [
            'name' => $request->name, 
            'email' => $request->email
        ];
        if(!empty($request->password)) {
            $password = md5($request->password);
            $data['password'] = $password;
        }

        $user->update($data);
    
        return back()->with('success','User updated successfully');
    }
    public function delete_user(Request $request, User $user)
    {
        $user_id = $request->UserId;
        if($user_id != 1){
            $user_query = DB::table('users')->where('id', '=', $user_id)->delete();
            return response()->json(['delete'=> '1']);
        }else{
            return response()->json(['delete'=> '0']);
        }
    }
    public function create_group(Request $request) {
        $name_group = $_POST['name_group'];
        $list_user = $_POST['list_user'];
        $group = DB::table('group_users')->where('group_name', '=', $name_group)->first();
        if ($group === null) {
            DB::table('group_users')->insert([
                'group_name' => $name_group,
                'use_id' => $list_user,
            ]);   
            return response()->json(['create'=> '1']);
        } else { 
            return response()->json(['create'=> '0']);
        }
    }
    public function load_group_modal(Request $request) { 
        $current_group = $_POST['current_group'];
        $group_exist = DB::table('group_users')->where('group_id', $current_group)->value('use_id');
        $group_name = DB::table('group_users')->where('group_id', $current_group)->value('group_name');

        $current_user = explode(",", $group_exist);
        $arg_current =  array();
        foreach($current_user as $current) {
            if(!empty($current)) {
                $current_html = '<li>'.DB::table('users')->where('id', $current)->value('name').'<span class="remove-item" data-id="'.$current.'"><i class="far fa-times-circle"></i></span></li>';
                array_push($arg_current,$current_html);
            }        
        }
        $user_arg = array();
        foreach (DB::table('users')->get() as $user) {
            if(in_array($user->id, $current_user)) {
                $user_html = '<li data-id="'.$user->id.'" class="add-to-group" style="pointer-events: none; opacity: 0.4;" data-name="'.$user->name.'">'.$user->name.'</li>';
            } else {
                $user_html = '<li data-id="'.$user->id.'" class="add-to-group" data-name="'.$user->name.'">'.$user->name.'</li>';
            }
            array_push($user_arg,$user_html);
        }
        $html = '<div class="name-item">
                    <div class="group-item">
                        <div class="form-group input-group">
                            <span class="has-float-label">
                                <input type="text" class="form-control" id="group_name_edit" placeholder="Group Name:" value="'.$group_name.'" disabled>
                                <label for="group_name">Group Name:</label>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="user-item">
                    <div class="select-user">
                        <span class="label-select">Select User <i class="fas fa-caret-down"></i></span>
                        <ul class="list-user">'.implode("",$user_arg).'</ul>
                    </div>
                </div>
                <div class="participants-item">
                    <p class="modal-title mt-3 mb-2"><i class="fas fa-users"></i> <strong>Participants:</strong></p>
                    <ul class="list-participants">'.implode("",$arg_current).'</ul>
                    <input type="hidden" name="list_user" value="">
                </div>';

        return response()->json(['load_modal'=> '1', 'load_edit_group'=> $html]);
    }
    public function load_user_group(Request $request) { 
        $id_user = $_POST['id_user'];

        $user_arg = array();
        $current_user = explode(",", $id_user);        
        foreach (DB::table('users')->get() as $user) {
            if(in_array($user->id, $current_user)) {
                $user_html = '<li>
                    <label class="user-task">
                        <span class="name-user"">'.$user->name.'</span>
                        <input type="checkbox" name="user_id" data-task_id="'.$_POST['task_id'].'" data-group_id="'.$_POST['group_id'].'" data-group_name="'.$_POST['group_name'].'" value="'.$user->id.'">
                    </label> 
                </span>
            </li>';
            } else {
                break;
            }
            array_push($user_arg,$user_html);
        }
        if(!empty($user_arg)) {
            $html = '<ul class="inner">'.implode("",$user_arg).'</ul>';
        } else {
            $html = '<ul class="inner"><li><label class="user-task">Not found</label></li></ul>';
        }
        $group_selected = ' ';

        return response()->json(['load_user'=> '1', 'load_user_group'=> $html, 'load_group_selected'=> $group_selected]);
    }
}
