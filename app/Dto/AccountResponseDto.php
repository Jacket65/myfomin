<?php

namespace App\Dto;
use App\Models\Account;
use Illuminate\Http\Request;


/**
 * @OA\Schema(
 *     schema="AccountResponse",
 *     type="object",
 *     title="AccountResponse",
 *     description="Account model schema",
 *     required={"id", "name", "amount"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Main Account"),
 *     @OA\Property(property="amount", type="number", example=5000)
 * )
 */
class AccountResponseDto {
    public int $id;
    public string $name;
    public int $amount;

    public function __construct(Account $account)
    {
        $this->id = $account->id;
        $this->name = $account->name;
        $this->amount = $account->amount;
    }
}

