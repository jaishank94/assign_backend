<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $status_code    =        200;

    public function userSignUp(Request $request) {
        $validator              =        Validator::make($request->all(), [
            "name"              =>          "required|max:30",
            "email"             =>          "required|email",
            "username"          =>          "required|max:30",
            "password"          =>          "required|max:20"
        ]);

        if($validator->fails()) {
            return response()->json(["status" => "failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }

        $name                   =       $request->name;

        $userDataArray          =       array(
            "name"              =>          $request->name,
            "email"             =>          $request->email,
            "username"          =>          $request->username,
            "password"          =>          md5($request->password)
        );

        $user_email_exists           =           User::where("email", $request->email)->first();
        $user_username_exists            =           User::where("username", $request->username)->first();

        if(!is_null($user_email_exists) || !is_null($user_username_exists)) {
           return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! Email or username already registered"]);
        }

        $user                   =           User::create($userDataArray);

        if(!is_null($user)) {
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "Registration completed successfully", "data" => $user]);
        }

        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "failed to register"]);
        }
    }


    // ------------ [ User Login ] -------------------
    public function userLogin(Request $request) {

        $validator          =       Validator::make($request->all(),
            [
                "email"             =>          "required|email",
                "password"          =>          "required|max:30"
            ]
        );

        if($validator->fails()) {
            return response()->json(["status" => "failed", "validation_error" => $validator->errors()]);
        }


        // check if entered email exists in db
        $email_status       =       User::where("email", $request->email)->first();


        // if email exists then we will check password for the same email

        if(!is_null($email_status)) {
            $password_status    =   User::where("email", $request->email)->where("password", md5($request->password))->first();

            // if password is correct
            if(!is_null($password_status)) {
                $user           =       $this->userDetail($request->email);

                return response()->json(["status" => $this->status_code, "success" => true, "message" => "You have logged in successfully", "data" => $user]);
            }

            else {
                return response()->json(["status" => "failed", "success" => false, "message" => "Unable to login. Incorrect password."]);
            }
        }

        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Unable to login. Email doesn't exist."]);
        }
    }

    // ------------------ [ User Detail ] ---------------------
    public function userDetails($userId) {
        $user               =       array();
        if($userId != "") {
            $user           =       User::where("id", $userId)->first();
            return $user;
        }
    }

    // ------------------ [ User List ] ---------------------
    public function userList() {
        $user               =       array();
            $user           =       User::all();
            return $user;
    }

    // ------------------ [ User List With Role ] ---------------------
    public function userListWithRole($roleId) {
        $user               =       array();
        if($roleId != "") {
            $user           =       User::where("roleId", $roleId)->all();
            return $user;
        }
    }

    public function update(Request $request, $id){

        $validator              =        Validator::make($request->all(), [
            "name"              =>          "required"
        ]);

        if($validator->fails()) {
            return response()->json(["status" => "failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }


        $reqdata = $request->all();

 
        $updatedUser= User::where('id',$id)->update($reqdata);
 
         if ($updatedUser) {
 
             return 'true';
 
         }else{
 
             return 'false';
 
         }
 
     }  

     public function destroy($id)
    {
        //
        return User::destroy($id);
    }
}
