<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $type = request('type');
        $user = auth()->user();
        $categories = $user->categories()->where('type', $type)->get();
        return response()->json($categories);
    }
}
