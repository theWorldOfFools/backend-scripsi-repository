<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RedirectTicketTaskQuest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'ticket_id' => 'required',
            'assigned_to' => 'required|exists:users,id',
        ];
    }
}

?>