<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiCollection;
use App\Http\Resources\Script as ScriptResource;
use App\Models\Script;

final class ScriptController extends Controller
{
    /**
     * Get a list of scripts in a process.
     *
     * @param Process $process
     *
     * @return ResponseFactory|Response
     * 
     *     
     *     @OA\Get(
     *     path="/scripts",
     *     summary="Returns all scripts that the user has access to",
     *     operationId="getScripts",
     *     tags={"Scripts"},
     *     @OA\Parameter(ref="#/components/parameters/filter"),
     *     @OA\Parameter(ref="#/components/parameters/order_by"),
     *     @OA\Parameter(ref="#/components/parameters/order_direction"),
     *     @OA\Parameter(ref="#/components/parameters/per_page"),
     *     @OA\Parameter(ref="#/components/parameters/include"),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="list of scripts",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/scripts"),
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
        // Do not return results when a key is set. Those are for connectors.
        $query = Script::where('key', null);

        $filter = $request->input('filter', '');
        if (!empty($filter)) {
            $filter = '%' . $filter . '%';
            $query->where(function ($query) use ($filter): void {
                $query->Where('title', 'like', $filter)
                    ->orWhere('description', 'like', $filter)
                    ->orWhere('language', 'like', $filter);
            });
        }

        $response =
            $query->orderBy(
                $request->input('order_by', 'title'),
                $request->input('order_direction', 'ASC')
            )
            ->paginate($request->input('per_page', 10));

        return new ApiCollection($response);
    }

    /**
     * Previews executing a script, with sample data/config data
     * 
     *     @OA\Get(
     *     path="/scripts/ew",
     *     summary="Returns all scripts that the user has access to",
     *     operationId="getScriptsPreview",
     *     tags={"Scripts"},
     *         @OA\Parameter(
     *             name="data",
     *             in="query",
     *             @OA\Schema(type="string"),
     *         ),
     *         @OA\Parameter(
     *             name="config",
     *             in="query",
     *             @OA\Schema(type="string"),
     *         ),
     *         @OA\Parameter(
     *             name="code",
     *             in="query",
     *             @OA\Schema(type="string"),
     *         ),
     *         @OA\Parameter(
     *             name="language",
     *             in="query",
     *             @OA\Schema(type="string"),
     *         ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="output of scripts",
     *         @OA\JsonContent()
     *         ),
     *     ),
     * )
     */
    public function preview(Request $request): array
    {
        $data = json_decode((string) $request->get('data'), true, 512, JSON_THROW_ON_ERROR) ?: [];
        $config = json_decode((string) $request->get('config'), true, 512, JSON_THROW_ON_ERROR) ?: [];
        $code = $request->get('code');
        $language = $request->get('language');
        $script = new Script([
            'code' => $code,
            'language' => $language,
        ]);
        return $script->runScript($data, $config);
    }

    /**
     * Get a single script in a process.
     *
     *
     * @return ResponseFactory|Response
     *     @OA\Get(
     *     path="/scripts/scriptsId",
     *     summary="Get single script by ID",
     *     operationId="getScriptsById",
     *     tags={"Scripts"},
     *     @OA\Parameter(
     *         description="ID of script to return",
     *         in="path",
     *         name="script_id",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully found the script",
     *         @OA\JsonContent(ref="#/components/schemas/scripts")
     *     ),
     * )
     */
    public function show(Script $script): ScriptResource
    {
        return new ScriptResource($script);
    }

    /**
     * Create a new script in a process.
     *
     *
     * @return ResponseFactory|Response
     *     @OA\Post(
     *     path="/scripts",
     *     summary="Save a new script",
     *     operationId="createScript",
     *     tags={"Scripts"},
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(ref="#/components/schemas/scriptsEditable")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="success",
     *         @OA\JsonContent(ref="#/components/schemas/scripts")
     *     ),
     * )
     */
    public function store(Request $request): ScriptResource
    {
        $request->validate(Script::rules());
        $script = new Script();
        $script->fill($request->input());
        $script->saveOrFail();
        return new ScriptResource($script);
    }

    /**
     * Update a script in a process.
     *
     * @param Process $process
     *
     * @return ResponseFactory|Response
     *
     *     @OA\Put(
     *     path="/scripts/scriptsId",
     *     summary="Update a script",
     *     operationId="updateScript",
     *     tags={"Scripts"},
     *     @OA\Parameter(
     *         description="ID of script to return",
     *         in="path",
     *         name="script_id",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(ref="#/components/schemas/scriptsEditable")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(ref="#/components/schemas/scripts")
     *     ),
     * )
     */
    public function update(Script $script, Request $request)
    {
        $request->validate(Script::rules($script));

        $script->fill($request->input());
        $script->saveOrFail();

        return response($request, 204);
    }

    /**
     * Delete a script in a process.
     *
     *
     * @return ResponseFactory|Response
     *     @OA\Delete(
     *     path="/scripts/scriptsId",
     *     summary="Delete a script",
     *     operationId="deleteScript",
     *     tags={"Scripts"},
     *     @OA\Parameter(
     *         description="ID of script to return",
     *         in="path",
     *         name="script_id",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="success",
     *         @OA\JsonContent(ref="#/components/schemas/scripts")
     *     ),
     * )
     */
    public function destroy(Script $script)
    {
        $script->delete();
        return response([], 204);
    }
}
