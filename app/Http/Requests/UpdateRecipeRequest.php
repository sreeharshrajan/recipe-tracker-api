<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecipeRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Change this if you have authorization logic
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'ingredients' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
            'prep_time' => 'sometimes|required|integer|min:0',
            'cook_time' => 'sometimes|required|integer|min:0',
            'difficulty' => 'sometimes|required|in:easy,medium,hard',
        ];
    }
}
