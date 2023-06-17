<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {

      if (Auth::check()) {
          return redirect('/requests');
      }

      return redirect('/login');

    }
}
