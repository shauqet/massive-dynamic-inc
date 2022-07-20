<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store User request",
 *      description="Store User request body data",
 *      type="object",
 *      required={"name"}
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
        return [
            "name"      => "required|max:50",
            "username"  => "required|max:20",
            "email"     => "required|email",
            "role"      => "required|integer|min:1|digits_between:1,3",
        ];
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
