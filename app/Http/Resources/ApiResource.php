<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ApiResource extends JsonResource
{
    /**
     * Generic resource for outputting models
     *
     * @param Request $request
     * @return array
     */
    static $wrap;
}
