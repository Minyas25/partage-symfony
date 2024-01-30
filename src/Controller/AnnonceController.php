<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Person;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class AnnonceController extends AbstractController
{
    public function __construct(private AnnonceRepository $repo, private EntityManagerInterface $em) {}

    #[Route('/annonce',methods:'GET')]
    public function all(Request $request): JsonResponse
    {
        $page = $request->query->get('page', 1);
        $pageSize = $request->query->get('pageSize', 5);

        return $this->json($this->repo->findBy([], limit: $pageSize, offset: ($page-1)*$pageSize));
    }

    #[Route('/annonce/{id}', methods: 'GET')]
    public function one(Annonce $annonce) {
        return $this->json($annonce);
    }

    #[Route('/annonce-create', methods: 'POST')]
    // public function add(Person $person, Request $request, SerializerInterface $serializer): JsonResponse {
    //     try {
    //         $annonce = $serializer->deserialize($request->getContent(), Annonce::class, 'json');
    //     } catch (\Exception $e) {
    //         return $this->json('Invalid Body', 400);
    //     }
    //     $annonce->setIdPerson($person);
    //     $this->em->persist($annonce);
    //     $this->em->flush();
    //     return $this->json($annonce, 201);

    // }
    public function add(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        try {
            $annonce = $serializer->deserialize($request->getContent(), Annonce::class, 'json');
        } catch (\Exception $error) {
            return $this->json('Invalid body', 400);
        }
        $errors = $validator->validate($annonce);
        if ($errors->count() > 0) {
            return $this->json(['errors' => $errors], 400);
        }
        $this->repo->persist($annonce);

        return $this->json($annonce, 201);
    }

    #[Route('annonce/{id}', methods: 'DELETE')]
    public function delete(Annonce $annonce): JsonResponse {
        $this->em->remove($annonce);
        $this->em->flush();
        return $this->json(null, 204);
    }
    
    #[Route('/{id}', methods: 'PATCH')]
    public function update(Annonce $annonce, Request $request, SerializerInterface $serializer): JsonResponse {
        try {
            $serializer->deserialize($request->getContent(), Annonce::class, 'json', [
                'object_to_populate' => $annonce
            ]);
        } catch (\Exception $th) {
            return $this->json('Invalid body', 400);
        }

        $this->em->flush();

        return $this->json($annonce);
    }
}
