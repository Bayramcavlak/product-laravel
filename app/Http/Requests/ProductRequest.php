<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class ProductRequest extends FormRequest
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
            'name' => 'required|string',
            'price' => 'required|numeric',
            'status' => 'required|in:active,inactive,out_of_stock,pre_order,coming_soon,discontinued',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:mal,hizmet',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Ürün adı zorunludur.',
            'price.required' => 'Ürün fiyatı zorunludur.',
            'status.required' => 'Ürün durumu zorunludur.',
            'user_id.required' => 'Ürün sahibi zorunludur.',
            'type.required' => 'Ürün tipi zorunludur.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */

    public function attributes(): array
    {
        return [
            'name' => 'Ürün adı',
            'price' => 'Ürün fiyatı',
            'status' => 'Ürün durumu',
            'user_id' => 'Ürün sahibi',
            'type' => 'Ürün tipi',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $response = new JsonResponse([
            'status' => 'error',
            'message' => 'The given data was invalid.',
            'errors' => $errors,
        ], 422);

        throw new HttpResponseException($response);
    }
}
