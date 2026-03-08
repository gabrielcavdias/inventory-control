<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'min:3', 'max:255'],
            'preco_venda' => ['required', 'decimal:2', 'min:0', 'max:99999.99']
        ];
    }


    protected function prepareForValidation()
    {
        parent::prepareForValidation();
        if (!isset($this->preco_venda) || !is_numeric($this->preco_venda)) {
            return;
        }
        $newValue = number_format(
            num: $this->preco_venda,
            decimals: 2,
            decimal_separator: '.',
            thousands_separator: ''
        );
        $this->replace([
            ...$this->all(),
            'preco_venda' => $newValue
        ]);
    }
}
