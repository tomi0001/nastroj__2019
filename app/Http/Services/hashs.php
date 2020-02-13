<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Services;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\User as User;
use App\Hash as Hash;
use DB;

use Illuminate\Support\Facades\Input as Input;
use Auth;
use Cookie;

class hashs {
    public $hash = '';
    public $id = '';
    public $start = '';
    public function checkHash($id) {
        $Hash = new Hash;
        $select = $Hash->where("id_users",$id)->count();
        return $select;
    }
    public function checkHashDr() {
        $Hash = new Hash;
        $select = $Hash->join("users","users.id","hashes.id_users")->where("hashes.hash",Input::get("hash"))
                        ->where("users.login",Input::get("login"))->count();
        if ($select == 0) {
            return false;
        }
        else {
            return true;
        }
    }
    
    public function checkHashLogin() {
        $Hash = new Hash;
//        print $_COOKIE['hash'];
        $select = $Hash->where("hash",Cookie::get('hash'))
                        ->where("id_users",Cookie::get('id'))
                        ->where("if_true",true)->count();
        if ($select == 0) {
            return false;
        }
        else {
            $this->id = Cookie::get('id');
            $this->start = Cookie::get('start');
            return true;
        }
        
    }
    public function selectData2() {
        $Hash = new Hash;
        $select = $Hash->join("users","users.id","hashes.id_users")
                        ->selectRaw("users.id as id")
                        ->selectRaw("hashes.hash as hash")
                        ->selectRaw("users.start_day as start")
                        ->where("hashes.hash",Cookie::get('hash'))
                        ->where("hashes.if_true",true)
                        ->where("users.id",Cookie::get("id"))->first();
        if (empty($select)) {
            return false;
        }
        return array($select->id,$select->hash,$select->start); 
    }
    public function selectData() {
        $Hash = new Hash;
        $select = $Hash->join("users","users.id","hashes.id_users")
                        ->selectRaw("users.id as id")
                        ->selectRaw("hashes.hash as hash")
                        ->selectRaw("users.start_day as start")
                        ->where("hashes.hash",Input::get("hash"))
                        ->where("users.login",Input::get("login"))
                        ->where("hashes.if_true",true)->first();
        if (empty($select)) {
            return false;
        }
        return array($select->id,$select->hash,$select->start);
    }
    public function selectHash($id) {
        $Hash = new Hash;
        $select = $Hash->where("id_users",$id)->first();
        if (empty($select) ) {
            return null;
        }
        return $select;
    }
    public function createHash($id) {
        $Hash = new Hash;
        $Hash->id_users = $id;
        $Hash->if_true = Input::get("ifHash");
        $Hash->hash = Input::get("nameHash");
        $Hash->save();
        
    }
    public function updateHash($id) {
        //print Input::get("hash");
        $Hash = new Hash;
        $Hash->where("id_users",$id)->update(["if_true" => Input::get("ifHash"),"hash" => Input::get("nameHash")]);
    }
    
    //put your code here
}
