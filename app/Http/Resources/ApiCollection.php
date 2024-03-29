<?php

namespace App\Http\Resources;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;

/**
 *  @OA\Schema(
 *    schema="metadata",
 *    @OA\Property(property="filter", type="string"),
 *    @OA\Property(property="sort_by", type="string"),
 *    @OA\Property(property="sort_order", type="string", enum={"ASC", "DESC"}),
 *    @OA\Property(property="count", type="integer"),
 *    @OA\Property(property="total_pages", type="integer"),
 *    
 *    @OA\Property(property="current_page", type="integer"),
 *    @OA\Property(property="form", type="integer"),
 *    @OA\Property(property="last_page", type="integer"),
 *    @OA\Property(property="path", type="string"),
 *    @OA\Property(property="per_page", type="integer"),
 *    @OA\Property(property="to", type="integer"),
 *    @OA\Property(property="total", type="integer"),
 *  )
 */
final class ApiCollection extends ResourceCollection
{
    /**
     * Generic collection to add sorting and filtering metadata.
     *
     * @param Request $request
     * @return array{data: Collection, meta: array{filter: mixed, sort_by: mixed, sort_order: mixed, count: mixed, total_pages: float}}
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'filter' => $request->input('filter', ''),
                'sort_by' => $request->input('order_by', ''),
                'sort_order' => $request->input('order_direction', ''),
                /**
                 * count: (integer, total items in current response)
                 */
                'count' => $this->resource->count(),
                /**
                 * total_pages: (integer, the total number of pages available, based on per_page and total)
                 */
                'total_pages' => ceil($this->resource->total() / $this->resource->perPage())
            ]
        ];
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function toResponse($request)
    {
        return $this->resource instanceof AbstractPaginator
                    ? (new ApiPaginatedResourceResponse($this))->toResponse($request)
                    : parent::toResponse($request);
    }
}
