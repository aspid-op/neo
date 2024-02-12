<?php

namespace App\Serializer;

use App\Entity\NeoInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class NeoNormalizer implements ContextAwareNormalizerInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $router,
        private readonly ObjectNormalizer $normalizer,
    ) {
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
       return $data instanceof NeoInterface;
    }

    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        $data['links']['self'] = $this->router->generate('neo_item', [
            'id' => $object->getId()
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        return $data;
    }
}
