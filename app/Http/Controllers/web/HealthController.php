<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class HealthController extends Controller{
    public function check(): JsonResponse{
        return response()->json(['status' => 'ok'], 200);
    }
}