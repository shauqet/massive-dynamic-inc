<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store User request",
 *      description="Store User request body data",
 *      type="object"
 * )
 */
class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            "name"      => "required|max:50",
            "username"  => "required|max:20",
            "email"     => "required|email",
            "role"      => "required|integer|min:1|digits_between:1,3",
        ];

        $rules["password"] = $this->isMethod('PATCH') ? "nullable|min:8" : "required|min:8";

        return $rules;
    }

    /**
     * Validation messages if validation fails
     *
     * @return array
     */
    public function messages() {
        return [];
    }

}
