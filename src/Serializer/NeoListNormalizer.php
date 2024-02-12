<?php

namespace App\Serializer;

use App\Tools\Paginator\Paginator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class NeoListNormalizer implements NormalizerInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly NeoNormalizer $neoNormalizer,
    ) {
    }

    public function supportsNormalization($data, ?string $format = null): bool
    {
        return $data instanceof Paginator;
    }

    /**
     * @throws ExceptionInterface
     */
    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $data = [];

        $data['links'] = $this->getLinks($object, $context['_route'] ?? '');
        $data['total'] = $object->getItems()->count();

        foreach ($object->getItems() ?? [] as $item) {
            $data['items'][] = $this->neoNormalizer->normalize($item);
        }

        return $data;
    }

    private function getLinks(Paginator $paginator, string $routeName): array
    {
        $links = [];
        $currentPage = $paginator->getControlElement()->getPage();
        $limit = $paginator->getControlElement()->getLimit();
        $pages = (int) ceil($paginator->getItems()->count() / $limit);

        $links['self'] = $this->urlGenerator->generate($routeName, ['page' => $currentPage, 'limit' => $limit], UrlGeneratorInterface::ABSOLUTE_URL);

        if ($currentPage > 1) {
            $links['previous'] = $this->urlGenerator->generate($routeName, ['page' => $currentPage - 1, 'limit' => $limit], UrlGeneratorInterface::ABSOLUTE_URL);
        }

        if ($currentPage < $pages) {
            $links['next'] = $this->urlGenerator->generate($routeName, ['page' => $currentPage + 1, 'limit' => $limit], UrlGeneratorInterface::ABSOLUTE_URL);
        }

        return $links;
    }
}