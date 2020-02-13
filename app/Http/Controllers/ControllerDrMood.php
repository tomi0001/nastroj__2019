<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Auth;
use App\Http\Services\calendar as Calendar;
use App\Http\Services\mood as Mood;
use App\Http\Services\drugs as Drugs;
use Illuminate\Support\Facades\Input as Input;
use App\Http\Services\hashs as Hashs;
use App\Http\Services\User as user;
class ControllerDrMood extends BaseController
{


    
    public function showDescription() {
        $Hash = new Hashs();
        $user = new user();
        if ( ($Hash->checkHashLogin() == true) ) {
            $user->updateHash();
            $Mood = new Mood;
            $description = $Mood->showDescription($Hash->id);
            return View("Ajax.description")->with("description",$description);
        }
    }
    
    public function showDrugs() {
        $Hash = new Hashs();
        $user = new user();
        if ( ($Hash->checkHashLogin() == true) ) {
            $user->updateHash();
            $Mood = new Mood;
            $list = $Mood->selectDrugs(Input::get("id"),$Hash->id);
            return View("AjaxDr.drugsList")->with("drugs",$list);
        }
    }

    
}