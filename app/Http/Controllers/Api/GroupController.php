<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiCollection;
use App\Models\Group;
use App\Http\Resources\Groups as GroupResource;

class GroupController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return ApiCollection
     *
     * @OA\Get(
     *     path="/groups",
     *     summary="Returns all groups that the user has access to",
     *     operationId="getGroups",
     *     tags={"Groups"},
     *     @OA\Parameter(ref="#/components/parameters/filter"),
     *     @OA\Parameter(ref="#/components/parameters/order_by"),
     *     @OA\Parameter(ref="#/components/parameters/order_direction"),
     *     @OA\Parameter(ref="#/components/parameters/per_page"),
     *     @OA\Parameter(ref="#/components/parameters/include"),
     *
     *     @OA\Response(
     *         response=200,
     *         description="list of groups",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/groups"),
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
    public function index(Request $request)
    {
        $include = $request->input('include', '');
        $query = Group::query();
        if ($include) {
            $include = explode(',', $include);
            $count = array_search('membersCount', $include);
            if ($count !== false) {
                unset($include[$count]);
                $query->withCount('groupMembers');
            }
            if ($include) {
                $query->with($include);
            }
        }
        $filter = $request->input('filter', '');
        if (!empty($filter)) {
            $filter = '%' . $filter . '%';
            $query->where(function ($query) use ($filter) {
                $query->Where('name', 'like', $filter)
                    ->orWhere('description', 'like', $filter)
                    ->orWhere('status', 'like', $filter);
            });
        }
        $response =
            $query->orderBy(
                $request->input('order_by', 'name'),
                $request->input('order_direction', 'ASC')
            )
                ->paginate($request->input('per_page', 10));
        return new ApiCollection($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return GroupResource
     * @throws \Throwable
     *
     * @OA\Post(
     *     path="/groups",
     *     summary="Save a new groups",
     *     operationId="createGroup",
     *     tags={"Groups"},
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(ref="#/components/schemas/groupsEditable")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="success",
     *         @OA\JsonContent(ref="#/components/schemas/groups")
     *     ),
     * )
     */
    public function store(Request $request)
    {
        $request->validate(Group::rules());
        $group = new Group();
        $group->fill($request->input());
        $group->saveOrFail();
        return new GroupResource($group);
    }

    /**
     * Display the specified resource.
     *
     * @param  id $id
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/groups/groupId",
     *     summary="Get single group by ID",
     *     operationId="getGroupById",
     *     tags={"Groups"},
     *     @OA\Parameter(
     *         description="ID of group to return",
     *         in="path",
     *         name="group_id",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully found the group",
     *         @OA\JsonContent(ref="#/components/schemas/groups")
     *     ),
     * )
     */
    public function show(Group $group)
    {
        return new GroupResource($group);
    }

    /**
     * Update a user
     *
     * @param Group $group
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Throwable
     *
     * @OA\Put(
     *     path="/groups/groupId",
     *     summary="Update a group",
     *     operationId="updateGroup",
     *     tags={"Groups"},
     *     @OA\Parameter(
     *         description="ID of group to return",
     *         in="path",
     *         name="group_id",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(ref="#/components/schemas/groupsEditable")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(ref="#/components/schemas/groups")
     *     ),
     * )
     */
    public function update(Group $group, Request $request)
    {
        $request->validate(Group::rules($group));
        $group->fill($request->input());
        $group->saveOrFail();
        return response([], 204);
    }

    /**
     * Delete a user
     *
     * @param Group $user
     *
     * @return ResponseFactory|Response
     *
     * @OA\Delete(
     *     path="/groups/groupId",
     *     summary="Delete a group",
     *     operationId="deleteGroup",
     *     tags={"Groups"},
     *     @OA\Parameter(
     *         description="ID of group to return",
     *         in="path",
     *         name="group_id",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="success",
     *         @OA\JsonContent(ref="#/components/schemas/groups")
     *     ),
     * )
     */
    public function destroy(Group $group)
    {
        $group->delete();
        return response([], 204);
    }
}
