<?php


namespace App\Services;

use App\Services\CurrencyQuotationInterface;
use Carbon\Carbon;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CurrencyQuotation implements CurrencyQuotationInterface
{
    /* @Guzzle Client */
    private $guzzleService;

    /* Tempo de cache */
    private $cacheTimeInMinutes;

    public function __construct()
    {
        $this->guzzleService = new Guzzle();

        $timeInSeconds = 60;
        $this->cacheTimeInMinutes = 60 * $timeInSeconds; // 60 = 1hr
    }

    public function getCoinsType()
    {
        return Cache::remember('allCoinsType', $this->cacheTimeInMinutes, function () {
            $allCoinsOccurences = $this->requestAllCoinsOccurences();

            $allCoins = [];
            foreach ($allCoinsOccurences as $coinArray) {
                $allCoins [$coinArray['name']]= [
                    'nome' => $coinArray['name'],
                    'sigla' => $coinArray['code']
                ];
            }

            /* Adicionando o Real
            $allCoins['BRL'] = [
                'nome' => 'Real Brasileiro',
                'sigla' => 'BRL'
            ];*/

            ksort($allCoins);

            return $allCoins;
        });
    }

    public function requestQuotation($coinFrom, $coinTo, $datetime)
    {
        list($date, $time) = explode(' ',$datetime);
        list($ano, $mes, $dia) = explode('-', $date);
        list($hora, $minutos, $segundos) = explode(':', $time);

       $keyCache = $coinFrom.'-'.$coinTo.'-'.$ano.$mes.$dia.$hora;
        return Cache::remember($keyCache, $this->cacheTimeInMinutes, function () use ($coinFrom, $coinTo) {
            $responseApi = $this->requestCoinsSpecificOccurence($coinFrom, $coinTo);
            return $responseApi;
        });
    }

    public function requestQuotationByPeriod(string $coinFrom, string $coinTo, int $days)
    {
        $keyCache = $coinFrom.'-'.$coinTo.'-'.$days;
        return Cache::remember($keyCache, $this->cacheTimeInMinutes, function () use ($coinFrom, $coinTo, $days) {
            $responseApi = $this->requestCoinsSpecificOccurenceByPeriods($coinFrom, $coinTo, $days);

            $returnArray = [];
            $siglaFrom = '';
            $SiglaTo = '';
            $nomeFrom = '';
            $nomeTo = 'Real Brasileiro';

            foreach ($responseApi as $cotacoes) {
                if(isset($cotacoes['code']))
                    $siglaFrom = $cotacoes['code'];
                if(isset($cotacoes['codein']))
                    $SiglaTo = $cotacoes['codein'];
                if(isset($cotacoes['name']))
                    $nomeFrom = $cotacoes['name'];

                $returnArray []= [
                  'siglaFrom' => $siglaFrom,
                  'nomeFrom' => $nomeFrom,
                  'siglaTo' => $SiglaTo,
                  'nomeTo' => $nomeTo,
                  'data' => date('Y-m-d H:i:s', $cotacoes['timestamp']),
                  'compra' => $cotacoes['bid']
                ];
            }

            return $returnArray;
        });
    }

    private function requestAllCoinsOccurences()
    {
        try {
            $request = $this->guzzleService->request('GET','https://economia.awesomeapi.com.br/json/all');
            return json_decode($request->getBody(), true);
        } catch (ConnectException $ec) {
            throw new ConnectException('A API não está disponível para consulta! [Erro de Conexão]', $ec->getRequest());
            Log::error('EA API não está disponível para consulta, erro de conexão. ['.$ec->getMessage().'] - '.$ec->getCode());
        } catch (BadResponseException $e) {
            throw new \BadResponseException('Erro ao requisitar moedas na API!', $e->getCode());
            Log::error('Erro ao pesquisar moedas na API. ['.$e->getMessage().']');
        }
    }

    private function requestCoinsSpecificOccurence($siglaCoinFrom, $siglaCoinTo)
    {
        $requestInitials = $siglaCoinFrom.'-'.$siglaCoinTo;
        $urlRequest = 'https://economia.awesomeapi.com.br/json/all/'.$requestInitials;

        try {
            $request = $this->guzzleService->request('GET',$urlRequest);
            return json_decode($request->getBody(), true);
        } catch (ConnectException $ec) {
            throw new \ConnectException('A API não está disponível para consulta! [Erro de Conexão]',$ec->getRequest());
            Log::error('EA API não está disponível para consulta, erro de conexão. ['.$ec->getMessage().'] - '.$ec->getCode());
        } catch (ClientException $eClient) {
            $responseError = json_decode($eClient->getResponse()->getBody()->getContents());

            throw new \Exception('Erro ao requisitar cotação entre moedas na API! Erro['.$responseError->status.'] '.$responseError->message);
            Log::error('Erro ao pesquisar a cotação entre as moedas ['.$siglaCoinFrom.'-'.$siglaCoinTo.'] na API. ['.$responseError->message.']');
        }
    }

    private function requestCoinsSpecificOccurenceByPeriods($siglaCoinFrom, $siglaCoinTo, $days)
    {
        $requestInitials = $siglaCoinFrom.'-'.$siglaCoinTo;
        $urlRequest = 'https://economia.awesomeapi.com.br/json/daily/'.$requestInitials.'/'.$days;

        try {
            $request = $this->guzzleService->request('GET',$urlRequest);
            return json_decode($request->getBody(), true);
        } catch (ConnectException $ec) {
            throw new \ConnectException('A API não está disponível para consulta! [Erro de Conexão]', $ec->getRequest());
            Log::error('EA API não está disponível para consulta, erro de conexão. ['.$ec->getMessage().'] - '.$ec->getCode());
        } catch (ClientException $eClient) {
            $responseError = json_decode($eClient->getResponse()->getBody()->getContents());

            throw new \ClientException('Erro ao requisitar cotação entre moedas na API em período de '.$days.' dias! Erro['.$responseError->status.'] '.$responseError->message);
            Log::error('Erro ao pesquisar a cotação entre as moedas ['.$siglaCoinFrom.'-'.$siglaCoinTo.' de '.$days.' dias] na API. ['.$responseError->message.']');
        }
    }
}