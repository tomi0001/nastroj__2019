@extends('Layout.Main')

@section('content')
<div class="search">
    <table class="table">
        <form action="{{ url('/Produkt/Search_action')}}" method="get">
        <tr>
            <td class="center">
                Nastrój
            </td>
            <td width="20%">
                <input type="text" name="moodFrom" class="form-control typeMood">
            </td>
            <td class="center">
                do
            </td>
            <td width="20%">
                <input type="text" name="moodTo" class="form-control typeMood">
            </td>
            <td width="20%"class="mini" rowspan="4">
                <br><br><br><br>
                Wartośc dla jednego dnia
                <input type="checkbox" name="moodForDay" class="form-check-input typeMood">
            </td>
        </tr>
        <tr>
            <td class="center">
                Lęk
            </td>
            <td width="20%">
                <input type="text" name="anxietyFrom" class="form-control typeMood">
            </td>
            <td class="center">
                do
            </td>
            <td width="20%">
                <input type="text" name="anxietyTo" class="form-control typeMood">
            </td>

        </tr>
        <tr>
            <td class="center">
                Zdenerwowanie
            </td>
            <td width="20%">
                <input type="text" name="nervousnessFrom" class="form-control typeMood">
            </td>
            <td class="center">
                do
            </td>
            <td width="20%">
                <input type="text" name="nervousnessTo" class="form-control typeMood">
            </td>

        </tr>
        <tr>
            <td class="center">
                Pobudzenie
            </td>
            <td width="20%">
                <input type="text" name="stimulationFrom" class="form-control typeMood">
            </td>
            <td class="center">
                do
            </td>
            <td width="20%">
                <input type="text" name="stimulationTo" class="form-control typeMood">
            </td>

        </tr>
        <tr>
            <td class="center">
                Długośc <span class="mooddd">nastroju</span> od
            </td>
            <td width="20%" >
                <input type="text" name="hour1" class="form-control" placeholder="Godziny">
            </td>
            <td></td>
            <td width="20%">
                <input type="text" name="min1" class="form-control" placeholder="Minuty">
            </td>

        </tr>
        <tr>
            <td class="center">
                Długośc <span class="mooddd">nastroju</span> do
            </td>
            <td width="20%">
                <input type="text" name="hour2" class="form-control" placeholder="Godziny">
            </td>
            <td class="center">
            </td>
            <td width="20%">
                <input type="text" name="min2" class="form-control" placeholder="Minuty">
            </td>

        </tr>
    </table>
    <table class="table">
        <tr>
            <td class="center" width="18%">
                Data od
            </td>
            <td width="17%">
                <select name="yearFrom" class="form-control" >
                    <option value=""></option>
                    @for ($i = $yearFrom;$i <= $yearTo;$i++)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>
               
            </td>
            <td class="center"  width="17%">
                <select name="monthFrom" class="form-control">
                    <option value=""></option>
                    @for ($i = 1;$i <= 12;$i++)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>
            </td>
            <td class="center">
                <select name="dayFrom" class="form-control">
                    <option value=""></option>
                    @for ($i = 1;$i <= 31;$i++)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>
            </td>
            <td class="center">
                <select name="hourFrom" class="form-control">
                    <option value=""></option>
                    @for ($i = 0;$i <= 23;$i++)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>
            </td>

            <td width="20%"class="mini">
 
            </td>
        </tr>
       <tr>
            <td class="center" width="18%">
                Data do
            </td>
            <td width="17%">
                <select name="yearTo" class="form-control" >
                    <option value=""></option>
                    @for ($i = $yearFrom;$i <= $yearTo;$i++)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>
               
            </td>
            <td class="center"  width="17%">
                <select name="monthTo" class="form-control">
                    <option value=""></option>
                    @for ($i = 1;$i <= 12;$i++)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>
            </td>
            <td class="center">
                <select name="dayTo" class="form-control">
                    <option value=""></option>
                    @for ($i = 1;$i <= 31;$i++)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>
            </td>
            <td class="center">
                <select name="hourTo" class="form-control">
                    <option value=""></option>
                    @for ($i = 0;$i <= 23;$i++)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>
            </td>

            <td width="20%"class="mini">
 
            </td>
        </tr>
     </table>
    <table class="table">
        <tr>
            <td class="center">
                Słowa kluczowe co robiłem
                
            </td>
            
            <td class="center">
                    <table  width="100%" id="what_work">
                           <tbody >
                        <tr>

                            <td>
                    <input type="text" name="what_work3[]" class="form-control typeMood">
                        </td>
                               <td>
                     <a onclick="addWorld()" class="btn btn-info btn-lg">
              <span class="glyphicon glyphicon-plus"></span>  
            </a>
                </td>

                        </tr>
                        <tr>

                            <td>
                    <input type="text" name="what_work3[]" class="form-control typeMood">
                        </td>
                               <td>
                     <a onclick="addWorld()" class="btn btn-info btn-lg">
              <span class="glyphicon glyphicon-plus"></span>  
            </a>
                </td>

                        </tr>
                        <tr>

                            <td class="element">
                    <input type="text" name="what_work3[]" class="form-control typeMood">
                        </td>
                               <td>
                     <a onclick="addWorld()" class="btn btn-info btn-lg">
              <span class="glyphicon glyphicon-plus"></span>  
            </a>
                </td>

                        </tr>
                        <tr>

                            <td>
                    <input type="text" name="what_work3[]" class="form-control typeMood">
                        </td>
                               <td>
                     <a onclick="addWorld()" id="add" class="btn btn-info btn-lg">
              <span class="glyphicon glyphicon-plus"></span>  
            </a>
                </td>

                        </tr>

                           </tbody>
                    </table>
                
            </td>
            
     
     
        </tr>
        <tr>
            <td class="center">
                Wyszukaj tylko wpisy które mają jakiś text
                
            </td>
            <td>
                <input type="checkbox" class="form-check-input" name="ifText">
                
            </td>
        </tr>
        <tr>
            <td class="center">
                Wyszukaj następujące leki
                
            </td>
            <td class="center">
                <input type="text" name="drugs" class="form-control typeMood">
                
            </td>
        </tr>
        <tr>
            <td class="center">
                Sortuj według
                
            </td>
            <td class="center">
                <select name="sort" class="form-control" id="sort">
                    <option value="date">Według daty</option>
                    <option value="hour">Według Godziny</option>
                    <option value="mood">Według nastroju</option>
                    <option value="anxiety">Według lęku</option>
                    <option value="nervousness">Według zdenerwowania</option>
                    <option value="stimulation">Według pobudzenia</option>
                    <option value="longMood">Według długości trwania nastroju</option>
                </select>
                
            </td>
        </tr>
        <tr>
            <td class="center">
                Co ma wyszukać
            </td>
            <td>
                <select id="type" name="type" class="form-control" onchange="changeMood()">
                    <option value="mood">Nastrój</option>
                    <option value="sleep">Sen</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="center">
                średnia snu
            </td>
            <td>
               <input type="checkbox" name="average" class="form-check-input typeSleep">
            </td>
        </tr>
        <tr>
            <td colspan="2" class="center">
                <input type="submit" value="Szukaj" class="btn btn-primary">
            </td>
            
        </tr>
        
    </table>
        </form>
        <table class="table">
            <form  method="get">
                <tr>
                    <td colspan="6" class="center">
                        Oblicz średnią trwania nastrojów 
                    </td>
                </tr>
        <tr>
            <td class="center" width="18%">
                Data od
            </td>
            <td width="17%">
                <select name="yearFrom" class="form-control" >
                    <option value=""></option>
                    @for ($i = $yearFrom;$i <= $yearTo;$i++)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>
               
            </td>
            <td class="center"  width="17%">
                <select name="monthFrom" class="form-control">
                    <option value=""></option>
                    @for ($i = 1;$i <= 12;$i++)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>
            </td>
            <td class="center">
                <select name="dayFrom" class="form-control">
                    <option value=""></option>
                    @for ($i = 1;$i <= 31;$i++)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>
            </td>
            <td class="center">
                <select name="hourFrom" id="hourFrom" class="form-control">
                    <option value=""></option>
                    @for ($i = 0;$i <= 23;$i++)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>
            </td>

            <td class="center">
                <select name="week" class="form-control">
                    <option value=""></optino>
                    <option value="1">Poniedziałek</option>
                    
                    <option value="2">Wtorek</option>
                    <option value="3">Środa</option>
                    <option value="4">Czwartek</option>
                    <option value="5">Piątek</option>
                    <option value="6">Sobota</option>
                    <option value="7">Niedziela</option>
                    
                </select>
            </td>
        </tr>
       <tr>
            <td class="center" width="18%">
                Data do
            </td>
            <td width="17%">
                <select name="yearTo" class="form-control" >
                    <option value=""></option>
                    @for ($i = $yearFrom;$i <= $yearTo;$i++)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>
               
            </td>
            <td class="center"  width="17%">
                <select name="monthTo" class="form-control">
                    <option value=""></option>
                    @for ($i = 1;$i <= 12;$i++)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>
            </td>
            <td class="center">
                <select name="dayTo" class="form-control">
                    <option value=""></option>
                    @for ($i = 1;$i <= 31;$i++)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>
            </td>
            <td class="center">
                <select name="hourTo" id="hourTo" class="form-control">
                    <option value=""></option>
                    @for ($i = 0;$i <= 23;$i++)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>
            </td>
            <td class="center">
                    Dla dnia <input type="checkbox" name="day">
            
            
                    Dla całego dnia <input type="checkbox" name="dayForAll" id= "dayFor" onchange="offHour()">
            </td>


        </tr>
        <tr>
            <td colspan="6" class="center">
                <input type="button" onclick="searchAI('{{url('/Produkt/actionAI')}}')" value="Szukaj" class="btn btn-primary">
            </td>
        </tr>
        <tr>
        <td colspan="6">
            <div id="AI" class="tras"></div>
            
        </td>
        </tr>
            </form>
            
        </table>
        
        
        <table class='table'>
            <form method='get'>
                <tr>
                    <td class="center" colspan='8'>
                        Oblicz ile H trwały nastroje
                    </td>
                </tr>
                <tr>
                    <td>
                        Pozmior nastroju od
                    </td>
                    <td>
                        <input type='text' name='moodFrom' class='form-control'>
                    </td>
                    <td>
                        Pozmior nastroju do
                    </td>
                    <td>
                        <input type='text' name='moodTo' class='form-control'>
                    </td>
                </tr>
                <tr>
                    <td>
                        Pozmior lęku od
                    </td>
                    <td>
                        <input type='text' name='anxietyFrom' class='form-control'>
                    </td>
                    <td>
                        Pozmior lęku do
                    </td>
                    <td>
                        <input type='text' name='anxietyTo' class='form-control'>
                    </td>
                </tr>
                <tr>
                    <td>
                        Pozmior zdenerwowania od
                    </td>
                    <td>
                        <input type='text' name='nerwoFrom' class='form-control'>
                    </td>
                    <td>
                        Pozmior zdenerwowania do
                    </td>
                    <td>
                        <input type='text' name='nerwoTo' class='form-control'>
                    </td>
                </tr>
                <tr>
                    <td>
                        Pozmior pobudzenia od
                    </td>
                    <td>
                        <input type='text' name='stimulationFrom' class='form-control'>
                    </td>
                    <td>
                        Pozmior pobudzenia do
                    </td>
                    <td>
                        <input type='text' name='stimulationTo' class='form-control'>
                    </td>
                </tr>
                <tr>
                    <td>
                        Data od
                    </td>
                    <td>
                        <input type='date' name='dateFrom' class='form-control'>
                    </td>
                    <td>
                        Data do
                    </td>
                    <td>
                        <input type='date' name='dateTo' class='form-control'>
                    </td>
                </tr>
                <tr>
                    <td colspan="8" class='center'>
                        <input type='button' onclick='sumMood("{{ url('/Produkt/actionCountMood')}}")' class='btn btn-primary' value='Oblicz'>
                    </td>
                </tr>
                <tr>
                    <td colspan="8">
                    <div id="MoodSearch" class="tras"></div>
            
                    </td>
                </tr>
            </form>
        </table>
        
        <table class="table">
            <form action="{{ url('/PDF/generate')}}">
                <tr>
                    <td class="center" colspan="8">
                        Generuj PDF dla podanych dat
                    </td>
                </tr>
                <tr>
                    <td class="center">
                        Data rozpoczęcia
                    </td>
                    <td>
                        <input type="date" class="form-control" name="date_start">
                    </td>
                    <td class="center">
                        Data zakończenia
                    </td>
                    <td>
                        <input type="date" class="form-control" name="date_end">
                    </td>
                    <td class="center">
                        Czy leki też wydrukować ?
                    </td>
                    <td>
                        <input type="checkbox"  name="drugs">
                    </td>
                    <td class="center">
                        Czy treśc opisu też wydrukować
                    </td>
                    <td>
                        <input type="checkbox"  name="whatWork">
                    </td>
                </tr>
                <tr>
                    <td colspan="8" class="center">
                        <input type="submit" class="btn btn-primary" value="Generuj">
                    </td>
                </tr>
                
            </form>
        </table>
</div>
@endsection