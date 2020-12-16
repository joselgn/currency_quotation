<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuotationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'coinFrom' => 'required|string',
            'coinTo' => 'required|string',
            'days' => 'integer|min:1',
        ];
    }

    /**
     * Set custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'coinFrom.required' => 'É necessário selecionar uma moeda para comparar.',
            'coinFrom.string' => 'O valor da moeda a comparar deve ser a sigla da moeda.',

            'coinTo.required' => 'É necessário selecionar uma moeda a ser comparada.',
            'coinTo.string' => 'O valor da moeda a ser comparada deve ser a sigla da moeda.',

            'days.integer' => 'O número de dias deve ser um valor inteiro.',
            'days.min' => 'O número de dias deve possuir um valor mínimo de :min.',
        ];
    }

}