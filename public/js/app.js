
       function saveDrugs(url,i,id)  {
           var result = $("#addDrugsResult"+i).load(url + "?" + "idMood=" + id  + "&" +  $("#addDrugsssss"+i).serialize(),function() {
               if (result.text() =="Pomyslnie dodano") {
                   resetDrugs(i);
                   //alert("dobrze");
               }
           });
       }     
       
       function showDrugs(url,i,id) {
           
           $("#showDrugss"+i).load(url + "?id=" + id).toggle();
       }
       
function addDrugs(url = null,i = null,id = null) {
    if (i == null) {
        
        $(".drugss").append("<table class='table addMood drugs' id=\"drug_\"><tr><td width='50%' class='center'>Nazwa leku </td><td class='center'><input type='text' name='name[]' class='form-control'></td></tr><tr><td width='50%' class='center' rowspan=\"2\"><br>Dawka leku </td><td class='center'><input type='text' name='dose[]' class='form-control'></td></tr><tr>    <td class='center'>                <select name='type[]' class='form-control form-control-lg'><option value='1'>Mg</option><option value='2'>Militry</option><option value='3'>Ilości</option></select></td></tr><tr><td rowspan='2' class='center'><br>Data wzięcia</td><td><input type='date' name='date[]' class='form-control'></td></tr><tr><td class='center'><input type='time' name='time[]' class='form-control'></td></tr><tr>                                                            <td colspan=\"2\" class='center'><div class=\"center\"><input type=\"button\" onclick='deleteDrugs()'  value=\"Usuń lek\" class=\"btn btn-primary drugsss\"></div></td></tr></table>").html();
    }
    
    else {
        var bool = false;
        if ($(".drugss"+i).text() == "") {
            bool = true;
        }
        var drugs = "drugs"+i;
        $("#addDrugsButton"+i).prop('disabled', true);
        $(".drugss"+i).html("<div class='newline'></div><div class='addDrugssss '" + drugs  + "' id=\"drug_\"><div class=tr><div class='td'>Nazwa leku</div><div class='td'><input type='text' name='name[]' class='form-control'></div></div><div class=newline></div><div class=tr><div class='td'><br>Dawka leku</div><div class='td'><input type='text' name='dose[]' class='form-control'><br><select name=type[] class='form-control'><option value='1'>Mg</option><option value='2'>Militry</option><option value='3'>Ilości</option></select></div></div><div class='newline'></div><div class=tr><div class='td'><br>Dawka leku</div><div class='td'><input type='date' name='date[]' class='form-control'><br><input type='time' name='time[]' class='form-control'>             </div></div>      <div class='newline'></div><div class='tr'><div class=' tdCenter'><input type='button' value='Usuń wpis' onclick='deleteDrugs(true," + i + ")' class='btn btn-primary'></div></div>           </div>");
        
  $(".drugsss"+i).html("      <div class='addDrugssss  center' style=' width: 80%;'" + drugs  + "'   id=\"drug_\"><div class='tr'><div class='tdCenter'><input type='button' onclick=saveDrugs('" + url + "'," + i + "," + id + ") class='btn btn-primary' value='Zapisz leki'></div></div></div>");



    }
    
}
function offHour() {
    //alert("dobrze");
    if($("#dayFor").is(':checked'))
    {
        $("#hourTo").prop('disabled', true);
        $("#hourFrom").prop('disabled', true);
    }
    else {
        $("#hourTo").prop('disabled', false);
        $("#hourFrom").prop('disabled', false);
    }
}
function resetDrugs(i) {
    //alert("jakks");
    $(".drugss"+i).html("");
            $(".drugsss"+i).html("");
            $("#addDrugsButton"+i).prop('disabled', false);
}
function editMood(url,id,i) {
    $("#showFieldText"+i).toggle(120);
    $("#showFieldText"+i).load(url + "?id=" + id + "&i=" + i);
}
function deleteDrugsId(url,id) {
    var con = confirm("Czy na pewno usunąć");
    if (con == true) {
        $("#DrugsTr"+id).load(url + "?id=" + id);
    }
}
function editMoodAction(url,id,i) {
    var levelMood = $("#levelMood").val();
    var levelAnxiety = $("#levelAnxiety").val();
    var levelNervousness = $("#levelNervousness").val();
    var levelStimulation = $("#levelStimulation").val();
    //alert(i);
    $("#viewEditMood"+i).load(url + "?id=" + id + "&levelMood=" + levelMood + "&levelAnxiety=" + levelAnxiety + "&levelNervousness=" + levelNervousness + "&levelStimulation=" + levelStimulation);
}
function addMood(url) {
    
    $("#addResult").load(url  + "?" +  $( "form" ).serialize());
}
function addSleep(url) {
    $("#addResultSleep").load(url + "?" + $("form").serialize());
}

function deleteDrugs(bool = false,i=0) {
     if (bool == false) {
       $(document).on('click', '.drugsss', function() {
           $(this).parents('.drugs').remove();
           
       });
     }
     else {
    

        $(".drugs"+i).remove();

        //alert(i);
        if (($(".drugss"+i).text() != "")) {
            $(".drugss"+i).html("");
            $(".drugsss"+i).html("");
            $("#addDrugsButton"+i).prop('disabled', false);
        }
        else {

        }
     }
}


function addDescription(url,id,i) {
    $("#showFieldText"+i).toggle(120);
    $("#showFieldText"+i).load(url + "?id=" + id);
}

function hideDiv(count) {
    for (i=0;i < count;i++) {
        $("#showDescription"+i).hide();
        $("#showFieldText"+i).hide();
        $("#showDrugss"+i).hide();
    }
}
function editDescription(url,id) {
    
    var description = $("#description").val();
    if (description == "") {
        alert("Musisz wpisać jakąś wartość");
        
    }
    else {
        
        $("#editDescription"+id).load(url + "?" + $("form").serialize() + "&id=" + id);
    }
    
}

function searchAI(url) {
    
    $("#AI").load(url + "?" + $("form").serialize());
}

function changeMood() {
    if ($("#type").val() == "sleep") {
        $("#sort").html("<option value='date'>Według daty</option><option value='hour'>Według Godziny</option><option value='longMood'>Według długości trwania snu</option>")
        $(".typeMood").prop('disabled', true);
        $(".mooddd").text("snu");
    }
    else {
        $("#sort").html("<option value='date'>Według daty</option><option value='hour'>Według Godziny</option><option value='mood'>Według nastroju</option><option value='anxiety'>Według lęku</option><option value='nervousness'>Według zdenerwowania</option><option value='stimulation'>Według pobudzenia</option><option value='longMood'>Według długości trwania nastroju</option>")
        $(".typeMood").prop('disabled', false);
        $(".mooddd").text("nastroju");
    }
    
}
function showDescription(url,id,i) {
    
        $("#showDescription"+i).toggle(120);
        $("#showDescription"+i).load(url + "?id=" + id);
    
}
function deleteMood(url,id,i) {
    var con = confirm("Czy na pewno usunąć");
    
    if (con == true) {
        $(".idMood"+i).load(url + "?id=" + id).remove();
    }

}
function deleteSleep(url,id,i) {
    var con = confirm("Czy na pewno usunąć");
    
    if (con == true) {
        //alert(id);
        $(".idMood"+i).load(url + "?id=" + id);
    }
}