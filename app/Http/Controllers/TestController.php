<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use function Symfony\Component\String\u;
use function Symfony\Component\Translation\t;

class TestController extends Controller
{
    protected function imageUpload($request){
        $image = $request->file('image');
        $fName = time().'.'.$image->getClientOriginalExtension();
        $dir = './images/';
        $url = $dir.$fName;
        $image->move($dir,$fName);
        return $url;
    }
public function store(Request $request){
    $url = $this->imageUpload($request);

    $user = new User();
    $user->name = $request->name;
    $user->email  = $request->email;
    $user->mobile = $request->mobile;
    $user->image = $url;
    $user->save();
    $msg = "User info save successfully .";

    return json_encode($msg);

    }
    public function getAll(){
        $users = User::all();
        return json_encode($users);
    }
    public function edit($id){
        $user = User::find($id);
        return json_encode($user);
    }
    protected function updateUserInfo($request){
        $image = $request->hasFile('image');
        $user = User::find($request->id);
        if($image){
            if(file_exists($user->image)){
                unlink($user->image);
                $imgUrl = $this->imageUpload($request);
            }else{
                $imgUrl = $this->imageUpload($request);
            }

        }else{
            $imgUrl = $user->image;
        }
        $user->name = $request->name;
        $user->email  = $request->email;
        $user->mobile = $request->mobile;
        $user->image = $imgUrl;
        $user->save();

    }
    public function update(Request $request){
        $this->updateUserInfo($request);
        $msg = "User info Update successfully .";
        return json_encode($msg);
    }
    public function delete($id){
        $user = User::find($id);
        if(file_exists($user->image)){
            unlink($user->image);
        }
        $user->delete();
        $msg = "User info delete successfully .";
        return json_encode($msg);
    }
}
