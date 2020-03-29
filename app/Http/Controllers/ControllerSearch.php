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
                $Search->createQuestion($page,Auth::User()->start_day,Auth::User()->id);
                $Search->sortMoods("off");
            
            return View("Search.action")->with("list",$Search->arrayList)
                    ->with("paginate",$Search->list)
                    ->with("percent",$Search->listPercent)
                    ->with("count",count($Search->list));
            }
            else if (Input::get("average") == "on") {
                $Search->averageForSleep(Auth::User()->start_day,Auth::User()->id);
                $Search->averageForSleepCount(Auth::User()->start_day,Auth::User()->id);
                if ($Search->count == 0) {
                    $Search->count = 1;
                }
                return View("Search.average")->with("list",($Mood->changeSecondAtHour(($Search->list->result  / $Search->count / 3600))));
                        //->with("count",$Search->count);
                    //->with("paginate",$Search->list)
                    //->with("percent",$Search->listPercent)
                    //->with("count",count($Search->list));
            }
            else {
                $Search->createQuestionForSleep($page,Auth::User()->start_day,Auth::User()->id);
                $Search->sortMoods("off");
            
            return View("Search.action")->with("list",$Search->arrayList)
                    ->with("paginate",$Search->list)
                    ->with("percent",$Search->listPercent)
                    ->with("count",count($Search->list));
                
            }
            
            

        }
        
    }
    public function searchMood() {
        $Search = new Search;
        if ( (Auth::check()) ) {
            $sum = $Search->sumMood();
            return View("Search.sumMood")->with("sum",round($sum->sum,2))
                    ->with("dateFrom",Input::get("dateFrom"))->with("dateTo",Input::get("dateTo"))
                    ->with("moodFrom",Input::get("moodFrom"))->with("moodTo",Input::get("moodTo"))
                    ->with("anxietyFrom",Input::get("anxietyFrom"))->with("anxietyTo",Input::get("anxietyTo"))
                    ->with("nerwoFrom",Input::get("nerwoFrom"))->with("nerwoTo",Input::get("nerwoTo"))
                    ->with("stimulationFrom",Input::get("stimulationFrom"))->with("stimulationTo",Input::get("stimulationTo"));
        }
    }
    public function savePDF() {
        $Search = new Search;
        if ( (Auth::check()) ) {
            $Search->selectPDF(Auth::User()->start_day,Auth::User()->id,Input::get("date_start"),Input::get("date_end"),Input::get("whatWork"),Input::get("drugs"));
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
                    Input::get("type"),Auth::User()->start_day,Auth::User()->id,Input::get("day"));
            //print ("<pre>");
            //print_r ($list);
  //          print ("<pre>");
            
            //var_dump($list);
            
//print_r($list);
            //$a = $AI->sortMood([0.1,1,0.1,1]);
            //var_dump($a);
            return View("Ajax.showAverage")->with("days",$AI->days)->with("list",$list)
                   ->with("day",Input::get("day"))->with("harmonyMood",$AI->tableMood)->with("harmonyAnxiety",$AI->tableAnxiety)
                    ->with("harmonyNer",$AI->tableNer)->with("harmonyStimu",$AI->tableStimu)->with("hour","Godzina od " . Input::get("hourFrom") . " do "  .  Input::get("hourTo"));
        }
    }
}