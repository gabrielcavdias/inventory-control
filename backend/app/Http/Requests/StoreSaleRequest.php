<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cliente' => ['required', 'string', 'min:3', 'max:255'],
            'produtos' => ['required', 'array', 'min:1'],
            'produtos.*.id' => ['required', 'integer', 'exists:products,id',],
            'produtos.*.quantidade' => ['required', 'integer', 'min:1', 'max:999999'],
            'produtos.*.preco_unitario' => ['required', 'decimal:2', 'min:1', 'max:999999'],

        ];
    }

    protected function prepareForValidation()
    {
        parent::prepareForValidation();
        if (!isset($this->produtos) || !is_array($this->produtos)) {
            return;
        }
        $newProducts = array_map(fn(array $produto) => [
            ...$produto,
            'preco_unitario' => number_format(
                num: $produto['preco_unitario'],
                decimals: 2,
                decimal_separator: '.',
                thousands_separator: ''
            )
        ], $this->produtos);

        $this->replace([
            ...$this->all(),
            'produtos' => $newProducts
        ]);
    }
}
