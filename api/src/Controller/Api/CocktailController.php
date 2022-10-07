<?php

namespace App\Controller\Api;

use OpenApi\Annotations as OA;
use App\Entity\Cocktail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @OA\Tag(
 *     name="Cocktail",
 *     description="Cocktail related operations"
 * )
 */
class CocktailController extends AbstractController
{

    /**
     * @param EntityManagerInterface $entityManager the entity manager to use
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $this->cocktailRepository = $entityManager->getRepository(Cocktail::class);
    }

    /**
     * @Route("/api/cocktails/{id}", name="get_one_cocktail", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $cocktail = $this->cocktailRepository->findOneBy(["id" => $id]);

        if (!$cocktail) return new JsonResponse([], Response::HTTP_NOT_FOUND);

        return new JsonResponse($cocktail->toArray(), Response::HTTP_OK);
    }

    /**
     * @Route("/api/cocktails", name="get_all_cocktails", methods={"GET"})
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
        $cocktails =
            isset($_GET["name"]) ?
                $this->cocktailRepository->getCocktailsByName($_GET["name"]) :
                $this->cocktailRepository->findAll();

        $data = [];

        foreach ($cocktails as $cocktail){
            $data[] = $cocktail->toArray();
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/cocktails/ingredients/{id}", name="get_all_cocktails_by_ingredient", methods={"GET"})
     */
    public function getByIngredient($id): JsonResponse
    {
        $cocktails = $this->cocktailRepository->getCocktailsByIngredientId($id);

        $data = [];

        foreach ($cocktails as $cocktail){
            $data[] = $cocktail->toArray();
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/cocktails", name="add_cocktail", methods={"POST"})
     *
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="name", type="string", example="Mojito"),
     *        @OA\Property(
     *             property="ingredients",
     *             type="integer",
     *             example="[1,2]"
     *        )
     *     )
     * )
     *
     * @OA\Response(
     *         response=200,
     *         description="Cocktail created!",
     * )
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (count($data) != 2) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $name = $data["name"];
        $ingredients = $data["ingredients"];

        if (empty($name) || empty($ingredients)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->cocktailRepository->saveCocktail($name, $ingredients);

        return new JsonResponse(["status" => "Cocktail created!"], Response::HTTP_CREATED);
    }
}