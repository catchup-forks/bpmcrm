<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Models\Group;

final class GroupController extends Controller
{
  /**
   * Get the list of groups.
   *
   * @return View|\Illuminate\Contracts\View
   */
  public function index()
  {
    return view('admin.groups.index');
  }

  /**
    * Get a specific group
    *
    * @return View|\Illuminate\Contracts\View
    */
   public function edit(Group $group)
   {
     return view('admin.groups.edit', compact('group'));
   }

   public function show(Group $group)
   {
       return view('admin.groups.show', compact('group'));
   }
}
