<?php

namespace App\Tools\Import;

use App\Factory\NeoFactory;
use App\Repository\NeoRepository;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class Import
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly NeoRepository $repository,
        private readonly NeoFactory $factory,
        private readonly LoggerInterface $logger,
        private readonly string $apiUrl,
        private readonly string $apiKey,
        private ?int $days = null,
        private ?array $apiParams = [],
    ) {
    }

    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function import(int $iterations): void
    {
        do {
            try {
                $response = $this->client->request('GET', $this->apiUrl, $this->apiParams);
                $dataToImport = $this->processData($response->toArray());
                $this->repository->bulkSave($dataToImport);
                $this->setApiParameters(
                    $this->apiParams['query']['end_date'],
                    $this->days,
                );
            } catch (Throwable $exception) {
                $this->logger->critical($exception->getMessage());
            }
        } while (--$iterations > 0);
    }

    public function setApiParameters(string $startDate, int $days): Import
    {
        $this->days = $days;
        $this->apiParams['query'] = [
            'start_date' => $startDate,
            'end_date' => date('Y-m-d', strtotime(sprintf($startDate . ' + %d days', $days))),
            'api_key' => $this->apiKey,
        ];

        return $this;
    }

    /**
     * @throws Exception
     */
    private function processData(array $data): array
    {
        $resultData = [];

        foreach ($data['near_earth_objects'] ?? [] as $date => $asteroids) {
            foreach ($asteroids as $asteroid) {
                $neo = $this->repository->findOneBy(['neoReferenceId' => $asteroid['neo_reference_id']]);

                if (null === $neo) {
                    $asteroid['date'] = $date;
                    unset($asteroid['id']);
                    $resultData[] = $this->factory->create($asteroid);
                }
            }
        }

        return $resultData;
    }
}