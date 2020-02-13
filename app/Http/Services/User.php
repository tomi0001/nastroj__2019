<?php

namespace App\Http\Services;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input as Input;
use Auth;

use Illuminate\Support\Facades\Password as Password;
use Hash;
use DB;
use App\User as Users;
use App\Hash as Hash2;
use Cookie;
class User {
   public $errors = [];
   public function checkField()   {
       $User = new Users;
       $ifLogin = $User->where("login",Input::get("login"))->first();
       if (Input::get("login") == "") {
           array_push($this->errors, "Login nie może być pusty");
       }
       if (count($ifLogin) != 0 ) {
           array_push($this->errors, "Jest juz taki login wpisz inny");
       }
       
       if (((int)Input::get("start_day") != Input::get("start_day")
               or (Input::get("start_day") < 0 or Input::get("start_day") > 23 )) and Input::get("start_day") != "") {
           array_push($this->errors, "Godzina zaczęcia dnia musi być wartością od 0 do 23");
       }
       $this->checkPassword(Input::get("password"),Input::get("password_confirm"));
       
       
   }
   /*
   public function checkHash($idUsers) {
       $Hash = new Hash2;
       $ifTrue = $Hash->where("id_users",$idUsers)->first();
       if (empty($ifTrue)) {
           return 0;
       }
       else {
        return $ifTrue->if_true;
       }
   }
    * 
    */
   public function checkPasswordForm() {
       if (strlen(Input::get("passwordNew")) < 5 or strlen(Input::get("passwordNewConfirm")) < 5) {
           array_push($this->errors, "Hasła muszą mieć minimum 5 znaków długości");
       }
       if (!Hash::check(Input::get("password"), Auth::User()->password)) {
            array_push($this->errors, "Wpisałęś stare złe hasło");
        }
       if (Input::get("passwordNew") != Input::get("passwordNewConfirm")) {
           array_push($this->errors, "Podane hasła się różnią");
       }
   }
   public function updatePassword($password) {
         $Users = new Users;
         $Users->where("id",Auth::User()->id)
                ->update(['password'=>Hash::make($password)]);
   }
   private function checkIsGoodPassword($password) {
       $User = new Users;
       $is = $User->where("password",Hash::make($password))->count();
       if ($is > 0) {
           return true;
       }
       else {
           return false;
       }
   }
   private function checkPassword($password,$password_confirm) {
       if (strlen($password) < 5) {
           array_push($this->errors, "Podane hasło musi mieć minumum 5 znaków");
       }
       if ($password != $password_confirm) {
           array_push($this->errors, "Podane hasła się różnią");
       }
   }
   public function add() {
       $Users = new Users;
       $Users->login = Input::get("login");
       $Users->password = Hash::make(Input::get("password"));
       $Users->start_day = Input::get("start_day");
       $Users->save();
   }
   public function changeHour() {
       $Users = new Users;
       $Users->where("id",Auth::User()->id)
                ->update(['start_day'=>Input::get("hourStart")]);
   }
   public function checHour() {
       
       if (Input::get("hourStart") < 0  or Input::get("hourStart") > 23) {
              array_push($this->errors, "Podana liczba musi być w przedziale od 0 do 23");
       }
   }
   public function updateHash() {
        $Hash = new Hashs;
        $data = $Hash->selectData2();
        Cookie::queue(Cookie::make('id', $data[0], 60));
        Cookie::queue(Cookie::make('hash', $data[1], 60));
        Cookie::queue(Cookie::make('start', $data[2], 60));
    }
}