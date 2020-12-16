@extends('layouts.theme')

@section('content')
<div class="container form-select-coins" >
    <div class="left-select">
        <select id="coin_from">
            <option value="">Selecione uma Moeda</option>
            @foreach($coins as $coin)
                <option value="{{ $coin['sigla'] }}">
                    {{ $coin['nome'] }} - {{ $coin['sigla'] }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="comparative-select">
        X
    </div>

    <div class="right-select">
        <select id="coin_to">
            <option value="BRL" selected>
                Real Brasileiro - BRL
            </option>
        </select>
    </div>
</div>

<div class="container result_coin_board result_quotation" >
    <div class="text_board">
        <div class="cotacao"></div>
        <div class="comparacao"></div>
    </div>

    <div class="graphic">
        <div id="chart_div" style="width: 100%; height: 300px;"></div>
    </div>
</div>

<div class="more_quotations result_quotation">
    <h3>Mais opções de prazo para cotação</h3>

    <div class="menu_quotations">
        <button class="btn_days" value="5">5 Dias</button>
        <button class="btn_days" value="10">10 Dias</button>
        <button class="btn_days" value="15">15 Dias</button>
    </div>

    <div class="graphic_by_period" >
        <div id="cart_by_period" style="width: 100%; height: 300px;"></div>
    </div>
</div>
@endsection