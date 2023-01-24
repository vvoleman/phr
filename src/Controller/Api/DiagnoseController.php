<?php
declare(strict_types=1);


namespace App\Controller\Api;

use App\Controller\BaseApiController;
use App\Exception\MissingParameterException;
use App\Repository\UZIS\DiagnoseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/diagnose', name: 'api_diagnose')]
class DiagnoseController extends BaseApiController
{

	#[Route('/', name: '_search')]
	public function index(DiagnoseRepository $repository): JsonResponse
	{
		try {
			$params = $this->getParams([
				"page" => false,
				"search" => true,
				"sortBy" => false,
				"direction" => false
			]);
		} catch (MissingParameterException) {
			return $this->error("Missing parameters");
		}

		$direction = match ($params["direction"] ?? null) {
			"desc" => "desc",
			default => "asc"
		};

		$sortBy = match ($params['sortBy'] ?? null) {
			"id" => "id",
			default => "name"
		};

		$page = (int)($params["page"] ?? 0);
		$search = $params["search"];

		$diagnoses = $repository->search($search, $page, $sortBy, $direction);
		$mapped = array_map(function ($diagnose) {
			return [
				"id" => $diagnose->getId(),
				"name" => $diagnose->getName(),
				"parent" => [
					"id" => $diagnose->getParent()->getId(),
					"name" => $diagnose->getParent()->getName(),
				]
			];
		}, $diagnoses);

		return $this->send($mapped);
	}

    #[Route('/random', name:'_random')]
    public function random(EntityManagerInterface $manager): JsonResponse
    {
        $limit = 1000;

        $sql = "SELECT id, name FROM diagnose ORDER BY RAND() LIMIT $limit";
        $results = $manager->getConnection()->fetchAllAssociative($sql);

        $data = [];
        foreach ($results as $result) {
            $data[$result["id"]] = $result["name"];
        }

        return new JsonResponse($data);
    }

}