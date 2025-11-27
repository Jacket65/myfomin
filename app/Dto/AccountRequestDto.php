<?php

namespace App\Dto;
use App\Models\Account;
use Illuminate\Http\Request;


/**
* @OA\Schema(
 *     schema="AccountRequest",
 *     type="object",
 *     title="AccountRequest",
 *     description="Account model schema",
 *     required={"name", "amount"},
 *     @OA\Property(property="name", type="string", example="Main Account"),
 *     @OA\Property(property="amount", type="number", example=5000)
* )
 */
class AccountRequestDto {
    public string $name;
    public int $amount;

    public function __construct($name, $amount)
    {
        $this->name = $name;
        $this->amount = $amount;


    }

}

