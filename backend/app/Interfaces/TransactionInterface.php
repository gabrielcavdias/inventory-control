<?php

namespace App\Interfaces;

use App\DTOs\TransactionProductList;

interface TransactionInterface
{
    public TransactionProductList $products { get; set; }
}
