<?php 
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'no_telepon'=>'string|min:7',
            'password' => 'required|string|min:6',
        ];
    }
}

?>