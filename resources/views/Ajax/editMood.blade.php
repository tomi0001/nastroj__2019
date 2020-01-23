

<table class="table">
    <form  method="get">
    <tr>
        <td>
            Poziom nastroju
        </td>
        <td>
            <input type="text" id="levelMood_{{$list->id}}" class="form-control" value="{{$list->level_mood}}">
        </td>
    </tr>
    <tr>
        <td>
            Poziom lęku
        </td>
        <td>
            <input type="text" id="levelAnxiety_{{$list->id}}" class="form-control" value="{{$list->level_anxiety}}">
        </td>
    </tr>
    <tr>
        <td>
            Poziom zdenerwowania
        </td>
        <td>
            <input type="text" id="levelNervousness_{{$list->id}}" class="form-control" value="{{$list->level_nervousness}}">
        </td>
    </tr>
    <tr>
        <td>
            Poziom pobudzenia
        </td>
        <td>
            <input type="text" id="levelStimulation_{{$list->id}}" class="form-control" value="{{$list->level_stimulation}}">
        </td>
    </tr>
    <tr>

        <td colspan="2" class="center">
            <input type="button" value="Edytuj" class="btn btn-primary" onclick="editMoodAction('{{ url('/Mood/editAction')}}',{{$list->id}},{{$i}})">
        </td>
    </tr>

    </form>
    
</table>