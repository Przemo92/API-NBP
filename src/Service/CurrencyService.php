<?php

namespace App\Service;

use App\Controller\CurrencyController;
use App\Repository\CurrencyRepository;

class CurrencyService
{
    private CurrencyRepository $currencyRepository;
    private CurrencyController $currencyController;

    public function __construct(
        CurrencyRepository $currencyRepository,
        CurrencyController $currencyController
    )
    {
        $this->currencyRepository = $currencyRepository;
        $this->currencyController = $currencyController;
    }

    public function processApiCurrencyData($data): void
    {
        foreach ($data as $currency) {
            if ($this->isCorrectCurrencyData($currency)) {
                $currencyFromDataBase = $this->currencyRepository->findOneBy(['currencyCode' => $currency['code']]);

                if ($currencyFromDataBase) {
                    if ($currencyFromDataBase->getExchangeRate() !== $currency['mid']) {
                        $updatedCurrency = $this->currencyController->updateCurrencyExchangeRate($currencyFromDataBase, $currency['mid']);
                        $this->currencyRepository->store($updatedCurrency);
                    }
                } else {
                    $newCurrency = $this->currencyController->createCurrency($currency);
                    $this->currencyRepository->store($newCurrency);
                }
            }
        }
        $this->currencyRepository->saveEntities();
    }

    private function isCorrectCurrencyData($currency): bool
    {
        if (isset($currency['code']) && isset($currency['mid']) && isset($currency['currency'])) {
            return true;
        } else {
            return false;
        }
    }
}