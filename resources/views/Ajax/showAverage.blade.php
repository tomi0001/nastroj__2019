<div class="center" style="width: 100%;">
    <table class="table">
      @if ($day == "on")
      <tr>
            <td style="width: 18%;">
                Data <br>{{$hour}}
            </td>
            <td>
                Poziom nastroju
            </td>
            <td>
                odchylenie nastroju
                
            </td>
            <td>
                różnica nastroju (min/max)
                
            </td>
            <td>
                Poziom lęku
            </td>
            <td>
                odchylenie lęku
                
            </td>
            <td>
                Poziom zdenerowania
            </td>
            <td>
                odchylenie zdenerowania
                
            </td>
            <td>
                Poziom pobudzenia
            </td>
            <td>
                odchylenie pobudzenia
                
            </td>
      </tr>
      <tr>
            <td>
                Dla tego czasu
            </td>
            <td>
                {{$list[0]}}
            </td>
            <td>
                {{$list[1]}}
            </td>
            <td>
                {{$list[8]}} / {{$list[9]}}
            </td>
            <td>
                {{$list[2]}}
            </td>
            <td>
                {{$list[3]}}
            </td>
            <td>
                {{$list[4]}}
            </td>
            <td>
                {{$list[5]}}
            </td>
            <td>
                {{$list[6]}}
            </td>
            <td>
                {{$list[7]}}
            </td>
        </tr>
      
      
      
      @else
      <tr>
            <td style="width: 18%;">
                Data <br>{{$hour}}
            </td>
            <td>
                Poziom nastroju
            </td>
            <td>
                odchylenie nastroju
                
            </td>
            <td>
                różnica nastroju (min/max)
                
            </td>
            <td>
                Poziom lęku
            </td>
            <td>
                odchylenie lęku
                
            </td>
            <td>
                Poziom zdenerowania
            </td>
            <td>
                odchylenie zdenerowania
                
            </td>
            <td>
                Poziom pobudzenia
            </td>
            <td>
                odchylenie pobudzenia
                
            </td>
      </tr>
        @for ($i=0;$i < count($days);$i++)
        <tr>
            <td>
                {{$days[$i]}}
            </td>
            <td>
                {{$list[0][$i]}}
            </td>
            <td>
                {{$harmonyMood[$i]}}
                
            </td>
            <td>
                {{$list[4][$i]}} / {{$list[5][$i]}}
                
            </td>
            <td>
                {{$list[1][$i]}}
            </td>
            <td>
                {{$harmonyAnxiety[$i]}}
                
            </td>
            
            <td>
                {{$list[2][$i]}}
            </td>
            <td>
                {{$harmonyNer[$i]}}
                
            </td>
            
            <td>
                {{$list[3][$i]}}
            </td>
            <td>
                {{$harmonyStimu[$i]}}
                
            </td>
        </tr>
        @endfor
      @endif
    </table>
</div>