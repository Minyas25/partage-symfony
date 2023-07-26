<?php

namespace App\Controller;

use App\Entity\Person;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/person')]
class PersonController extends AbstractController
{
    public function __construct(private PersonRepository $repo, private EntityManagerInterface $em) {}

    #[Route(methods:'GET')]
    public function all(Request $request): JsonResponse
    {
        $page = $request->query->get('page', 1);
        $pageSize = $request->query->get('pageSize', 5);

        return $this->json($this->repo->findBy([], limit: $pageSize, offset: ($page-1)*$pageSize));
    }

    #[Route('/{id}', methods: 'GET')]
    public function one(Person $person) {
        return $this->json($person);
    }

    #[Route(methods: 'POST')]
    public function add(Request $request, SerializerInterface $serializer): JsonResponse {
        try {
            $person = $serializer->deserialize($request->getContent(), Person::class, 'json');
        } catch (\Exception $e) {
            return $this->json('Invalid Body', 400);
        }

        $this->em->persist($person);
        $this->em->flush();
        return $this->json($person, 201);

    }

    #[Route('/{id}', methods: 'DELETE')]
    public function delete(Person $person): JsonResponse {
        $this->em->remove($person);
        $this->em->flush();
        return $this->json(null, 204);
    }
    
    #[Route('/{id}', methods: 'PATCH')]
    public function update(Person $person, Request $request, SerializerInterface $serializer): JsonResponse {
        try {
            $serializer->deserialize($request->getContent(), Person::class, 'json', [
                'object_to_populate' => $person
            ]);
        } catch (\Exception $th) {
            return $this->json('Invalid body', 400);
        }

        $this->em->flush();

        return $this->json($person);
    }
}
