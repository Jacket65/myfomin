<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CategoryApiController extends Controller
{

     use ApiResponse;


     public function __construct(protected CategoryService $categoryService) {}
     /**
      * Returns a list of categories by type.
      */
     /**
      **
      * @OA\Get(
      *     path="/api/categories/{type}",
      *     summary="Get a list of categories by type (deposit or spend)",
      *     description="Returns a list of categories filtered by type. The 'type' parameter is required.",
      *     tags={"Categories"},
      *     @OA\Parameter(
      *         name="type",
      *         in="query",
      *         description="The type of categories to filter by",
      *         required=true,
      *         @OA\Schema(type="string")
      *     ),
      *     @OA\Response(
      *         response=200,
      *         description="List of category by type",
      *         @OA\JsonContent(
      *             type="array",
      *             @OA\Items(ref="#/components/schemas/Category")
      *         )
      *     ),
      *     @OA\Response(
      *         response=400,
      *         description="Missing required parameter 'type'",
      *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
      *     ),
      *     @OA\Response(
      *         response=500,
      *         description="Error retrieving categories",
      *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
      *     )
      * )
      */
     public function index(Request $request): JsonResponse
     {
          $categories = $this->categoryService->getCategoriesByType(request('type'));
          return $this->respond($categories, ResponseAlias::HTTP_OK);


          //        try {
          //            $type = $request->query('type');
          //
          //            if (!$type) {
          //                return ResponseAlias::json([
          //                    'success' => false,
          //                    'message' => 'The "type" parameter is required.',
          //                ], 400);
          //            }
          //
          //            $categories = Category::where('type', $type)->get();
          //
          //            return ResponseAlias::json([
          //                'success' => true,
          //                'data' => $categories,
          //                'message' => 'Categories successfully retrieved.',
          //            ], 200);
          //        } catch (Exception $e) {
          //            return ResponseAlias::json([
          //                'success' => false,
          //                'message' => 'Error retrieving categories.',
          //                'error' => $e->getMessage(),
          //            ], 500);
          //        }
     }
}
