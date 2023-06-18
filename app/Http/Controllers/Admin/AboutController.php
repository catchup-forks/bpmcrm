<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Http\Controllers\Controller;

class AboutController extends Controller
{
   /**
   * Get the list of users.
   *
   * @return View|\Illuminate\Contracts\View
   */
  public function index()
  {
      return view('admin.about.index');
  }
}
