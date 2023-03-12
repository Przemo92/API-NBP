<?php

namespace App\Command;

use App\Service\CurrencyService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'update:currency',
    description: 'Update currency entity in database based on API NBP.',
)]
class UpdateDataCurrencyCommand extends Command
{
    // I am using below api instead of http://api.nbp.pl/api/exchangerates/tables/A/today/,
    // because it was not working (12.03.2023).
    private string $apiUrl = 'https://api.nbp.pl/api/exchangerates/tables/A/2013-01-31/?format=json';
    private CurrencyService $currencyService;

    public function __construct(

        CurrencyService $currencyService
    )
    {
        parent::__construct(null);
        $this->currencyService = $currencyService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $client = new Client([
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);

        try {
            $response = $client->request('GET', $this->apiUrl);
        } catch (GuzzleException $e) {
            $output->writeln('Fetching data for url: ' . $this->apiUrl . ' FAILED');
            return Command::FAILURE;
        }

        $jsonCurrencyData = json_decode($response->getBody(), true);

        if (isset($jsonCurrencyData[0]['rates']) && is_array($jsonCurrencyData[0]['rates'])) {
            $this->currencyService->processApiCurrencyData($jsonCurrencyData[0]['rates']);
        } else {
            $output->writeln('Not valid data from api');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
