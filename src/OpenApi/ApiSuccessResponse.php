<?php declare(strict_types=1);

namespace App\OpenApi;

use OpenApi\Attributes\Response;
use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class ApiSuccessResponse extends Response
{

	public function __construct(string $description, OA\Items $items, int $response = 200)
	{
		parent::__construct(
			response: 200,
			description: $description,
			content: new OA\JsonContent(
				properties: [
					new OA\Property(property: 'status', type: 'string', enum: ['success']),
					new OA\Property(property: 'data', type: 'array', items: $items),
				],
				type: 'object'
			)
		);
	}
}
