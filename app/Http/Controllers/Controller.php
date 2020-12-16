<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function _bc_validaCampos($arrParams, $arrRules,  $arrMessages)
    {
        //Instancia o validador atraves do helper
        $validator = \validator($arrParams,$arrRules,$arrMessages);//monta validador

        return $validator;
    }//_validaCampos

    //Retorna data formatada conforme o padrao passado como string
    //Y = ano - 4 caracteres
    //m = mes - 2 caracteres
    //d = dia - 2 caracteres
    public function _bc_convertDateFormat($date, $formatOut='d/m/Y H:i:s', $useTime=true, $separateDateFromTime=' ')
    {
        $formatIn = 'Y-m-d H:i:s';
        $formatOut = is_null($formatOut) ?  'd/m/Y H:i:s' : $formatOut;
        $useTime = is_null($useTime) ? true : $useTime;
        $separateDateFromTime = is_null($separateDateFromTime) ? ' ' : $separateDateFromTime;

        $dateTreat = $useTime ? $date : $date.' 00:00:00';
        $dateIn = date($formatIn, strtotime($dateTreat));
        $dateOut = date($formatOut, strtotime($dateIn));

        if ($useTime)
            $newDate = str_replace(' ', $separateDateFromTime, $dateOut);
        else
            [$newDate] = explode(' ', $dateOut);

        return $newDate;
    }// _bc_convertDateFormat
}
