<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Auth;
use App\Http\Services\calendar as Calendar;
use App\Http\Services\mood as Mood;
use App\Http\Services\AIMood as AI;
use App\Http\Services\search as Search;
use App\Http\Services\common as Common;
use Illuminate\Support\Facades\Input as Input;
use Barryvdh\DomPDF\Facade as PDF;
class ControllerSearch extends BaseController
{
    public function main() {
        if ( (Auth::check()) ) {
            $Common = new Common;
            $year = $Common->selectFirstYear();
            
            return View("Search.main")->with("yearFrom",$year)
                    ->with("yearTo",date("Y"));
        }
    }
    
    public function searchAction() {
        if ( (Auth::check()) ) {
            $Mood = new Mood;
            $Search = new Search;
            if (empty(Input::get("page"))) {
                $page = 0;
            }
            else {
                $page = Input::get("page");
            }
            if (Input::get("type") == "mood") {
                $Search->createQuestion($page);
            }
            else {
                $Search->createQuestionForSleep($page);
            }
            
            $Search->sortMoods("off");
            
            return View("Search.action")->with("list",$Search->arrayList)
                    ->with("paginate",$Search->list)
                    ->with("percent",$Search->listPercent)
                    ->with("count",count($Search->list));

        }
        
    }
    public function savePDF() {
        $Search = new Search;
        if ( (Auth::check()) ) {
            $Search->selectPDF(Input::get("date_start"),Input::get("date_end"),Input::get("whatWork"),Input::get("drugs"));
            $Search->sortMoods(Input::get("whatWork"),true);
            $pdf = PDF::loadView('PDF.File',['list' => $Search->arrayList]);
            return $pdf->download("moods_" . Input::get("date_start") . " - " .  Input::get("date_end") . ".pdf");
        }
    }
    
    public function searchAI() {
        $AI = new AI;
        if ( (Auth::check()) ) {
            $list = $AI->selectDays(Input::get("hourFrom"), Input::get("hourTo"), 
                    Input::get("yearFrom") . "-" . Input::get("monthFrom")  . "-" . Input::get("dayFrom"), 
                    Input::get("yearTo") . "-" . Input::get("monthTo")  . "-" . Input::get("dayTo"),
                    Input::get("type"),Input::get("day"));
            //print ("<pre>");
            //print_r ($list);
  //          print ("<pre>");
            
            //var_dump($list);
            
//print_r($list);
            //$a = $AI->sortMood([0.1,1,0.1,1]);
            //var_dump($a);
            return View("Ajax.showAverage")->with("days",$AI->days)->with("list",$list)
                   ->with("day",Input::get("day"))->with("harmonyMood",$AI->tableMood)->with("harmonyAnxiety",$AI->tableAnxiety)
                    ->with("harmonyNer",$AI->tableNer)->with("harmonyStimu",$AI->tableStimu);
        }
    }
}