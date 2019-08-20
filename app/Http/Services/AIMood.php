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
use App\Mood as Moods;
use App\Sleep as Sleep;
use App\Http\Services\drugs as Drugs;
use App\Http\Services\common as Common;
use App\Http\Services\calendar as kalendar;


class AIMood extends mood {
    public $arrayAI = [];
    public $z = 0;
    public $j = 0;
    public $second = 0;
    public $d = 0;
    public $days = [];
    public $harmony = [];
    public $tableMood = [];
    public $tableAnxiety = [];
    public $tableStimu = [];
    public $tableNer = [];
    public function  selectDays($hourStart,$hourEnd,$dataStart,$dataEnd,$type,$dayInput = "") {
        $daystart = strtotime($dataStart) + (Auth::User()->start_day * 3600);
        $dayend = strtotime($dataEnd) + (Auth::User()->start_day * 3600);
        $days = [];
        $sumNer = 0;
        $sumAnxiety = 0;
        $sumMood = 0;
        $sumStimu = 0;
        
        $j = 0;
        for ($i = $daystart;$i <= $dayend;$i += 86400 ) {
            $days[0][$j] = $this->calculateAverage($hourStart,$hourEnd,date("Y-m-d H:i:s",$i),date("Y-m-d H:i:s",$i+86400),"mood");
            $days[1][$j] = $this->calculateAverage($hourStart,$hourEnd,date("Y-m-d H:i:s",$i),date("Y-m-d H:i:s",$i+86400),"anxiety");
            $days[2][$j] = $this->calculateAverage($hourStart,$hourEnd,date("Y-m-d H:i:s",$i),date("Y-m-d H:i:s",$i+86400),"ner");
            $days[3][$j] = $this->calculateAverage($hourStart,$hourEnd,date("Y-m-d H:i:s",$i),date("Y-m-d H:i:s",$i+86400),"stimulation");
            $sumMood += $days[0][$j];
            $sumAnxiety += $days[1][$j];
            $sumNer += $days[2][$j];
            $sumStimu += $days[3][$j];
            if ($days[0][$j] == null) {
              //  continue;
            }
            $this->days[$j] = date("Y-m-d",$i);
            $j++;
        }
        if ($dayInput == "on") {
            if ($j == 0) {
                return 0;
            }
            return [round($sumMood / $j,2),
                round($this->sortMood((($days[0]) )),2)
                ,round($sumAnxiety / $j,2)
                ,round($this->sortMood((($days[1]) )),2)
                ,round($sumNer / $j,2)
                ,round($this->sortMood((($days[2]) )),2)
                ,round($sumStimu / $j,2)
                ,round($this->sortMood((($days[3]) )),2)];
            
        }
        return $days;
    }
    private function calculateAverage($hourStart,$hourEnd,$dataStart,$dataEnd,$type,$dayInput = "") {
        $Moods = Moods::query();
        $idUsers = Auth::User()->id;
        $hour = Auth::User()->start_day;
        $average = 0;
        $second = 0;
        $sumMood = 0;
        $sumAnxiety = 0;
        $sumNer = 0;
        $sumStimu = 0;
        $harmonyMood = [];
        $harmonyStimu = [];
        $harmonyNer = [];
        $harmonyAnxiety = [];

        $Moods->select(DB::Raw("(DATE(IF(HOUR(date_start) >= '$hour', date_start,Date_add(date_start, INTERVAL - 1 DAY) )) ) as dat  "))
               ->selectRaw("date_start as date_start")
                ->selectRaw("date_end as date_end")
                ->selectRaw("level_anxiety as level_anxiety")
                ->selectRaw("level_nervousness as level_nervousness")
                ->selectRaw("level_stimulation as level_stimulation")
                ->selectRaw("level_mood as level_mood")
                
                      ->where("id_users",$idUsers);
        if ($dataStart != "") {
            $Moods->where("date_start",">=",$dataStart);
            $Moods->where("date_start","<=",$dataEnd);
        }
        if ($hourStart != "" and $hourEnd != "") {
           $Moods->whereRaw("(hour(time(date_start)) BETWEEN $hourStart AND $hourEnd or hour(time(date_end)) BETWEEN $hourStart AND $hourEnd or hour(time(date_start)) < '$hourStart' and hour(time(date_end)) > '$hourEnd')");
        }
        $list = $Moods->get();

        $i = 0;
        foreach ($list as $moodss) {

            $time1 = strtotime($moodss->date_start);
            $time2 = strtotime($moodss->date_end);
             $divi1 = explode(" ",$moodss->date_start);
             $divi2 = explode(" ",$moodss->date_end);
                $dateComparate1 = $divi1[0] . " " . $hourStart . ":00:00";
                $dateComparate2 = $divi2[0] . " " . $hourEnd . ":00:00";
                $sumHour = (((($hourEnd) - ($hourStart)) *3600 )+ 3600);

            if ($time1 < $dateComparate1) {
                $div = $dateComparate1;
            }
            else {
                $div = $time1;
            }
            if ($time2 < $dateComparate2) {
                $div2 = $dateComparate2;
            }
            else {
                $div2 = $time2;
            }

                    $sumAnxiety += ($div2 - $div) * $moodss->level_anxiety;
                    $harmonyAnxiety[$i] = $moodss->level_anxiety;
                    

                    $sumNer += ($div2 - $div) * $moodss->level_nervousness;
                    $harmonyNer[$i] =  $moodss->level_nervousness;

                    $sumStimu += ($div2 - $div) *  $moodss->level_stimulation;
                    $harmonyStimu[$i] = $moodss->level_stimulation;

                    $sumMood += ($div2 - $div) * $moodss->level_mood;
                    $harmonyMood[$i] =  $moodss->level_mood;
                
            $second += $div2 - $div;

            $i++;
        }
                 if ($type == "anxiety") {
        array_push($this->tableAnxiety,round(($this->sortMood($harmonyAnxiety) ),2));
         }
         else if ($type=="ner") {
        array_push($this->tableNer,round(($this->sortMood($harmonyNer) ),2));
         }
         else if ($type=="stimulation") {
        array_push($this->tableStimu,round(($this->sortMood($harmonyStimu) ),2));
         }
         else {
        array_push($this->tableMood,round(($this->sortMood($harmonyMood) ),2));
        }
        if ($i == 0) {
            return null;
        }

         
          
         

        if ($type == "mood") {
            return round($sumMood  / $second,2);
        }
        else if ($type=="anxiety") {
            return round($sumAnxiety  / $second,2);
        }
        else if ($type=="ner") {
            return round($sumNer  / $second,2);
        }
        else  {
            return round($sumStimu  / $second,2);
        }

    }
 
    
    public function sortMood($list) {

        $sort = $list;
        if (count($sort) % 2 == 1) {
            $average = array_sum($sort)/count($sort);
            array_push($sort, $average);
        }
        asort($sort);
        $count = count($sort)-1;
        $tmp = 0;
        $tmp2 = 0;

        for ($i=0;$i < count($sort) / 2;$i++) {
            $tmp = $sort[$count] - $sort[$i];

            //}
            $count--;
            if ($tmp < 0) {
                $tmp = -$tmp;
            }
            $tmp2 += $tmp / $percent;
        }
        if (count($sort) == 0)  {
            return 0;
        }      
        return ((($tmp2 / count($sort)) * 5));
        
    }

}