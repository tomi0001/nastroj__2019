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
use App\Drug as Drug;
use App\Http\Services\mood as Mood;
use App\Http\Services\common as Common;
use App\Forwarding_drug as Forwarding_drug;
//class User
class search  {
    public $qestion;
    public $qestion2;
    public $list;
    public $arrayList;
    public $listPercent;
    public $listMoods;
    public $listSleep;
    public $count;
    public $errors = [];
    
    private function selectDate() {
        
    }
    public function sortMoods($what,$bool = false) {
        $Mood = new Mood;
        if ($bool == true) {
            $Mood->sortMoodsSleep($this->list,$this->listSleep,$what,true);
        }
        else if (Input::get("type") == "mood") {
            $Mood->sortMoodsSleep($this->list,[],$what,false);
        }
        else {
            $Mood->sortMoodsSleep([],$this->list,$what,false);
        }
        $this->arrayList = $Mood->arrayList;
        $this->listPercent = $Mood->listPercent;
        $this->selectDate();

    }
    public function selectPDF($start,$id,$dateStart,$dateEnd,$whatWork = true,$drugs = false) {
        $Mood = new Mood;
        $hourStart = explode(":",$start);
        $hour = $start;

        $this->qestion =  Moods::query();
        $this->qestion->select(DB::Raw("(DATE(IF(HOUR(moods.date_start) >= '$hour', moods.date_start,Date_add(moods.date_start, INTERVAL - 1 DAY) )) ) as dat  "));
        $this->qestion->selectRaw("hour(date_start) as hour");
        $this->qestion->selectRaw("level_mood as level_mood");
        $this->qestion->selectRaw("level_anxiety as level_anxiety");
        $this->qestion->selectRaw("level_nervousness as level_nervousness");
        $this->qestion->selectRaw("level_stimulation as level_stimulation");
        $this->qestion->selectRaw("epizodes_psychotik as epizodes_psychotik");
        $this->qestion->selectRaw("date_start as date_start");
        $this->qestion->selectRaw("date_end as date_end");
        $this->qestion->selectRaw("what_work as what_work");
        $this->qestion->selectRaw("moods.id as id");
        $this->qestion->selectRaw("forwarding_drugs.id_drugs as drugs");
        
        $this->qestion->selectRaw("year((DATE(IF(HOUR(moods.date_start) >= '$hour', moods.date_start,Date_add(moods.date_start, INTERVAL - 1 DAY) )) )) as year");
        $this->qestion->selectRaw("month((DATE(IF(HOUR(moods.date_start) >= '$hour', moods.date_start,Date_add(moods.date_start, INTERVAL - 1 DAY) )) )) as month");
        $this->qestion->selectRaw("day((DATE(IF(HOUR(moods.date_start) >= '$hour', moods.date_start,Date_add(moods.date_start, INTERVAL - 1 DAY) )) )) as day");
        $this->qestion->selectRaw("TIMESTAMPDIFF (SECOND, date_start , date_end) as divi");
        $this->qestion->selectRaw("hour(date_start) as hour");
        $this->qestion->selectRaw("year((DATE(IF(HOUR(moods.date_start) >= '$hour', moods.date_start,Date_add(moods.date_start, INTERVAL - 1 DAY) )) )) as year");
        $this->qestion->selectRaw("month((DATE(IF(HOUR(moods.date_start) >= '$hour', moods.date_start,Date_add(moods.date_start, INTERVAL - 1 DAY) )) )) as month");
        $this->qestion->selectRaw("day((DATE(IF(HOUR(moods.date_start) >= '$hour', moods.date_start,Date_add(moods.date_start, INTERVAL - 1 DAY) )) )) as day");
        $this->qestion->leftjoin("forwarding_drugs","forwarding_drugs.id_mood","moods.id");
        if ($drugs == true) {
            $this->qestion->leftjoin("drugs","drugs.id","forwarding_drugs.id_drugs");
        }
        if ($dateStart != "") {
            $this->qestion->where("date_start",">=",$dateStart);
        }
        if ($dateEnd != "") {
            $this->qestion->where("date_end","<=",$dateEnd);
        }
        $this->qestion->where("moods.id_users",$id);
        $this->list = $this->qestion->get();
        $this->listSleep = $Mood->selectSleep($start,$id,$dateStart,$dateEnd,true);
    }
    private function setHour() {
        $second1 = 0;
        if (Input::get("hour1") != "") {
            $second1 = (int) Input::get("hour1") * 3600;
        }
        if (Input::get("min1") != "") {
            $second1 += (int) Input::get("min1") * 60;
        }
        $second2 = 0;
        if (Input::get("hour2") != "") {
            $second2 = (int) Input::get("hour2") * 3600;
        }
        if (Input::get("min2") != "") {
            $second2 += (int) Input::get("min2") * 60;
        }
        return array($second1,$second2);
    }
    public function createQuestion($page,$start,$id) {
        
        $hourStart = explode(":",$start);
        $hour = $start;
        $this->qestion =  Moods::query();


        
        $this->qestion->select(DB::Raw("(DATE(IF(HOUR(moods.date_start) >= '$hour', moods.date_start,Date_add(moods.date_start, INTERVAL - 1 DAY) )) ) as dat  "));
        
        $this->qestion->selectRaw("hour(date_start) as hour");
        $this->qestion->selectRaw("level_mood as level_mood");
        $this->qestion->selectRaw("level_anxiety as level_anxiety");
        $this->qestion->selectRaw("level_nervousness as level_nervousness");
        $this->qestion->selectRaw("level_stimulation as level_stimulation");
        $this->qestion->selectRaw("epizodes_psychotik as epizodes_psychotik");
        $this->qestion->selectRaw("date_start as date_start");
        $this->qestion->selectRaw("date_end as date_end");
        $this->qestion->selectRaw("what_work as what_work");
        $this->qestion->selectRaw("moods.id as id");
        $this->qestion->selectRaw("forwarding_drugs.id_drugs as drugs");
        
        $this->qestion->selectRaw("year((DATE(IF(HOUR(moods.date_start) >= '$hour', moods.date_start,Date_add(moods.date_start, INTERVAL - 1 DAY) )) )) as year");
        $this->qestion->selectRaw("month((DATE(IF(HOUR(moods.date_start) >= '$hour', moods.date_start,Date_add(moods.date_start, INTERVAL - 1 DAY) )) )) as month");
        $this->qestion->selectRaw("day((DATE(IF(HOUR(moods.date_start) >= '$hour', moods.date_start,Date_add(moods.date_start, INTERVAL - 1 DAY) )) )) as day");
        $this->qestion->selectRaw("TIMESTAMPDIFF (SECOND, date_start , date_end) as divi");
        $this->qestion->selectRaw("hour(date_start) as hour");
        if (Input::get("moodForDay") != "") {
            
            $this->qestion->selectRaw("round(sum(TIMESTAMPDIFF (SECOND, moods.date_start , moods.date_end)  * moods.level_mood) / "
                   . "sum(TIMESTAMPDIFF(second,moods.date_start,moods.date_end)),2) as nas");
            $this->qestion->selectRaw("round(sum(TIMESTAMPDIFF (SECOND, date_start , date_end)  * level_anxiety) / "
                   . "sum(TIMESTAMPDIFF(second,date_start,date_end)),2) as nas2");
            $this->qestion->selectRaw("round(sum(TIMESTAMPDIFF (SECOND, date_start , date_end)  *  	level_nervousness ) / "
                   . "sum(TIMESTAMPDIFF(second,date_start,date_end)),2) as nas3");
            $this->qestion->selectRaw("round(sum(TIMESTAMPDIFF (SECOND, date_start , date_end)  * level_stimulation) / "
                   . "sum(TIMESTAMPDIFF(second,date_start,date_end)),2) as nas4");
        }
        //$this->qestion->selectRaw("CASE WHEN forwarding_drugs.id_mood = '' THEN    '0'    END  '1'  ");
        $this->qestion->leftjoin("forwarding_drugs","forwarding_drugs.id_mood","moods.id");
        if (Input::get("drugs") != "") {
            $this->qestion->leftjoin("drugs","drugs.id","forwarding_drugs.id_drugs");
        }
        //if (count()) {
        
            $this->setWhatWotk();
        //}
        $this->qestion->where("moods.id_users",$id);
        if (Input::get("moodForDay") != "") {
            $this->setGroup();
        }
        
        else {
            $this->setWhere();
            $this->setGroup2();
        }
         
        if (Input::get("drugs") != "") {
            $this->setDrugs();
        }
        $this->setDate();
        if (Input::get("moodForDay") != "") {
            $this->sortForDay($page);
        }
        else {
            $this->sort($page);
        }
        
        
        $this->list = $this->qestion->Paginate(15);
    }
    public function createQuestionForSleep($page,$start,$id) {
        
        $hourStart = explode(":",$start);
        $hour = $start;
        $this->qestion =  Sleep::query();


        
        $this->qestion->select(DB::Raw("(DATE(IF(HOUR(sleeps.date_start) >= '$hour', sleeps.date_start,Date_add(sleeps.date_start, INTERVAL - 1 DAY) )) ) as dat  "));
        $this->qestion->selectRaw("hour(date_start) as hour");
        $this->qestion->selectRaw("date_start as date_start");
        $this->qestion->selectRaw("date_end as date_end");
        
        $this->qestion->selectRaw("sleeps.id as id");

        
        $this->qestion->selectRaw("year((DATE(IF(HOUR(sleeps.date_start) >= '$hour', sleeps.date_start,Date_add(sleeps.date_start, INTERVAL - 1 DAY) )) )) as year");
        $this->qestion->selectRaw("month((DATE(IF(HOUR(sleeps.date_start) >= '$hour', sleeps.date_start,Date_add(sleeps.date_start, INTERVAL - 1 DAY) )) )) as month");
        $this->qestion->selectRaw("day((DATE(IF(HOUR(sleeps.date_start) >= '$hour', sleeps.date_start,Date_add(sleeps.date_start, INTERVAL - 1 DAY) )) )) as day");
        $this->qestion->selectRaw("TIMESTAMPDIFF (SECOND, date_start , date_end) as divi");
        $this->qestion->selectRaw("hour(date_start) as hour");


        $this->qestion->where("sleeps.id_users",$id);


            $this->setWhere();


        $this->setDate();
        $this->sort($page);
        
        
        $this->list = $this->qestion->Paginate(15);
        
    }
    public function averageForSleep($start,$id) {
        $hourStart = explode(":",$start);
        $hour = $start;
        $this->qestion =  Sleep::query();


        
        //$this->qestion->select(DB::Raw("(DATE(IF(HOUR(sleeps.date_start) >= '$hour', sleeps.date_start,Date_add(sleeps.date_start, INTERVAL - 1 DAY) )) ) as dat  "));
        $this->qestion->selectRaw("((sum(UNIX_TIMESTAMP(sleeps.date_end) - UNIX_TIMESTAMP(sleeps.date_start))) )  as result");
        
        $this->qestion->where("sleeps.id_users",$id);
        $this->setWhereCountSleep();
        $this->setDate();
        
        $this->list = $this->qestion->first();
    }
    private function setWhereCountSleep() {
        $this->qestion->whereRaw("((HOUR(sleeps.date_start) >= '20') or (HOUR(sleeps.date_start) <= '4')) and HOUR(sleeps.date_end) <= '14'");
        //$this->qestion->whereRaw("");
    }
    public function averageForSleepCount($start,$id) {
        $hourStart = explode(":",$start);
        $hour = $start;
        $this->qestion =  Sleep::query();


        
        //$this->qestion->select(DB::Raw("(DATE(IF(HOUR(sleeps.date_start) >= '$hour', sleeps.date_start,Date_add(sleeps.date_start, INTERVAL - 1 DAY) )) ) as dat  "));
        //$this->qestion->selectRaw("sum(((sleeps.date_end - sleeps.date_start)) / 3600)  as result");
        
        $this->qestion->where("sleeps.id_users",$id);
        $this->setWhereCountSleep();
        $this->setDate();
        
        $this->count = $this->qestion->count();
    }
    private function setDrugs() {
        $this->qestion->where("drugs.name", Input::get("drugs") );
    }
    private function sort($page) {
        if (Input::get("sort") == "date") {
            $this->qestion->orderBy("date_start","DESC");
        }
        else if (Input::get("sort") == "hour") {
            $this->qestion->orderBy("hour","DESC");
        }
        else if (Input::get("sort") == "mood") {
            $this->qestion->orderBy("level_mood","DESC");
        }
        else if (Input::get("sort") == "anxiety") {
            $this->qestion->orderBy("level_anxiety","DESC");
        }
        else if (Input::get("sort") == "nervousness") {
            $this->qestion->orderBy("level_nervousness","DESC");
        }
        else if (Input::get("sort") == "stimulation") {
            $this->qestion->orderBy("level_stimulation","DESC");
        }
        else {
            $this->qestion->orderBy("divi","DESC");
        }
        $this->qestion->offset($page);
    }
    private function sortForDay($page) {
        if (Input::get("sort") == "date") {
            $this->qestion->orderBy("date_start","DESC");
        }
        else if (Input::get("sort") == "hour") {
            $this->qestion->orderBy("hour","DESC");
        }
        else if (Input::get("sort") == "mood") {
            $this->qestion->orderBy("nas","DESC");
        }
        else if (Input::get("sort") == "anxiety") {
            $this->qestion->orderBy("nas2","DESC");
        }
        else if (Input::get("sort") == "nervousness") {
            $this->qestion->orderBy("nas3","DESC");
        }
        else if (Input::get("sort") == "stimulation") {
            $this->qestion->orderBy("nas4","DESC");
        }
        else {
            $this->qestion->orderBy("divi","DESC");
        }
        $this->qestion->offset($page);        
    }
    private function setDate() {
       
        if ((Input::get("yearFrom") == "" xor Input::get("monthFrom") == "" xor Input::get("dayFrom") == "")) {
            array_push($this->errors, "Data pierwsza jest źle uzupełniona");
             
        }
        else {
            $dateFrom = Input::get("yearFrom") . "-" . Input::get("monthFrom") . "-" . Input::get("dayFrom");
            $this->qestion->where("date_start",">=" ,  $dateFrom  );
            
        }
        if (Input::get("yearTo") == "" xor Input::get("monthTo") == "" xor Input::get("dayTo") == "") {
            array_push($this->errors, "Data druga jest źle uzupełniona");
        }
        else {
            $dateTo = Input::get("yearTo") . "-" . Input::get("monthTo") . "-" . Input::get("dayTo");
            $this->qestion->where("date_start", "<=", $dateTo );
            
        }
        if (Input::get("hourFrom") != "") {
            $this->qestion->whereRaw("hour(date_start) >= " . Input::get("hourFrom") );
        }
        if (Input::get("hourTo") != "") {
            $this->qestion->whereRaw("hour(date_start) <= " . Input::get("hourTo") );
        }

    }
    private function setGroup2() {
        //$this->qestion->groupBy("CASE WHEN `forwarding_drugs`.`id_mood` = null THEN '0'  else `forwarding_drugs`.`id_mood` = `forwarding_drugs`.`id_mood` END");
        $this->qestion->groupBy("moods.id");
        //$this->qestion->havingRaw("CASE WHEN count(forwarding_drugs.id_mood) = 0 THEN  1  else forwarding_drugs.id_mood  END ");
    }
    private function setGroup() {
        $hourStart = explode(":",Auth::User()->start_day);
        $hour = Auth::User()->start_day;
        $this->qestion->groupBy(DB::Raw("(DATE(IF(HOUR(moods.date_start) >= '$hour', moods.date_start,Date_add(moods.date_start, INTERVAL - 1 DAY) )) ) "));
        //$this->qestion->groupBy("forwarding_drugs.id_mood");
        if (Input::get("moodFrom") != "") {
            $this->qestion->havingRaw("round(sum(TIMESTAMPDIFF (SECOND, moods.date_start , moods.date_end)  * moods.level_mood) / "
                   . "sum(TIMESTAMPDIFF(second,moods.date_start,moods.date_end)),2) >= '" . Input::get("moodFrom") . "'");
        }
        if (Input::get("moodTo") != "") {
            $this->qestion->havingRaw("round(sum(TIMESTAMPDIFF (SECOND, moods.date_start , moods.date_end)  * moods.level_mood) / "
                   . "sum(TIMESTAMPDIFF(second,moods.date_start,moods.date_end)),2) <= '" . Input::get("moodTo") . "'");
        }
        if (Input::get("anxietyFrom") != "") {
            $this->qestion->havingRaw("round(sum(TIMESTAMPDIFF (SECOND, date_start , date_end)  * level_anxiety) / "
                   . "sum(TIMESTAMPDIFF(second,date_start,date_end)),2) >=" . Input::get("anxietyFrom"));
        }
        if (Input::get("anxietyTo") != "") {
            $this->qestion->havingRaw("round(sum(TIMESTAMPDIFF (SECOND, date_start , date_end)  * level_anxiety) / "
                   . "sum(TIMESTAMPDIFF(second,date_start,date_end)),2) <= " . Input::get("anxietyTo"));
        }
        if (Input::get("nervousnessFrom") != "") {
            $this->qestion->havingRaw("round(sum(TIMESTAMPDIFF (SECOND, date_start , date_end)  *  	level_nervousness ) / "
                   . "sum(TIMESTAMPDIFF(second,date_start,date_end)),2) >= " .Input::get("nervousnessFrom"));
        }
        if (Input::get("nervousnessTo") != "") {
            $this->qestion->havingRaw("round(sum(TIMESTAMPDIFF (SECOND, date_start , date_end)  *  	level_nervousness ) / "
                   . "sum(TIMESTAMPDIFF(second,date_start,date_end)),2) <= " . Input::get("nervousnessTo"));
        }
        if (Input::get("stimulationFrom") != "") {
            $this->qestion->havingRaw("round(sum(TIMESTAMPDIFF (SECOND, date_start , date_end)  * level_stimulation) / "
                   . "sum(TIMESTAMPDIFF(second,date_start,date_end)),2) >= " . Input::get("stimulationFrom"));
        }
        if (Input::get("stimulationTo") != "") {
            $this->qestion->havingRaw("round(sum(TIMESTAMPDIFF (SECOND, date_start , date_end)  * level_stimulation) / "
                   . "sum(TIMESTAMPDIFF(second,date_start,date_end)),2) <= " . Input::get("stimulationTo"));
        }

        
    }
    private function setWhatWotk() {
        //var_dump(Input::get("what_work3"));
        $Common = new Common;
        
        for ($i=0;$i < 4;$i++) {
            
            if (isset(Input::get("what_work3")[$i]) and Input::get("what_work3")[$i] != "") {
                
                $stringSearch = $Common->charset_utf_fix2(Input::get("what_work3")[$i]);
                
                $this->qestion->where("what_work","LIKE","%" . $stringSearch . "%");
            }
        }
        
    }
    public function sumMood() {
        $Mood =  Moods::query();
        $Mood->selectRaw("(sum(TIMESTAMPDIFF (SECOND, date_start , date_end)) / 3600) as sum");
        if (Input::get("dateFrom") != "") {
                $Mood->where("date_start",">=",Input::get("dateFrom"));
        }
        if (Input::get("dateTo") != "") {
                $Mood->where("date_end","<=",Input::get("dateTo"));
        }
        if (Input::get("moodFrom") != "") {
            $Mood->where("level_mood",">=",Input::get("moodFrom"));
        }
        if (Input::get("moodTo") != "") {
            $Mood->where("level_mood","<=",Input::get("moodTo"));
        }
        if (Input::get("anxietyFrom") != "") {
            $Mood->where("level_anxiety",">=",Input::get("anxietyFrom"));
        }
        if (Input::get("anxietyTo") != "") {
            $Mood->where("level_anxiety","<=",Input::get("anxietyTo"));
        }
        if (Input::get("nerwoFrom") != "") {
            $Mood->where("level_nervousness",">=",Input::get("nerwoFrom"));
        }
        if (Input::get("nerwoTo") != "") {
            $Mood->where("level_nervousness","<=",Input::get("nerwoTo"));
        }
        if (Input::get("stimulationFrom") != "") {
            $Mood->where("level_stimulation",">=",Input::get("stimulationFrom"));
        }
        if (Input::get("stimulationTo") != "") {
            $Mood->where("level_stimulation","<=",Input::get("stimulationTo"));
        }
        $sum = $Mood->first();
        return $sum;
    }
    private function setWhere() {
        $Common = new Common;
        $second = $this->setHour();
        $stringSearch = $Common->charset_utf_fix2(Input::get("what_work"));
        if (Input::get("moodFrom") != "") {
            $this->qestion->whereRaw("level_mood >=' " . Input::get("moodFrom") . "'");
        }
        if (Input::get("moodTo") != "") {
            $this->qestion->whereRaw("level_mood <='" . Input::get("moodTo") . "'");
        }
        if (Input::get("anxietyFrom") != "") {
            $this->qestion->where("level_anxiety",">=",Input::get("anxietyFrom"));
        }
        if (Input::get("anxietyTo") != "") {
            $this->qestion->where("level_anxiety","<=",Input::get("anxietyTo"));
        }
        if (Input::get("nervousnessFrom") != "") {
            $this->qestion->where("level_nervousness",">=",Input::get("nervousnessFrom"));
        }
        if (Input::get("nervousnessTo") != "") {
            $this->qestion->where("level_nervousness","<=",Input::get("nervousnessTo"));
        }
        if (Input::get("stimulationFrom") != "") {
            $this->qestion->where("level_stimulation",">=",Input::get("stimulationFrom"));
        }
        if (Input::get("stimulationTo") != "") {
            $this->qestion->where("level_stimulation","<=",Input::get("stimulationTo"));
        }
        /*
        if (Input::get("what_work") != "") {
            $this->qestion->where("what_work","LIKE","%" . $stringSearch . "%");
        }
         * 
         */
        if (Input::get("ifText") == "on") {
            $this->qestion->where("what_work","!=","");
        }
        if ($second[0] != 0) {
            $this->qestion->whereRaw("(TIMESTAMPDIFF (SECOND, date_start , date_end)) >= '" . $second[0] . "'");
        }
        if ($second[1] != 0) {
            $this->qestion->whereRaw("(TIMESTAMPDIFF (SECOND, date_start , date_end)) <= '" . $second[1] . "'");
        }
    }
}