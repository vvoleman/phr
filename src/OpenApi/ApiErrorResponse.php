<?php declare(strict_types=1);

namespace App\OpenApi;

use OpenApi\Attributes\Response;
use OpenApi\Attributes as OA;


#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class ApiErrorResponse extends Response
{
	public function __construct(string $description, string $message, int $response = 400)
	{
		parent::__construct(
			response: $response,
			description: $description,
			content: new OA\JsonContent(
				properties: [
					new OA\Property(property: 'status', type: 'string', enum: ['error']),
					new OA\Property(property: 'message', type: 'string', example: $message),
				],
				type: 'object'
			)
		);
	}
}
