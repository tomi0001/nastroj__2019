<?php
namespace App\Http\Controllers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Services\calendar;
//use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Services\User as User2;
use App\Http\Services\hashs as Hashs;
use Illuminate\Support\Facades\Input as Input;
use Auth;
use Hash;
use DB;
use Cookie;
//use Response;
use App\User as Users;
class ControllerDrUser {
    
    
    public function loginAction() {
        $Hash = new Hashs;
        $check = $Hash->checkHashDr();
        if (Input::get('login') == "" or Input::get('hash') == "" ) {
            return Redirect('/User/Login')->with('errorDr','Uzupełnij pole login i hasło');
        }
        else if ($check == false) {
            return Redirect('/User/Login')->with('errorDr','Nie prawidłowy login lub hasło');
        }
        else {
            $data = $Hash->selectData();
            //\Cookie::make('id', $data[0], 3600);
            //\Cookie::make('hash', $data[1], 3600);
            Cookie::queue(Cookie::make('id', $data[0], 60));
            Cookie::queue(Cookie::make('hash', $data[1], 60));
            Cookie::queue(Cookie::make('start', $data[2], 60));
            //setcookie("id", $data[0], time() + 3600);
            //setcookie("hash", $data[1], time() + 3600);
            return Redirect("/MainDr");
        }
    }
    
    public function logout_action() {
        Cookie::queue(Cookie::make('id', 0, 0));
        Cookie::queue(Cookie::make('hash', 0, 0));
        //Cookie::forget('id');
        //Cookie::forget('hash');
        return redirect("/User/Login")->with("errorDr","Wylogowałeś się");
    }

    
    //put your code here
}
