<?php

namespace App\Dto;
class AccountDto {
    public int $id;
    public string $name;
    public int $amount;

public function __construct($id, $name, $amount)
    {
        $this->id = $id;
        $this->name = $name;
        $this->amount = $amount;
    }

}
