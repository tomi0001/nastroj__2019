<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Services\calendar as Calendar;
use App\Http\Services\mood as Mood;
use App\Http\Services\AIMood as AI;
use App\Http\Services\hashs as Hashs;
use Input;
use Auth;
use Hash;
use DB;
class ControllerHash {
    public function updateHash() {
        $hashs = new Hashs;
        $arrayHash = $hashs->checkHash(Auth::User()->id);
        if ($arrayHash == 0) {
            $hashs->createHash(Auth::User()->id);
        }
        else {
            $hashs->updateHash(Auth::User()->id);
        }
        $ifTrue = $hashs->selectHash(Auth::User()->id);
        return View("User.Setting")->with("succes","Hash zmieniony pomyślnie")
                ->with("startDay",Auth::User()->start_day)
                ->with("hash",$ifTrue->if_true)
                ->with("textHash",$ifTrue->hash);
        
    }
}
