<?php

namespace App\Services;

use App\Models\TicketAttachment;
use Illuminate\Support\Facades\Storage;

class AttachmentService
{
    public function upload(array $data, $file)
    {
        $path = $file->store("attachments", "public");
        return TicketAttachment::create([
            "ticket_id" => $data["ticket_id"],
            "file_url" => Storage::url($path),
            "file_name" => $file->getClientOriginalName(),
        ]);
    }

    public function delete($id)
    {
        $attachment = TicketAttachment::findOrFail($id);
        $relativePath = str_replace("/storage/", "", $attachment->file_url);
        Storage::disk("public")->delete($relativePath);
        $attachment->delete();
    }
}
