<?php

namespace App\Http\Controllers\Api;

use App\Dto\TransactionDto;
use App\Dto\TransactionRequestDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Models\Account;
use App\Models\Transaction;
use App\Services\TransactionService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

/**
 * @OA\Tag(
 *     name="Transactions",
 *     description="User transaction operations"
 * )
 */

class TransactionApiController extends Controller
{
    use ApiResponse;

    public function __construct(protected TransactionService $transactionService) {}
    /**
     * Returns a list of all transactions with categories
     */

    /**
     * @OA\Get(
     *     path="/api/transactions",
     *     summary="Get all transactions with categories and accounts",
     *     description="Returns a list of all transactions ordered by creation date (descending)",
     *     tags={"Transactions"},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved transaction list",
     *         @OA\JsonContent(
     *              type="array",
     *             @OA\Items(ref="#/components/schemas/Transaction")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $transaction = $this->transactionService->get();
        return $this->respond($transaction, ResponseAlias::HTTP_OK);
        //
        //        $transactions = Transaction::with('category', 'account')
        //            ->orderBy('created_at', 'desc')
        //            ->get();
        //
        //        return ResponseAlias::json([
        //            'success' => true,
        //            'data' => $transactions
        //        ], 200);
    }

    /**
     * Creates a new transaction (deposit or spend)
     */
    /**
     * @OA\Post(
     *     path="/api/transactions",
     *     summary="Create a new transaction (deposit or spend)",
     *     description="Adds a new transaction and updates the account balance.",
     *     tags={"Transactions"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"type","account_id","category_id","amount"},
     *             @OA\Property(property="type", type="string", enum={"deposit","withdrawal"}, example="deposit", description="Transaction type"),
     *             @OA\Property(property="account_id", type="integer", example=1, description="Account ID"),
     *             @OA\Property(property="category_id", type="integer", example=3, description="Category ID"),
     *             @OA\Property(property="amount", type="number", format="float", example=1500.50, description="Transaction amount"),
     *             @OA\Property(property="description", type="string", example="Deposit from client", description="Transaction description (optional)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Transaction created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Transaction created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Transaction")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request data",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error creating transaction",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function store(TransactionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $dto = new TransactionRequestDto($data);
        $dtoResponse = $this->transactionService->store($dto);
        return $this->respond($dtoResponse, ResponseAlias::HTTP_CREATED);
    }

    /**
     * Update a transaction.
     *
     * @OA\Put(
     *     path="/api/transactions/{id}",
     *     summary="Update a transaction",
     *     tags={"Transactions"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Transaction ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TransactionDto")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transaction updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TransactionDto")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request data"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaction not found"
     *     )
     * )
     */

    public function update(TransactionRequest $request, $id): JsonResponse
    {
        $transaction =  $request->validated();
        $transaction['id'] = $id;
        $dto = new TransactionDto($transaction);
        $this->transactionService->update($dto);
        return $this->respond($dto, ResponseAlias::HTTP_OK);
    }



    /**
     * Delete a transaction.
     *
     * @OA\Delete(
     *     path="/api/transactions/{id}",
     *     summary="Delete a transaction",
     *     tags={"Transactions"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Transaction ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Transaction deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaction not found"
     *     )
     * )
     */

    public function destroy(int $id): Response|JsonResponse
    {
        if ($this->transactionService->deleteByID($id)) {
            return $this->respondNodata();
        } else {
            return $this->error("Not Found", "there is no record with such id", ResponseAlias::HTTP_NOT_FOUND);
        }
    }
}
