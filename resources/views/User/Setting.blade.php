@extends('Layout.Main')
@section('content')
<div class="setting">
    <div id="login">
    <form action="{{ url('/User/changePassword')}}" method="post">
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
           <input type="password" name="password" class="form-control" required>
        </td>
        
        
    </tr>
    <tr>
        <td>
            Wpisz nowe hasło
        </td>
        <td>
           <input type="password" name="passwordNew" class="form-control" required>
        </td>
        
        
    </tr>
    <tr>
        <td>
            Wpisz jeszcze raz nowe hasło
        </td>
        <td>
           <input type="password" name="passwordNewConfirm" class="form-control" required>
        </td>
        
        
    </tr>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <tr>
        <td colspan="2" class="center">
           <input type="submit"  class="btn btn-primary" value="zmień">
        </td>
        
        
    </tr>   
    </form>
    <tr>
        <td colspan="2" class="center">
            ZMIANA GODZINY DNIA
        </td>
    </tr>    
    <tr>
        <td>
            Godzina dnia
        </td>
          <form action="{{ url('/User/changeHour')}}" method="get">
        <td>
                <input type="number" id="hourStart" name="hourStart" class="form-control" value="{{$startDay}}" required>
        </td>
        
        
    </tr>
    <tr>
        <td colspan="2" class="center">
           <input type="submit"  class="btn btn-primary" value="zmień">
        </td>
        
        
    </tr>

    </form>
    
    
        <tr>
        <td colspan="2" class="center">
            WYGENERUJ HASH
        </td>
    </tr>    
    <tr>
        <td>
            Czy można się logować za pomocą tego hasha 
        </td>
          <form action="{{ url('/User/changeHash')}}" method="get">
        <td>
            <select name="ifHash" class="form-control">
                @if ($hash == 0)
                    <option value=0 selected>Nie</option>
                    <option value=1>Tak</option>
                
                @else
                    <option value=1 selected>Tak</option>
                    <option value=0>Nie</option>
                @endif
                
            </select>
        </td>
        
        
        
    </tr>
    <tr>
        <td>
            Hash
        </td>
          
        <td>
                <input type="text" id="hash" name="nameHash" class="form-control" value="{{$textHash}}">
        </td>
        
        
        
    </tr>
    <tr>
          
        <td colspan="2" class="center">
            <input type="button" onclick="generateHash()" class="btn btn-default" value="Wygeneruj hash">
        </td>
        
        
        
    </tr>
    <tr>
        <td colspan="2" class="center">
           <input type="submit"  class="btn btn-primary" value="Generuj">
        </td>
        
        
    </tr>
</table>
    </form>
    
    
</div>
    <div id="succes">
        @if (session('succes') )
            {{session('succes')}}
        @endif
    </div>
    <div id="error">
        @if (session('error') )
            @for($i=0;$i < count(session('error'));$i++)
            {{session('error')[$i]}}<br>
            @endfor
        @endif
    </div>
</div>
@endsection