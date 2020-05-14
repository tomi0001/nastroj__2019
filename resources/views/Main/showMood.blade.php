<body onload='hideDiv({{$count}})'>
@php
    $i = 1;
@endphp
@if (!empty($colorDay) )
    <div class='level{{$colorDay["mood"]}}' style='width: 40%; text-align: center; margin-left: auto;margin-right: auto;'>
        Poziom nastróju dla tego dnia {{$colorForDay["mood"]}}
    </div>
    <div class='level{{$colorDay["anxiety"]}}' style='width: 40%;text-align: center; margin-left: auto;margin-right: auto;'>
        Poziom lęku dla tego dnia {{$colorForDay["anxiety"]}}
    </div>
    <div class='level{{$colorDay["nervousness"]}}' style='width: 40%;text-align: center; margin-left: auto;margin-right: auto;'>
        Poziom zdenerwowania dla tego dnia {{$colorForDay["nervousness"]}}
    </div>
    <div class='level{{$colorDay["stimulation"]}}' style='width: 40%;text-align: center; margin-left: auto;margin-right: auto;'>
        Poziom pobudzenia dla tego dnia {{$colorForDay["stimulation"]}}
    </div>
@endif
<table class="table" style="width: 90%; margin-left: auto;margin-right: auto;">
    <thead class="titleThead">
        <td width="10%" class="center">
            Start
        </td>
        <td width="10%" class="center">
            Koniec
        </td>
        <td width="10%" class="center">
            Nastrój
        </td>
        <td width="10%" class="center">
            Lęk
        </td>
        <td width="10%" class="center">
            Zdenerwowanie
        </td>
        <td width="10%" class="center">
            Pobudzenie
        </td>
        <td width="15%" class="center">
            Epizdy Pychotyczne /<br> Ilośc wybudzeń
        </td>
    </thead>
    @foreach ($listMood as $list)
        <tr class="idMood{{$i-1}}">
            @if ($list["type"] == 0)
                <td colspan="7" class=" titlemood" >

                    <div class="titleSleep2" style="width: {{$listPercent[$i-1]["percent"]}}%;"> <span style="color: #7e1d38; font-size: 21px;">{{$i}}</span></div>

                </td>
            @else
                <td colspan="7"  class="level titlemood"  >

                    <div class="titlemood{{$list["color_mood"]}}" style="width: {{$listPercent[$i-1]["percent"]}}%;"> <span style="color: #7e1d38; font-size: 21px;">{{$i}}</span></div>

                </td>
            @endif
        </tr>
    <tr>
        <td colspan="7" class="idMood{{$i-1}}">

            <table width=" 100%">
                <tr>
            <td  width="10%" class="center">
                {{substr($list["date_start"],11,5)}}
            </td>
            <td  width="10%" class="center">
                {{substr($list["date_end"],11,5)}}
            </td>
                    @if ($list["type"] != 0)
        <td  width="10%" class="center" rowspan="2">
            {{$listPercent[$i-1]["level_mood"]}}
        </td>
            <td  width="10%" class="center" rowspan="2">
                {{$listPercent[$i-1]["level_anxiety"]}}
            </td>
            <td  width="10%" class="center" rowspan="2">
                {{$listPercent[$i-1]["level_nervousness"]}}
            </td>
            <td  width="10%" class="center" rowspan="2">

                {{$listPercent[$i-1]["level_stimulation"]}}
            </td>
                        @else
                        <td  width="10%" class="center" rowspan="2">

                        </td>
                        <td  width="10%" class="center" rowspan="2">

                        </td>
                        <td  width="10%" class="center" rowspan="2">

                        </td>
                        <td  width="10%" class="center" rowspan="2">


                        </td>
                    @endif
                    @if ($listPercent[$i-1]["epizodes_psychotik"] != null and $list["type"] == 0)
                        <td  width="15%" class="centerbold boldWarning" rowspan="2">

                                Ilość wybudzeń {{$listPercent[$i-1]["epizodes_psychotik"]}}

                        </td>
                    @endif
                    @if ($listPercent[$i-1]["epizodes_psychotik"] != null and $list["type"] == 1)
                            <td  width="15%" class="centerbold boldWarning" rowspan="2">

                                    Ilość epizodów psychotycznych {{$listPercent[$i-1]["epizodes_psychotik"]}}

                            </td>

                    @else
                        <td  width="15%" class="center" rowspan="2">

                               Brak

                        </td>
                    @endif

                </tr>
                <tr>
                    <td colspan="2" class="center" rowspan="2">
                        {{$listPercent[$i-1]["second"]}}
                    </td>



    </tr>
                </table>
        </td>
    </tr>
    <tr>
        <td colspan="7"  class="idMood{{$i-1}}">
            @if ( ($list["type"] == 0))
                <button onclick="deleteSleep('{{ url('/Sleep/delete')}}',{{$listMood[$i-1]["id"]}},{{$i-1}})" class='btn btn-danger'>usuń sen</button>
            @else
                <div class="buttonMood">
                    @if ($listPercent[$i-1]["drugs"] != "")
                        <button onclick="showDrugs('{{url('/Drugs/show')}}',{{$i-1}},{{$listPercent[$i-1]["id"]}})" class="btn btn-primary">Pokaż leki</button>
                    @else
                        <button class="btn btn-danger" disabled>Nie było leków</button>
                    @endif
                        @if ($listPercent[$i-1]["what_work"] == true)
                            <button onclick="showDescription('{{url('/Mood/showDescription')}}',{{$listPercent[$i-1]["id"]}},{{$i-1}})" class="btn btn-primary">Pokaż opis</button>
                        @else
                            <button  class="btn btn-danger" disabled>Nie było opisu</button>
                        @endif
                        <button id="addDrugsButton{{$i-1}}" onclick="addDrugs('{{ url('/Drugs/addDrugs')}}',{{$i-1}},{{$listMood[$i-1]["id"]}})" class="btn btn-primary" id="addDrugsButton{{$i}}">Dodaj leki</button>
                        <button onclick="editMood('{{url('/Mood/edit')}}',{{$listMood[$i-1]["id"]}},{{$i-1}})" class="btn btn-primary">Edytuj nastrój</button>
                        <button onclick="deleteMood('{{url('/Mood/delete')}}',{{$listMood[$i-1]["id"]}},{{$i-1}})" class="btn btn-danger">Usuń nastrój</button>
                        <button onclick="addDescription('{{url('/Mood/addDescription')}}',{{$listMood[$i-1]["id"]}},{{$i-1}})" class="btn btn-primary">Edytuj dodaj opis</button>
                </div>
            @endif




            <br>
                <div id="showDrugss{{$i-1}}"></div>
            <div id="showDescription{{$i-1}}"></div>
                <div id="showFieldText{{$i-1}}" class='center' style='width: 50%;'></div>
                <div id="viewEditMood{{$i-1}}"></div>

                    <form id='addDrugsssss{{$i-1}}' method='get'>
                        <div class="drugss{{$i-1}} center " style='width: 80%;'>



                        </div>
                    </form>
                <div class="drugsss{{$i-1}} center"></div>


            <table width="60%" align="center">
                <tr>
                    <td colspan="6">
            <div id="addDrugsResult{{$i-1}}"></div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>





    <tr>
        <td colspan="7">
            <hr class="mood">
        </td>
    </tr>
        @php
            $i++;
        @endphp

    @endforeach
</table>