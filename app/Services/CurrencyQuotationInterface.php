<?php


namespace App\Services;

/**
 * Classe responsável por fazer as solicitaçẽos para a API de cotação de Moedas
 * API: https://docs.awesomeapi.com.br/api-de-moedas
 */
interface CurrencyQuotationInterface
{
    /**
     *  Retorna todas as moedas disponíveis.
     * @return array
     */
    public function getCoinsType();

    /**
     * Retorna a cotação entre uma determinada moeda.
     * Recebe como parâmetro a sigla das Moedas (string)
     *  e a data atual, retornando os dados da última hora pesquisada
     *
     * @param string $coinFrom
     * @param string $coinTo
     * @param datetime $datetime format Y-m-d H:i:s
     * @return array
     */
    public function requestQuotation($coinFrom, $coinTo, $datetime);

    /**
     * Retorna a cotação de N dias entre as moedas
     *
     * @param string $coinFrom // Sigla Moeda a comparar
     * @param string $coinTo // Sigla moeda a ser comparada
     * @param integer $days  // Quantidade de dias de comparação
     */
    public function requestQuotationByPeriod(string $coinFrom, string $coinTo, int $days);
}