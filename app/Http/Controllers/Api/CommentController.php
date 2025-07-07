<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Services\CommentService;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }



    public function index()
    {
        return response()->json($this->commentService->getAll());
    }

    public function store(StoreCommentRequest $request)
    {
        $comment = $this->commentService->create($request->validated());
        return response()->json($comment, 201);
    }

        public function getStatus($ticketId)
    {
        $comments = $this->commentService->getByTicketId($ticketId);
        return response()->json($comments);
    }




    public function update(UpdateCommentRequest $request, $id)
    {
        $comment = $this->commentService->update($id, $request->validated());
        return response()->json($comment);
    }

    public function destroy($id)
    {
        $this->commentService->delete($id);
        return response()->json(['message' => 'Comment deleted.'], 204);
    }
}


?>