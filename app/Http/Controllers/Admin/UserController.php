<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Filelist;
use App\Payments;
use App\Pricing;
use App\User;
use App\Dataset;
use DB;
use Auth;
use Exception;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware(['auth','verified','admin']);
    }

    public function index() {
        $index = 'user';
        $title = 'User management';
        return view('admin.user', compact('index','title'));
    }

    public function getUserList() {
        $users = User::where('id','!=',Auth::user()->id)->get();

        $result = [];
        $result['data'] = [];
        $i = 0;
        foreach($users as $user) {
            $result['data'][$i][0] = $i+1;
            $result['data'][$i][1] = $user->f_name;
            $result['data'][$i][2] = $user->l_name;
            $result['data'][$i][3] = "<a href='mailto:".$user->email."' class='email'>".$user->email."</a>";
            
            try {
                if(null !== $user->pricing && 0 !== $user->pricing) {
                    if(null !== $user->processed) {
                        $result['data'][$i][4] = strtoupper($user->package->name);
                        $result['data'][$i][5] = $user->package->rows - $user->processed;
                    }
                    else {
                        $result['data'][$i][4] = strtoupper($user->package->name);
                        $result['data'][$i][5] = $user->package->rows - 0;
                    }
                }
                else {
                    $result['data'][$i][4] = 'NONE';
                    $result['data'][$i][5] = 0;
                }
            } catch (Exception $e) {
                $result['data'][$i][4] = 'NONE';
                $result['data'][$i][5] = 0;
            }
            
            $result['data'][$i][6] = $user->mobile;
            $result['data'][$i][7] = $user->birthday;
            $result['data'][$i][8] = $user->location;

            $result['data'][$i][9] = date($user->created_at);
            if($user->active) {
                $result['data'][$i][10] = "<span class='label label-success inactive'>Active</span>";
            }
            else {
                $result['data'][$i][10] = "<span class='label label-danger inactive'>Inactive</span>";
            }
            
            $i++;
        }

        return response()->json($result);
    }

    public function pre_edit(Request $request,$id) {
        $user = User::where('email','=',$id)->first();
        $packages = Pricing::where('active','=',1)->get();
        
        $index = 'user';
        $title = 'User management';

        return view('admin.pre_user_edit', compact('index','user','title','packages'));
    }

    public function edit(Request $request) {
        $user = User::where('email','=',$request->get('email'))->first();
        $user->f_name = $request->get('first_name');
        $user->l_name = $request->get('last_name');
        $user->birthday = $request->get('birth');
        $user->mobile = $request->get('mobile');
        $user->location = $request->get('location');
        $user->pricing = $request->get('package');
        $user->processed = $request->get('processed');

        $user->save();

        return back();
    }

    public function makeActive(Request $request) {
        $user = User::where('email','=',$request->get('user_id'))->first();
        $user->active = 1;
        $user->save();

        return "success";
    }

    public function makeInactive(Request $request) {
        $user = User::where('email','=',$request->get('user_id'))->first();
        $user->active = 0;
        $user->save();

        return "success";
    }

    public function delete(Request $request) {
        $id = User::where('email','=',$request->get('user_id'))->first()->id;
        Filelist::where('user_id','=',$id)->delete();
        Payments::where('user_id','=',$id)->delete();
        User::where('email','=',$request->get('user_id'))->delete();

        return "success";
    }
}
