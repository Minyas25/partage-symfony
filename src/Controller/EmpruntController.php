<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Repository\EmpruntRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/emprunt')]

class EmpruntController extends AbstractController
{
    public function __construct(private EmpruntRepository $repo, private EntityManagerInterface $em) {}

    #[Route(methods:'GET')]
    public function all(Request $request): JsonResponse
    {
        $page = $request->query->get('page', 1);
        $pageSize = $request->query->get('pageSize', 5);

        return $this->json($this->repo->findBy([], limit: $pageSize, offset: ($page-1)*$pageSize));
    }

    #[Route('/{id}', methods: 'GET')]
    public function one(Emprunt $emprunt) {
        return $this->json($emprunt);
    }

    #[Route(methods: 'POST')]
    public function add(Request $request, SerializerInterface $serializer): JsonResponse {
        try {
            $emprunt = $serializer->deserialize($request->getContent(), Emprunt::class, 'json');
        } catch (\Exception $e) {
            return $this->json('Invalid Body', 400);
        }

        $this->em->persist($emprunt);
        $this->em->flush();
        return $this->json($emprunt, 201);

    }

    #[Route('/{id}', methods: 'DELETE')]
    public function delete(Emprunt $emprunt): JsonResponse {
        $this->em->remove($emprunt);
        $this->em->flush();
        return $this->json(null, 204);
    }
    
    #[Route('/{id}', methods: 'PATCH')]
    public function update(Emprunt $emprunt, Request $request, SerializerInterface $serializer): JsonResponse {
        try {
            $serializer->deserialize($request->getContent(), Emprunt::class, 'json', [
                'object_to_populate' => $emprunt
            ]);
        } catch (\Exception $th) {
            return $this->json('Invalid body', 400);
        }

        $this->em->flush();

        return $this->json($emprunt);
    }
}
