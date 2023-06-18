<?php
namespace App\Http\Resources;

use App\Http\Resources\Users;

final class Task extends ApiResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $array = parent::toArray($request);
        $include = explode(',', (string) $request->input('include', ''));
        if (in_array('user', $include)) {
            $array['user'] = new Users($this->user);
        }
        if (in_array('definition', $include)) {
            $array['definition'] = $this->getDefinition();
        }
        return $array;
    }
}
