<?php

namespace App\Factory;

use App\Entity\Neo;
use App\Entity\NeoInterface;
use DateTime;
use Exception;
use InvalidArgumentException;

class NeoFactory implements FactoryInterface
{
    /**
     * @throws Exception
     */
    public function create(array $arguments = []): NeoInterface
    {
        $name = $arguments['name'] ?? $this->throwException($arguments['name']);
        $date = $arguments['date'] ?? $this->throwException($arguments['date']);

        return new Neo(
            id:$arguments['id'] ?? null,
            date: new DateTime($date),
            neoReferenceId: $arguments['neo_reference_id'] ?? $this->throwException($arguments['neo_reference_id']),
            name: $name,
            speed: $arguments['close_approach_data'][0]['relative_velocity']['kilometers_per_hour'] ?? $this->throwException($arguments['close_approach_data']['relative_velocity']['kilometers_per_hour']),
            isHazardous: $arguments['is_potentially_hazardous_asteroid'] ?? $this->throwException($arguments['is_potentially_hazardous_asteroid'])
        );
    }

    private function throwException(mixed $argument): void
    {
        throw new InvalidArgumentException(sprintf(
            'Argument %s should not be null.',
            $argument,
        ));
    }
}
