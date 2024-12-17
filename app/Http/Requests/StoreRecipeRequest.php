<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecipeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Autorizar qualquer usuário para este request (pode ser ajustado conforme a lógica de autorização)
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'preparation_method' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'ingredients' => 'required|array', // Espera um array de ingredientes
            'ingredients.*.name' => 'required|string|max:255', // Valida o nome de cada ingrediente
            'ingredients.*.amount' => 'nullable|string', // Valida a quantidade de cada ingrediente
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => 'O título da receita é obrigatório.',
            'title.string' => 'O título da receita deve ser uma string.',
            'category_id.required' => 'A categoria da receita é obrigatória.',
            'ingredients.required' => 'É necessário informar os ingredientes.',
            'ingredients.*.name.required' => 'O nome de cada ingrediente é obrigatório.',
        ];
    }
}
