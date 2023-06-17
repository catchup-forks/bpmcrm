<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProcessRequests extends ApiResource
{
    public function toArray($request)
    {
        $array = parent::toArray($request);
        $include = explode(',', $request->input('include', ''));

        if (in_array('summary', $include)) {
            $array['summary'] = $this->summary();
        }
        if (in_array('participants', $include)) {
            $array['participants'] = $this->participants;
        }
        if (in_array('user', $include)) {
            $array['user'] = new Users($this->user);
        }
        return $array;
    }
}
