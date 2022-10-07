<?php

namespace App\Controller\Api;

use OpenApi\Annotations as OA;
use App\Entity\Ingredient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @OA\Tag(
 *     name="Ingredient",
 *     description="Ingredient related operations"
 * )
 */
class IngredientController extends AbstractController
{

    /**
     * @param EntityManagerInterface $entityManager the entity manager to use
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $this->ingredientRepository = $entityManager->getRepository(Ingredient::class);
    }

    /**
     * @Route("/api/ingredients/{id}", name="get_one_ingredient", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $ingredient = $this->ingredientRepository->findOneBy(["id" => $id]);

        if (!$ingredient) return new JsonResponse([], Response::HTTP_NOT_FOUND);

        return new JsonResponse($ingredient->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/api/ingredients", name="get_all_ingredient", methods={"GET"})
     *
     * @OA\Parameter(
     *     name="name",
     *     in="query",
     *     description="The field used to filter by name",
     *     @OA\Schema(type="string")
     * )
     */
    public function getAll(): JsonResponse
    {
        $ingredients =
            isset($_GET["name"]) ?
                $this->ingredientRepository->getIngredientsByName($_GET["name"]) :
                $this->ingredientRepository->findAll();

        $data = [];

        foreach ($ingredients as $ingredient){
            $data[] = $ingredient->toArray();
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/ingredients", name="add_ingredient", methods={"POST"})
     *
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="name", type="string", example="Menthe")
     *     )
     * )
     *
     * @OA\Response(
     *         response=200,
     *         description="Ingredient created!",
     * )
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (count($data) < 1) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $name = $data["name"];

        if (empty($name)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->ingredientRepository->saveIngredient($name);

        return new JsonResponse(["status" => "Ingredient created!"], Response::HTTP_CREATED);
    }
}