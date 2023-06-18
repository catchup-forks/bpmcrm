<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Screen;
use App\Http\Resources\ApiResource;
use App\Http\Resources\ApiCollection;

final class ScreenController extends Controller
{
    /**
     * Get a list of Screens.
     *
     *
     * @return ResponseFactory|Response
     *
     *     @OA\Get(
     *     path="/screens",
     *     summary="Returns all screens that the user has access to",
     *     operationId="getScreens",
     *     tags={"Screens"},
     *     @OA\Parameter(ref="#/components/parameters/filter"),
     *     @OA\Parameter(ref="#/components/parameters/order_by"),
     *     @OA\Parameter(ref="#/components/parameters/order_direction"),
     *     @OA\Parameter(ref="#/components/parameters/per_page"),
     *     @OA\Parameter(ref="#/components/parameters/include"),
     *
     *     @OA\Response(
     *         response=200,
     *         description="list of screens",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/screens"),
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
     *         description="list of screens",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/screens"),
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
        $query = Screen::query();

        $filter = $request->input('filter', '');
        if (!empty($filter)) {
            $filter = '%' . $filter . '%';
            $query->where(function ($query) use ($filter): void {
                $query->where('title', 'like', $filter)
                    ->orWhere('description', 'like', $filter)
                    ->orWhere('type', 'like', $filter)
                    ->orWhere('config', 'like', $filter);
            });
        }

        $response =
            $query->orderBy(
                $request->input('order_by', 'title'),
                $request->input('order_direction', 'ASC')
            )->paginate($request->input('per_page', 10));
        return new ApiCollection($response);
    }

    /**
     * Get a single Screen.
     *
     *
     * @return ResponseFactory|Response
     *     @OA\Get(
     *     path="/screens/screensId",
     *     summary="Get single screens by ID",
     *     operationId="getScreensById",
     *     tags={"Screens"},
     *     @OA\Parameter(
     *         description="ID of screens to return",
     *         in="path",
     *         name="screens_id",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully found the screens",
     *         @OA\JsonContent(ref="#/components/schemas/screens")
     *     ),
     * )
     */
    public function show(Screen $screen): ApiResource
    {
        return new ApiResource($screen);
    }

    /**
     * Create a new Screen.
     *
     *
     * @return ResponseFactory|Response
     *     @OA\Post(
     *     path="/screens",
     *     summary="Save a new screens",
     *     operationId="createScreens",
     *     tags={"Screens"},
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(ref="#/components/schemas/screensEditable")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="success",
     *         @OA\JsonContent(ref="#/components/schemas/screens")
     *     ),
     * )
     */
    public function store(Request $request): ApiResource
    {
        $request->validate(Screen::rules());
        $screen = new Screen();
        $screen->fill($request->input());
        $screen->saveOrFail();
        return new ApiResource($screen);
    }

    /**
     * Update a Screen.
     *
     *
     * @return ResponseFactory|Response
     *
     *     @OA\Put(
     *     path="/screens/screensId",
     *     summary="Update a screen",
     *     operationId="updateScreen",
     *     tags={"Screens"},
     *     @OA\Parameter(
     *         description="ID of screen to return",
     *         in="path",
     *         name="screens_id",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(ref="#/components/schemas/screensEditable")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(ref="#/components/schemas/screens")
     *     ),
     * )
     */
    public function update(Screen $screen, Request $request)
    {
        $request->validate(Screen::rules($screen));
        $screen->fill($request->input());
        $screen->saveOrFail();

        return response([], 204);
    }

    /**
     * Delete a Screen.
     *
     *
     * @return ResponseFactory|Response
     *     @OA\Delete(
     *     path="/screens/screensId",
     *     summary="Delete a screen",
     *     operationId="deleteScreen",
     *     tags={"Screens"},
     *     @OA\Parameter(
     *         description="ID of screen to return",
     *         in="path",
     *         name="screens_id",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="success",
     *         @OA\JsonContent(ref="#/components/schemas/screens")
     *     ),
     * )
     */
    public function destroy(Screen $screen)
    {
        $screen->delete();
        return response([], 204);
    }

}
