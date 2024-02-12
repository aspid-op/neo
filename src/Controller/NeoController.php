<?php

namespace App\Controller;

use App\Factory\FindNeoQueryFactory;
use App\Serializer\NeoListNormalizer;
use App\Serializer\NeoNormalizer;
use App\Repository\NeoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController()]
class NeoController extends AbstractController
{
    /**
     * @throws ExceptionInterface
     */
    #[Route(path: '/v1/neo', name: 'neo_list', methods: ['GET'])]
    public function getList(
        Request $request,
        FindNeoQueryFactory $queryFactory,
        ValidatorInterface $validator,
        EncoderInterface $encoder,
        NeoRepository $repository,
        NeoListNormalizer $normalizer,
    ): JsonResponse {
        $query = $queryFactory->create($request->query->all());
        $errors = $validator->validate($query);

        if (count($errors) > 0) {
            throw new InvalidArgumentException((string) $errors);
        }

        return new JsonResponse(
            $encoder->encode(
                $normalizer->normalize($repository->findAllByQuery($query), null, ['_route' => $request->attributes->get('_route')]),
                'json',
            ),
            Response::HTTP_OK,
            [],
            true,
        );
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route(path: '/v1/neo/hazardous', name: 'neo_list_hazardous', methods: ['GET'])]
    public function getHazardous(
        Request $request,
        FindNeoQueryFactory $queryFactory,
        ValidatorInterface $validator,
        EncoderInterface $encoder,
        NeoRepository $repository,
        NeoListNormalizer $normalizer,
    ): JsonResponse {
        $query = $queryFactory->create($request->query->all());
        $errors = $validator->validate($query);

        if (count($errors) > 0) {
            throw new InvalidArgumentException((string) $errors);
        }

        return new JsonResponse(
            $encoder->encode(
                $normalizer->normalize($repository->findByHazardous($query), null, ['_route' => $request->attributes->get('_route')]),
                'json',
            ),
            Response::HTTP_OK,
            [],
            true,
        );
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route(path: '/v1/neo/fastest', name: 'neo_fastest', methods: ['GET'])]
    public function getFastest(
        Request $request,
        FindNeoQueryFactory $queryFactory,
        ValidatorInterface $validator,
        EncoderInterface $encoder,
        NeoRepository $repository,
        NeoNormalizer $normalizer,
    ): JsonResponse {
        $query = $queryFactory->create([
            'isHazardous' => $request->query->getBoolean('hazardous'),
        ]);
        $errors = $validator->validate($query);

        if (count($errors) > 0) {
            throw new InvalidArgumentException((string) $errors);
        }

        return new JsonResponse(
            $encoder->encode($normalizer->normalize($repository->findFastest($query)), 'json'),
            Response::HTTP_OK,
            [],
            true,
        );
    }

    #[Route(path: '/v1/neo/best-month', name: 'neo_best_month', methods: ['GET'])]
    public function getBestMonth(
        Request $request,
        FindNeoQueryFactory $queryFactory,
        ValidatorInterface $validator,
        NeoRepository $repository,
        EncoderInterface $encoder,
    ): JsonResponse {
        $query = $queryFactory->create([
            'isHazardous' => $request->query->getBoolean('hazardous'),
        ]);
        $errors = $validator->validate($query);

        if (count($errors) > 0) {
            throw new InvalidArgumentException((string) $errors);
        }

        return new JsonResponse(
            $encoder->encode($repository->findBestMonth($query), 'json'),
            Response::HTTP_OK,
            [],
            true,
        );
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route(path: '/v1/neo/{id}', name: 'neo_item', methods: ['GET'])]
    public function getItem(
        Request $request,
        EncoderInterface $encoder,
        NeoRepository $repository,
        NeoNormalizer $normalizer,
    ): JsonResponse {
        return new JsonResponse(
            $encoder->encode(
                $normalizer->normalize($repository->find($request->attributes->getInt('id'))),
                'json',
            ),
            Response::HTTP_OK,
            [],
            true
        );
    }
}