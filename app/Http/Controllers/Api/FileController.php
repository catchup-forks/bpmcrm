<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Horizon\Http\Controllers\Controller;
use App\Http\Resources\ApiCollection;
use App\Models\Media;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $query = Media::query();
        $filter = $request->input('filter', '');

        if (!empty($filter)) {
            $filter = '%' . $filter . '%';
            $query->where(function ($query) use ($filter) {
                $query->Where('file_name', 'like', $filter)
                    ->orWhere('mime_type', 'like', $filter);
            });
        }

        $query->orderBy(
            $request->input('order_by', 'updated_at'),
            $request->input('order_direction', 'asc')
        );

        $response = $query->paginate($request->input('per_page', 10));
        return new ApiCollection($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // We get the model instance with the data that the user sent
        $modelClass = 'app\\Models\\' . ucwords($request->query('model', null));
        $modelId = $request->query('model_id', null);

        // If no model info was sent in the request
        if ($modelClass === null || $modelId === null || !class_exists($modelClass)) {
            throw new NotFoundHttpException();
        }

        $model = $modelClass::find($modelId);

        // If we can't find the model's instance
        if ($model === null) {
            throw new NotFoundHttpException();
        }

        $addedMedia = $model->addMediaFromRequest('file')->toMediaCollection('local');

        return response([
            'id' => $addedMedia->id,
            'model_id' => $addedMedia->model_id,
            'file_name' => $addedMedia->file_name,
            'mime_type' => $addedMedia->mime_type
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param Media $file
     * @return Response
     */
    public function show(Media $file)
    {
        $path = Storage::disk('public_html')->getAdapter()->getPathPrefix() .
                $file->id . '/' .
                $file->file_name;
        return response()->download($path);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Media $file
     *
     * @return Response
     */
    public function update(Request $request, Media $file)
    {
        $newFile = $request->file('file');
        $newMedia = new \app\Media();
        $newMedia->updateFile($newFile, $file);
        return response([], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Media $file
     * @return Response
     *
     * @internal param int $id
     */
    public function destroy(Media $file)
    {
        // We get the model instance with the information that comes in the media
        $modelType = $file->model_type;
        $modelId = $file->model_id;
        $model = $modelType::find($modelId);

        $model->deleteMedia($file->id);

        return response([], 204);
    }
}
