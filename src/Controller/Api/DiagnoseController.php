<?php
declare(strict_types=1);


namespace App\Controller\Api;

use App\Controller\BaseApiController;
use App\Exception\MissingParameterException;
use App\Repository\UZIS\DiagnoseRepository;
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
				"page" => true,
				"search" => true,
			]);
		} catch (MissingParameterException) {
			return $this->error("Missing parameters");
		}

		$page = (int)$params["page"];
		$search = $params["search"];

		$diagnoses = $repository->search($search, $page);
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

}