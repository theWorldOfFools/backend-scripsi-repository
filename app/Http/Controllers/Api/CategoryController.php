<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    protected $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->all());
    }

    public function store(StoreCategoryRequest $request)
    {
        return response()->json($this->service->create($request->validated()), 201);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        return response()->json($this->service->update($category, $request->validated()));
    }

    public function destroy(Category $category)
    {
        $this->service->delete($category);
        return response()->json(null, 204);
    }
}


?>