<?php

namespace App\Ecommerce;

use Akaunting\Money\Currency;
use Akaunting\Money\Money as BaseMoney;

class Money
{
    protected $money;

    public function __construct($value)
    {
        $this->money = new BaseMoney($value, new Currency('USD'));
    }

    public function amount()
    {
        return $this->money->getAmount();
    }

    public function formatted()
    {
        return BaseMoney::USD($this->money)->format();
    }
}
