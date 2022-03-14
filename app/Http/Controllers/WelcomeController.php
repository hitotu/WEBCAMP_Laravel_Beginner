<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class WelcomeController extends Controller
{
  /**
   Route::get('/', function () {
      return view('welcome');
   });
  */
  Route::get('/', [\App\Http\Controllers\WelcomeController::class, 'index']);
    public function index()
    {
        return view('welcome');
    }
}