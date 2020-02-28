@extends('Layout.Main')

@section('content')

<div class="searchaction">
    <table class="table">
        <thead class="thead-dark">
        <tr>
            <td>
                <div class="center">
    <span class="title">średni czas snu
        {{$list}} H</span>
                </div>
            </td>
        </tr>
    </table>
</div>

@endsection