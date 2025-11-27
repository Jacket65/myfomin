<?php

namespace App\Http\Controllers\Api;
use App\Dto\AccountRequestDto;
use App\Dto\AccountDto;
use App\Dto\AccountResponseDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Services\AccountService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

use Illuminate\Http\Request;
/**
 * @OA\Tag(
 *     name="Accounts",
 *     description="API Endpoints for managing accounts"
 * )
 */
class AccountApiController extends Controller
{
    protected AccountService $accountService;
    use ApiResponse;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }
    /**
     * @OA\Get(
     *     path="/api/accounts",
     *     summary="Get list of all accounts",
     *     tags={"Accounts"},
     *     @OA\Response(
     *         response=200,
     *         description="List of accounts returned successfully",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/AccountResponse"))
     *     )
     * )
     */
    public function index(): JsonResponse {
        return $this->respond($this->accountService->getAll(), ResponseAlias::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/accounts",
     *     summary="Create a new account",
     *     tags={"Accounts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AccountRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Account created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/AccountResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent (
     *             @OA\Property(property="err", type="string")
     *         )
     *     )
     * )
     */

    public function store(AccountRequest $request)
    {
            $data =  $request->validated();
            $dto = new AccountRequestDto($data['name'], $data['amount']);
            $accountResponse = $this->accountService->store($dto);
            return $this->respond($accountResponse, ResponseAlias::HTTP_CREATED);

    }

    /**
     * @OA\Delete(
     *     path="/api/accounts/{id}",
     *     summary="Delete an account",
     *     tags={"Accounts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of account to delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Account deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Account not found"
     *     )
     * )
     */
    public function destroy($accountID): \Illuminate\Http\Response|JsonResponse
    {
        if ($this->accountService->deleteByID($accountID)) {
            return $this->respondNodata();
        } else {
            return $this->error('NotFound',  'there is no record with such id', ResponseAlias::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/accounts/{id}",
     *     summary="Update an existing account",
     *     tags={"Accounts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of account to update",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "amount"},
     *             @OA\Property(property="name", type="string", example="Updated Account"),
     *             @OA\Property(property="amount", type="number", example=7000)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Account updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/AccountResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Account not found"
     *     )
     * )
     */

    public function update(AccountRequest $request, $id) {
        $data = $request->validated();
        $dto = new AccountDto($id, $data['name'], $data['amount']);
        $accountDto = $this->accountService->update($dto);
        return $this->respond($accountDto, ResponseAlias::HTTP_OK);
    }
}
