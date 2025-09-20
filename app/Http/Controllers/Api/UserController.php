<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return response()->json($this->userService->getAll());
    }

    public function store(StoreUserRequest $request)
    {
        $ticket = $this->userService->create($request->validated());
        return response()->json($ticket, 201);
    }

    public function show($id)
    {
        return response()->json($this->userService->getById($id));
    }

public function myDetailProfile($userId)
{
    return response()->json($this->userService->getDetailUser($userId));
}



    public function update(Request $request, $id)
    {
        return response()->json($this->userService->update($id, $request->all()));
    }

    public function destroy($id)
    {
        $this->userService->delete($id);
        return response()->json(['message' => 'Deleted'], 204);
    }
}


?>