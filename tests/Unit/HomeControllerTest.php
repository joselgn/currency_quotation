<?php

namespace Tests\Unit;

use Tests\TestCase;

use App\Http\Controllers\HomeController;
use App\Services\CurrencyQuotation;

class HomeControllerTest extends TestCase
{
    public function testCoinsType()
    {
        $controller = $this->getMockBuilder(HomeController::class)
            ->disableOriginalConstructor()
            ->getMock();

        $currencyService = new CurrencyQuotation();
        $controller->expects($this->once())->method('index')->willReturn($currencyService->getCoinsType());

        $allCoinsType = $controller->index();
        foreach($this->getDataCoinsType() as $dataCoin) {
            $key = $dataCoin['nome'];
            $toCompare = $allCoinsType[$key];
            $this->assertEquals($dataCoin['nome'], $toCompare['nome']);
            $this->assertEquals($dataCoin['sigla'], $toCompare['sigla']);
        }
    }

    public function testQuotation ()
    {
        $response = $this->post(route('currency-quote', [
            'coinFrom' => 'AUD',
            'coinTo' => 'BRL'
        ]));

        $this->assertEquals(200, $response->getStatusCode());

        $responseArray = json_decode($response->getContent(), true);

        $this->assertEquals(true, array_key_exists('siglaFrom', $responseArray['response']));
        $this->assertEquals(true, array_key_exists('nomeFrom', $responseArray['response']));
        $this->assertEquals(true, array_key_exists('siglaTo', $responseArray['response']));
        $this->assertEquals(true, array_key_exists('nomeTo', $responseArray['response']));
        $this->assertEquals(true, array_key_exists('maiorCotacao', $responseArray['response']));
        $this->assertEquals(true, array_key_exists('menorCotacao', $responseArray['response']));
        $this->assertEquals(true, array_key_exists('compra', $responseArray['response']));
        $this->assertEquals(true, array_key_exists('data', $responseArray['response']));

        $responseError = $this->post(route('currency-quote', [
            'coinFrom' => 'BRL',
            'coinTo' => 'BRL'
        ]));

        $this->assertNotEquals(200, $responseError->getStatusCode());
        $this->assertEquals(422, $responseError->getStatusCode());
    }

    public function testQuotationPeriod ()
    {
        $response = $this->post(route('currency-period', [
            'coinFrom' => 'AUD',
            'coinTo' => 'BRL',
            'days' => 5
        ]));

        $this->assertEquals(200, $response->getStatusCode());

        $responseArray = json_decode($response->getContent(), true);
        $responseData = $responseArray['response'];

        $this->assertEquals(5, count($responseData));

       foreach($responseData as $quote) {
           $this->assertEquals(true, array_key_exists('siglaFrom', $quote));
           $this->assertEquals(true, array_key_exists('nomeFrom', $quote));
           $this->assertEquals(true, array_key_exists('siglaTo', $quote));
           $this->assertEquals(true, array_key_exists('nomeTo', $quote));
           $this->assertEquals(true, array_key_exists('data', $quote));
           $this->assertEquals(true, array_key_exists('compra', $quote));
       }

        $responseError = $this->post(route('currency-period', [
            'coinFrom' => 'BRL',
            'coinTo' => 'BRL',
            'days' => 10
        ]));

        $this->assertNotEquals(200, $responseError->getStatusCode());
        $this->assertEquals(422, $responseError->getStatusCode());
    }


    private function getDataCoinsType ()
    {
        return [
                [
                    "nome"=> "Bitcoin",
                    "sigla"=> "BTC"
                ],
                [
                    "nome"=> "Dólar Australiano",
                    "sigla"=> "AUD"
                ],
                [
                    "nome"=> "Dólar Canadense",
                    "sigla"=> "CAD"
                ],
                [
                    "nome"=> "Dólar Comercial",
                    "sigla"=> "USD"
                ],
                [
                    "nome"=> "Dólar Turismo",
                    "sigla"=> "USD"
                ],
                [
                    "nome"=> "Ethereum",
                    "sigla"=> "ETH"
                ],
                [
                    "nome"=> "Euro",
                    "sigla"=> "EUR"
                ],
                [
                    "nome"=> "Franco Suíço",
                    "sigla"=> "CHF"
                ],
                [
                    "nome"=> "Iene Japonês",
                    "sigla"=> "JPY"
                ],
                [
                    "nome"=> "Libra Esterlina",
                    "sigla"=> "GBP"
                ],
                [
                    "nome"=> "Litecoin",
                    "sigla"=> "LTC"
                ],
                [
                    "nome"=> "Novo Shekel Israelense",
                    "sigla"=> "ILS"
                ],
                [
                    "nome"=> "Peso Argentino",
                    "sigla"=> "ARS"
                ],
                [
                    "nome"=> "Ripple",
                    "sigla"=> "XRP"
                ],
                [
                    "nome"=> "Yuan Chinês",
                    "sigla"=> "CNY"
                ]
        ];
    }
}
