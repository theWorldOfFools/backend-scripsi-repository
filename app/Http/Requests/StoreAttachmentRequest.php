<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttachmentRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'ticket_id' => 'required|exists:tickets,id',
            'file' => 'required|file|max:10240' // max 10 MB
        ];
    }
}


?>