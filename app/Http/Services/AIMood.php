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
    public function  selectDays($hourStart,$hourEnd,$dataStart,$dataEnd,$type,$start,$id,$dayInput = "") {
        $daystart = strtotime($dataStart) + ($start * 3600);
        $dayend = strtotime($dataEnd) + ($start * 3600);
        $days = [];
        $sumNer = 0;
        //print $hourStart;
        $sumAnxiety = 0;
        $sumMood = 0;
        $sumStimu = 0;
        $z = 1;
        $j = 0;
        for ($i = $daystart;$i <= $dayend;$i += 86400 ) {
            if (  Input::get("week") != "") {
                if (date('N', $i) != Input::get("week")) {
                
                    continue;
                   //print "s";
                }
            }
            //print date('N', $i)  ."<br>";
            $check = $this->check($hourStart,$hourEnd,date("Y-m-d H:i:s",$i),date("Y-m-d H:i:s",$i+86400),$start,$id);
           
            if ($check == false) {
                //print "d";
                //$days[0][$j]
                continue;
            }
            else {
                $days[0][$j] = $this->calculateAverage($hourStart,$hourEnd,date("Y-m-d H:i:s",$i),date("Y-m-d H:i:s",$i+86400),"mood",$start,$id);
                $days[1][$j] = $this->calculateAverage($hourStart,$hourEnd,date("Y-m-d H:i:s",$i),date("Y-m-d H:i:s",$i+86400),"anxiety",$start,$id);
                $days[2][$j] = $this->calculateAverage($hourStart,$hourEnd,date("Y-m-d H:i:s",$i),date("Y-m-d H:i:s",$i+86400),"ner",$start,$id);
                $days[3][$j] = $this->calculateAverage($hourStart,$hourEnd,date("Y-m-d H:i:s",$i),date("Y-m-d H:i:s",$i+86400),"stimulation",$start,$id);
                $tmp = $this->minMaxcalculate($hourStart,$hourEnd,date("Y-m-d H:i:s",$i),date("Y-m-d H:i:s",$i+86400),"stimulation",$start,$id);
                $days[4][$j] = $tmp[0];
                $days[5][$j] = $tmp[1];
                //$z++;
            }
            $sumMood += $days[0][$j];
            $sumAnxiety += $days[1][$j];
            $sumNer += $days[2][$j];
            $sumStimu += $days[3][$j];
           

            $this->days[$j] = date("Y-m-d",$i);
            $j++;
        }
        $minDay = min($days[4]);
        $maxDay = max($days[5]);
        if ($dayInput == "on") {
            if ($j == 0) {
                return 0;
            }
            return [round($sumMood / $j,2),
                round($this->sortMood((($days[0]) )) ,2)
                ,round($sumAnxiety / $j,2)
                ,round($this->sortMood((($days[1]) )),2)
                ,round($sumNer / $j,2)
                ,round($this->sortMood((($days[2]) )),2)
                ,round($sumStimu / $j,2)
                ,round($this->sortMood((($days[3]) )),2),
                $minDay,$maxDay];
            
        }
        
        //print round($this->sortMood((([0.1,0.2,0,0.3,-0.1,0.1,-0.1,0.2,0.1]) )),2);
        return $days;
    }
    private function check($hourStart,$hourEnd,$dataStart,$dataEnd,$start,$id) {
        $Moods = Moods::query();
        $idUsers = $id;
        $hour = $start;
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
        //$Moods->whereRaw("week(moods.date_start) = 0");
        if ($hourStart != "" and $hourEnd != "") {
           $Moods->whereRaw("(hour(time(date_start)) BETWEEN $hourStart AND $hourEnd or hour(time(date_end)) BETWEEN $hourStart AND $hourEnd or hour(time(date_start)) < '$hourStart' and hour(time(date_end)) > '$hourEnd')");
        }
        $list = $Moods->count();
        if ($list == 0) {
            return false;
        }
        else {
            return true;
        }
    }
    
    
    
    private function minMaxcalculate($hourStart,$hourEnd,$dataStart,$dataEnd,$type,$start,$id,$dayInput = "") {
        $idUsers = $id;
        $hour = $start;
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
        $Moods2 = Moods::query();
        $Moods2->select(DB::Raw("(DATE(IF(HOUR(date_start) >= '$hour', date_start,Date_add(date_start, INTERVAL - 1 DAY) )) ) as dat  "))
               //->selectRaw("date_start as date_start")
                //->selectRaw("date_end as date_end")
                //->selectRaw("level_anxiety as level_anxiety")
                //->selectRaw("level_nervousness as level_nervousness")
                //->selectRaw("level_stimulation as level_stimulation")
                //->selectRaw("level_mood as level_mood")
                ->selectRaw("MIN(level_mood) as min")
                ->selectRaw("MAX(level_mood) as max")
                //->selectRaw("level_mood as min")
                
                      ->where("id_users",$idUsers);
        if ($dataStart != "") {
            $Moods2->where("date_start",">=",$dataStart);
            $Moods2->where("date_start","<=",$dataEnd);
        }
        //$Moods2->whereRaw("week(moods.date_start) = 0");
        if ($hourStart != "" and $hourEnd != "") {
           $Moods2->whereRaw("(hour(time(date_start)) BETWEEN $hourStart AND $hourEnd or hour(time(date_end)) BETWEEN $hourStart AND $hourEnd or hour(time(date_start)) < '$hourStart' and hour(time(date_end)) > '$hourEnd')");
        }
        $list2 = $Moods2->first();
        return array($list2->min,$list2->max);
    }
    
    private function calculateAverage($hourStart,$hourEnd,$dataStart,$dataEnd,$type,$start,$id,$dayInput = "") {
        $Moods = Moods::query();
        $idUsers = $id;
        $hour = $start;
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
                //->selectRaw("level_mood as min")
                
                      ->where("id_users",$idUsers);
        if ($dataStart != "") {
            $Moods->where("date_start",">=",$dataStart);
            $Moods->where("date_start","<=",$dataEnd);
        }
        //$Moods->whereRaw("week(moods.date_start) = 0");
        if ($hourStart != "" and $hourEnd != "") {
           $Moods->whereRaw("(hour(time(date_start)) BETWEEN $hourStart AND $hourEnd or hour(time(date_end)) BETWEEN $hourStart AND $hourEnd or hour(time(date_start)) < '$hourStart' and hour(time(date_end)) > '$hourEnd')");
        }
        
        $list = $Moods->get();
        
        //$min = min($list);
        $i = 0;
        
        foreach ($list as $moodss) {
            //print $moodss->level_mood . "<br>";
            $time1 = strtotime($moodss->date_start);
            $time2 = strtotime($moodss->date_end);
             $divi1 = explode(" ",$moodss->date_start);
             $divi2 = explode(" ",$moodss->date_end);
                $dateComparate1 = $divi1[0] . " " . $hourStart . ":00:00";
                $dateComparate2 = $divi2[0] . " " . $hourEnd . ":00:00";
                $sumHour = (((($hourEnd) - ($hourStart)) *3600 )+ 3600);
                //print strtotime($dateComparate1) . "<br>";
            if ($time1 < strtotime($dateComparate1)) {
                $div = strtotime($dateComparate1);
                $div = $time1;
            }
            else {
                $div = $time1;
            }
            if ($time2 < strtotime($dateComparate2)) {
                $div2 = strtotime($dateComparate2);
                $div2 = $time2;
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
                if ($i == 0) {
            //return 200;
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


         
          if ($second == 0) {
              return 0;
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
 
    
    public function sortMoodOld($list) {

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
            $tmp2 += $tmp;
        }
        if (count($sort) == 0)  {
            return 0;
        }      
        return ((($tmp2 / count($sort)) * 5));
        
    }
    
    public function sortMood($list) {
        
        $tmp = 0;
        $tmp2 = 0;       
        for ($i=0;$i < count($list)-1;$i++) {
            //if (count($list)-1 == $i) {
                //break;
            //}
            
            //if ($list[$i+1] == 0) {
                //$tmp  =100;
            //}
             
            //else {
           
                $tmp = ((((($list[$i]) )) ) -  ((($list[$i+1] ) )  ));
            //}
            if ($tmp < 0) {
                $tmp = -$tmp;
            }
            $tmp2 += ($tmp  * 5);
        }
        $average = array_sum($list) / 5;
        if ($average == 0) {
            $average = 0.5;
        }
        if ((((($tmp2 / count($list))) ) ) < 0) {
            return -(((($tmp2 / count($list))) ));
        }
        return  ((($tmp2 / count($list))) );
    }
    
    public function standardDeviation($list) {
                    //$tablica = [0.2,0.1,0.1,0.1,0.1,0.1,0.2,0.2];
            $n=count($list);
            $average=0;

            for($i=0;$i<$n;$i++)
            {
                $average += $list[$i];
            }

            $average /= $n;

            for($i=0;$i<$n;$i++)
            {
                $standardDeviation=$list[$i]-$average; // tutaj do zmiennej $wyraz_srednia przypisujesz roznice danej liczby i sredniej wszystkich liczb

                $list2[$i]= $standardDeviation * $standardDeviation;
            }
            $result = 0;
            for($i=0;$i<$n;$i++)
            {
                $result+=$list2[$i]; //dostajesz sume tych wszystkich kwadratow roznicy wyrazu i sredniej;
            }
            return sqrt($result);
            //print $wynik;
    }
            
            

}