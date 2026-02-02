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
        $results = $this->userService->getAllPaginated(10, 1);
        // return response()->json($this->userService->getAll());
        return response()->json($results);
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


    public function getUserDataByDepartemen($departemen_id)
    {
//        var_dump($departemen_id);die;
        return response()->json($this->userService->getDataByDepartemen($departemen_id));
    }

    public function myDetailProfile($userId)
    {
        return response()->json($this->userService->getDetailUser($userId));
    }

    public function update(Request $request, $id)
    {
        return response()->json(
            $this->userService->update($id, $request->all()),
        );
    }

    public function destroy($id)
    {
        $this->userService->delete($id);
        return response()->json(["message" => "Deleted"], 204);
    }

    /**
     * KPI Teknisi
     */
    public function kpiTechnician()
    {
        $kpi = $this->userService->getTechnicianKpi();

        return response()->json([
            "status" => "success",
            "data" => $kpi,
        ]);
    }
}

?>
