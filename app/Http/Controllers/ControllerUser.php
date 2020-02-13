<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Services\User as User;
use App\Http\Services\hashs as Hash2;
use Illuminate\Support\Facades\Input as Input;
use Illuminate\Support\Facades\Validator;
use Auth;
use Hash;
use DB;
class ControllerUser extends BaseController
{
    public function register() {
        return View("User.Register");
        
    }
    
    public function register_action() {
        $User = new User;
        $User->checkField();
        if (count($User->errors) != 0) {
            return back()->with("errors",$User->errors)->withInput();
        }
        else {
            $User->add();
            return Redirect("/User/Login")->with("succes","Poprownie się zarejestrowałeś możesz się zalogowac");
        }
        
    }
    public function login_action() {
            
        $user = array(
            "login" => Input::get("login"),
            "password" => Input::get("password")
            
        );
        if (Input::get('login') == "" or Input::get('password') == "" ) {
            return Redirect('/User/Login')->with('error','Uzupełnij pole login i hasło');
        }
        if (Auth::attempt($user) ) {
            return Redirect("/Main");
        }
        else {
            return Redirect('/User/Login')->with('error','Nie prawidłowy login lub hasło');
        }
        
    }
    public function logout_action() {
        Auth::logout();
        return Redirect('/User/Login')->with("succes","Wylogowałeś się pomyślnie");
    }
    public function login() {
        return View("User.Login");
    }
    public function Setting() {
        if ( (Auth::check()) ) {
            $Hash = new Hash2;
            $ifTrue = $Hash->selectHash(Auth::User()->id);
            if ($ifTrue == null) {
                $tmp1 = null;
                $tmp2 = null;
            }
            else {
                $tmp1 = $ifTrue->if_true;
                $tmp2 = $ifTrue->hash;
            }
            return View("/User/Setting")->with("startDay",Auth::User()->start_day)
                    ->with("hash",$tmp1)
                    ->with("textHash",$tmp2);
        }
        else {
            return Redirect('/User/Login')->with('error','Musiałeś się wylogować');
        }
    }
    public function changeHour() {
        $User = new User;
        if ( (Auth::check()) ) {
            $User->checHour();
            if (count($User->errors) != 0 ) {
                return Redirect('/User/Setting')->with("error",$User->errors);
            }
            else {
                $User->changeHour();
                return Redirect('/User/Setting')->with("succes","Pomyslnie zmodyfikowano dane");
            }
        }
    }
    public function changePassword() {
        $User = new User;
        if ( (Auth::check()) ) {
            $User->checkPasswordForm();
            if (count($User->errors) != 0 ) {
                return Redirect('/User/Setting')->with("error",$User->errors);
            }
            else {
                $User->updatePassword(Input::get("passwordNew"));
                return Redirect('/User/Setting')->with("succes","Pomyslnie zmodyfikowano dane");
            }
        }
    }
}