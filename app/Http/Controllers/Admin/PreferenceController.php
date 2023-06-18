<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Models\JsonData;

class PreferenceController extends Controller
{
  /**
   * Get the preferences form
   *
   * @return View|\Illuminate\Contracts\View
   */
  public function index()
  {
      $timezones = JsonData::timezones();
      return view('admin.preferences.index', compact('timezones'));
  }
}
