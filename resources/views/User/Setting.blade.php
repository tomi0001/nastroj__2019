@extends('Layout.Main')
@section('content')
<div class="setting">
    <form>
<table class="table">
    <tr>
        <td colspan="2" class="center">
            ZMIANA HASŁA
        </td>
    </tr>
    <tr>
        <td>
            Twoje hasło
        </td>
        <td>
           <input type="password" id="password" class="form-control">
        </td>
        
        
    </tr>
    <tr>
        <td>
            Wpisz nowe hasło
        </td>
        <td>
           <input type="password" id="passwordNew" class="form-control">
        </td>
        
        
    </tr>
    <tr>
        <td>
            Wpisz jeszcze raz nowe hasło
        </td>
        <td>
           <input type="password" id="passwordNewConfirm" class="form-control">
        </td>
        
        
    </tr>
    <tr>
        <td colspan="2" class="center">
           <input type="submit"  class="btn btn-primary" value="zmień">
        </td>
        
        
    </tr>   
    <tr>
        <td colspan="2" class="center">
            ZMIANA GODZINY DNIA
        </td>
    </tr>    
    <tr>
        <td>
            Godzina dnia
        </td>
        <td>
           <input type="number" id="hourStart" class="form-control" value="{{$startDay}}">
        </td>
        
        
    </tr>
    <tr>
        <td colspan="2" class="center">
           <input type="submit"  class="btn btn-primary" value="zmień">
        </td>
        
        
    </tr>
</table>
    </form>
</div>
@endsection