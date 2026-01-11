<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepartemenRequest;
use App\Http\Requests\UpdateDepartemenRequest;
use App\Models\Departemen;
use App\Services\DepartemenService;
use Illuminate\Http\Request;

class DepartemenController extends Controller
{
    protected $service;

    public function __construct(DepartemenService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $results = $this->service->getAllPaginated(10, $request->input('page', 1));
        return response()->json($results);
        // return response()->json($this->service->all());
    }

    public function store(StoreDepartemenRequest $request)
    {
        // dd($request);
        return response()->json(
            $this->service->create($request->validated()),
            201,
        );
    }

    public function update(UpdateDepartemenRequest $request, Departemen $departemen)
    {
        return response()->json(
            $this->service->update($departemen, $request->validated()),
        );
    }

    public function destroy(Departemen $departemen)
    {
        $this->service->delete($departemen);
        return response()->json(null, 204);
    }
}

?>
