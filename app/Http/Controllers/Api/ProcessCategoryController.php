<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProcessCategory;
use App\Http\Resources\ApiCollection;
use App\Http\Resources\ProcessCategory as Resource;

final class ProcessCategoryController extends Controller
{
    /**
     * Display a listing of the Process Categories.
     *
     * @return JsonResponse
     *
     * @OA\Get(
     *     path="/process_categories",
     *     summary="Returns all processes categories that the user has access to",
     *     operationId="getProcessCategories",
     *     tags={"ProcessCategories"},
     *     @OA\Parameter(ref="#/components/parameters/filter"),
     *     @OA\Parameter(ref="#/components/parameters/order_by"),
     *     @OA\Parameter(ref="#/components/parameters/order_direction"),
     *     @OA\Parameter(ref="#/components/parameters/per_page"),
     *     @OA\Parameter(ref="#/components/parameters/include"),
     *
     *     @OA\Response(
     *         response=200,
     *         description="list of processes categories",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/ProcessCategory"),
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 allOf={@OA\Schema(ref="#/components/schemas/metadata")},
     *             ),
     *         ),
     *     ),
     * )
     *     @OA\Response(
     *         response=200,
     *         description="list of processes categories",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/ProcessCategory"),
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 allOf={@OA\Schema(ref="#/components/schemas/metadata")},
     *             ),
     *         ),
     *     ),
     * )
     */
    public function index(Request $request): ApiCollection
    {
        $query = ProcessCategory::query();
        $include = $request->input('include', '');

        if ($include) {
            $include = explode(',', (string) $include);
            $count = array_search('processesCount', $include);
            if ($count !== false) {
                unset($include[$count]);
                $query->withCount('processes');
            }
            if ($include) {
                $query->with($include);
            }
        }

        $filter = $request->input('filter', '');
        if (!empty($filter)) {
            $query->where(function ($query) use ($filter): void {
                $query->Where('name', 'like', $filter)
                    ->orWhere('status', 'like', $filter);
            });
        }
        if ($request->has('status')) {
            $query->where('status', 'like', $request->input('status'));
        }
        $query->orderBy(
            $request->input('order_by', 'name'),
            $request->input('order_direction', 'asc')
        );
        $response = $query->paginate($request->input('per_page', 10));
        return new ApiCollection($response);
    }

    /**
     * Display the specified Process category.
     *
     *
     * @return JsonResponse
     *     * @OA\Get(
     *     path="/process_categories/process_category_id",
     *     summary="Get single process category by ID",
     *     operationId="getProcessCategoryById",
     *     tags={"ProcessCategories"},
     *     @OA\Parameter(
     *         description="ID of process category to return",
     *         in="path",
     *         name="process_category_id",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully found the process",
     *         @OA\JsonContent(ref="#/components/schemas/ProcessCategory")
     *     ),
     * )
     */
    public function show(ProcessCategory $processCategory): Resource
    {
        return new Resource($processCategory);
    }

    /**
     * Store a newly created Process Category in storage
     *
     *
     * @return JsonResponse
     *
     *     * @OA\Post(
     *     path="/process_categories",
     *     summary="Save a new process Category",
     *     operationId="createProcessCategory",
     *     tags={"ProcessCategories"},
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(ref="#/components/schemas/ProcessCategoryEditable")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="success",
     *         @OA\JsonContent(ref="#/components/schemas/ProcessCategory")
     *     ),
     * )
     */
    public function store(Request $request): Resource
    {
        $request->validate(ProcessCategory::rules());
        $category = new ProcessCategory();
        $category->fill($request->mediumText()->all());
        $category->saveOrFail();
        return new Resource($category);
    }

    /**
     * Updates the current element
     *
     *
     * @return ResponseFactory|Response
     *      * @OA\Put(
     *     path="/process_categories/process_category_id",
     *     summary="Update a process Category",
     *     operationId="updateProcessCategory",
     *     tags={"ProcessCategories"},
     *     @OA\Parameter(
     *         description="ID of process category to return",
     *         in="path",
     *         name="process_category_id",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(ref="#/components/schemas/ProcessCategoryEditable")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(ref="#/components/schemas/ProcessCategory")
     *     ),
     * )
     */
    public function update(Request $request, ProcessCategory $processCategory): Resource
    {
        $request->validate(ProcessCategory::rules($processCategory));
        $processCategory->fill($request->mediumText()->all());
        $processCategory->saveOrFail();
        return new Resource($processCategory);
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @return ResponseFactory|Response
     *
     *      * @OA\Delete(
     *     path="/process_categories/process_category_id",
     *     summary="Delete a process category",
     *     operationId="deleteProcessCategory",
     *     tags={"ProcessCategories"},
     *     @OA\Parameter(
     *         description="ID of process category to return",
     *         in="path",
     *         name="process_category_id",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="success",
     *         @OA\JsonContent(ref="#/components/schemas/Process")
     *     ),
     * )
     */
    public function destroy(ProcessCategory $processCategory)
    {
        if ($processCategory->processes->count() !== 0) {
            return response (
                ['message'=>'The item should not have associated processes',
                    'errors'=> ['processes' => $processCategory->processes->count()]],
                    422);
        }

        $processCategory->delete();
        return response('', 204);
    }
}
