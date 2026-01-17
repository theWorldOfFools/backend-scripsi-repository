<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttachmentRequest;
use App\Services\AttachmentService;

class AttachmentController extends Controller
{
    protected $attachmentService;

    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    public function store(StoreAttachmentRequest $request)
    {
        $attachment = $this->attachmentService->upload($request->validated(), $request->file('file'));
        return response()->json($attachment, 201);
    }

    public function destroy($id)
    {
        $this->attachmentService->delete($id);
        return response()->json(['message' => 'Attachment deleted'], 204);
    }
}


?>