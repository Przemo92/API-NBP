<?php

namespace App\Command;

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
    private string $apiUrl = 'https://api.nbp.pl/api/exchangerates/tables/A/2013-01-31/?format=json';

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
            return 1;
        }

        $jsonCurrencyData = json_decode($response->getBody(), true);
        dump($jsonCurrencyData[0]);

        return Command::SUCCESS;
    }
}
