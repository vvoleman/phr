<?php
declare(strict_types=1);


namespace App\Controller;

use App\Exception\MissingParameterException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseApiController extends AbstractController
{

	private Request $request;

	public function __construct(RequestStack $requestStack) {
		$this->request = $requestStack->getCurrentRequest();
	}

	/**
	 * @param array<string,bool> $params
	 * @return array<string,mixed>
	 * @throws MissingParameterException
	 */
	protected function getParams(array $params): array{
		$result = [];
		foreach ($params as $param => $mandatory) {
			$data = $this->request->get($param);
			if($data !== null){
				$result[$param] = $data;
			}else if($mandatory){
				throw new MissingParameterException(sprintf("Unable to find parameter '%s'",$param));
			}
		}

		return $result;
	}

	protected function error(string $message, int $code = Response::HTTP_BAD_REQUEST, array $context = []): JsonResponse
	{
		return new JsonResponse([
			"status"=>"error",
			"message"=>$message,
			"context"=>$context
		], $code);
	}

	protected function send(array $data, int $code = Response::HTTP_OK): JsonResponse
	{
		return new JsonResponse([
			"status"=>"success",
			"data"=>$data
		]);
	}

	#[Route('/', name:'app')]
	public function root(): Response
	{
		return $this->error('No endpoint found', Response::HTTP_NOT_FOUND);
	}

}