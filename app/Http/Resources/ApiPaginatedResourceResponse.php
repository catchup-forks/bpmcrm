<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

final class ApiPaginatedResourceResponse extends PaginatedResourceResponse {
    /**
     * Add the pagination information to the response.
     *
     * @param Request $request
     * @return array{meta: mixed[]}
     */
    protected function paginationInformation($request)
    {
        $paginated = $this->resource->resource->toArray();
        return [
            'meta' => $this->meta($paginated),
        ];
    }
}

?>