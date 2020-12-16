<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CurrencyQuotationInterface;
use App\Http\Requests\QuotationRequest;

class HomeController extends Controller
{
    private $currencyApi;

    public function __construct(
        CurrencyQuotationInterface $currencyService
    ){
        $this->currencyApi = $currencyService;
    }

    // Index
    public function index ()
    {
        $coins = $this->currencyApi->getCoinsType();

        return view('pages.initial', compact('coins'));
    }

    public function getQuotation(QuotationRequest $request)
    {
        $coinFrom = $request->input('coinFrom');
        $coinTo = $request->input('coinTo');

        $possibleCoins = array_map(function($coinArray) {
            return $coinArray['sigla'];
        }, $this->currencyApi->getCoinsType());

        // Validador
        $validateCoins = $this->_bc_validaCampos([
            'coinFrom' => $coinFrom,
            'coinTo' => $coinTo
        ],[
            'coinFrom' => 'in:'.implode(',',$possibleCoins),
           // 'coinTo' => 'in:'.implode(',',$possibleCoins)
        ], [
            'coinFrom.in' => 'A moeda a comparar não está disponível, selecione uma moeda possível!',
            // 'coinTo.in' => 'A moeda a ser comparada não está disponível, selecione uma moeda possível!',
        ]);

        if($validateCoins->fails()) {

            $returnMessage = [];
            foreach ($validateCoins->errors()->messages() as $message) {
                $returnMessage []= $message;
            }

            return response()->json([
                'response' => $returnMessage
            ], 422);
        }

        // Informa a data e hora atual
        $dateLastHour = date('Y-m-d H:i:s');

        $quotationReturn = $this->currencyApi->requestQuotation($coinFrom, $coinTo, $dateLastHour);
        $quotationCoinFrom = $quotationReturn[$coinFrom];

        $dataReturn = [
            'siglaFrom' => $quotationCoinFrom['code'],
            'nomeFrom' =>  $quotationCoinFrom['name'],
            'siglaTo' => 'BRL',
            'nomeTo' =>  'Real Brasileiro',
            'maiorCotacao' => $quotationCoinFrom['high'],
            'menorCotacao' => $quotationCoinFrom['low'],
            'compra' => $quotationCoinFrom['bid'],
            'data' => $this->_bc_convertDateFormat($quotationCoinFrom['create_date'])
        ];

        return response()->json([
            'response' => $dataReturn
        ], 200);
    }

    public function getQuotationByPeriods(QuotationRequest $request)
    {
        $coinFrom = $request->input('coinFrom');
        $coinTo = $request->input('coinTo');
        $days = $request->input('days');

        $possibleCoins = array_map(function ($coinArray) {
            return $coinArray['sigla'];
        }, $this->currencyApi->getCoinsType());

        // Validador
        $validateCoins = $this->_bc_validaCampos([
            'coinFrom' => $coinFrom,
            'coinTo' => $coinTo
        ], [
            'coinFrom' => 'in:' . implode(',', $possibleCoins),
            // 'coinTo' => 'in:'.implode(',',$possibleCoins)
        ], [
            'coinFrom.in' => 'A moeda a comparar não está disponível, selecione uma moeda possível!',
            // 'coinTo.in' => 'A moeda a ser comparada não está disponível, selecione uma moeda possível!',
        ]);

        if ($validateCoins->fails()) {
            $returnMessage = [];
            foreach ($validateCoins->errors()->messages() as $message) {
                $returnMessage [] = $message;
            }

            return response()->json([
                'response' => $returnMessage
            ], 422);
        }

        $quotationReturn = $this->currencyApi->requestQuotationByPeriod($coinFrom, $coinTo, $days);

        return response()->json([
            'response' => $quotationReturn
        ], 200);
    }

}
