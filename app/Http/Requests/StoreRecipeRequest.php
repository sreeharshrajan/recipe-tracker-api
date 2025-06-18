<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecipeRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all authenticated users
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'ingredients' => 'required|string',
            'description' => 'required|string',
            'prep_time' => 'required|integer|min:0',
            'cook_time' => 'required|integer|min:0',
            'difficulty' => 'required|in:easy,medium,hard',
        ];
    }
}
