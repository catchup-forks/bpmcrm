<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Auth;
use App\Models\User;
use App\Models\JsonData;

final class ProfileController extends Controller
{

    /**
     * edit your profile.
     *
     * @return View|\Illuminate\Contracts\View
     */
    public function edit()
    {
        $currentUser = Auth::user();
        $states = JsonData::states();
        $countries = JsonData::countries();

        $timezones = array_reduce(JsonData::timezones(),
            function ($result, $item) {
                $result[$item] = $item;
                return $result;
            }
        );

        $datetimeFormats = array_reduce(JsonData::datetimeFormats(),
                                function ($result, $item) {
                                    $result[$item['format']] = $item['title'];
                                    return $result;
                                }
                            );

        return view('profile.edit',
            compact('currentUser', 'states', 'timezones', 'countries', 'datetimeFormats'));
    }

    /**
     * show other users profile
     *
     * @return View|\Illuminate\Contracts\View
     */
    public function show($id)
    {

        $user = User::findOrFail($id);

        return view('profile.show', compact('user'));
    }
}
