<?php

namespace App\Controller;

use App\Entity\Currency;

class CurrencyController
{

    public function createCurrency($data): Currency
    {
        $currency = new Currency();

        $currency->setName($data['currency']);
        $currency->setCurrencyCode($data['code']);
        $currency->setExchangeRate($data['mid']);

        return $currency;
    }

    public function updateCurrencyExchangeRate($currency, $newExchangeRate): Currency
    {
        $currency->setExchangeRate($newExchangeRate);
        return $currency;
    }
}