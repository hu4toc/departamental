<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class PersonaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombres' => 'required',
            'apellidos' => 'required',
            'ci' => 'required',
            'celular' => 'required',
            'tipo' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'nombres.required' => 'El campo nombres es requerido',
            'apellidos.required' => 'El campo apellidos es requerido',
            'ci.required' => 'El campo C.I. es requerido',
            'celular.required' => 'El campo celular es requerido',
            'tipo.required' => 'El campo tipo es requerido',
        ];
    }

    public function failedValidation(Validator $validator)
    {
       throw new HttpResponseException(response()->json([
         'success'   => false,
         'message'   => 'Validation errors',
         'data'      => $validator->errors()
       ]));
    }
}
