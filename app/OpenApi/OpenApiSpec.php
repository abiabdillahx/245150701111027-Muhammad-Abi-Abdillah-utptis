<?php

namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="UTP TIS - Ecommerce API",
 *         version="1.0.0",
 *         description="Backend API sederhana ecommerce menggunakan Laravel dengan mock data JSON"
 *     ),
 *     @OA\Server(
 *         url=L5_SWAGGER_CONST_HOST,
 *         description="Local server"
 *     )
 * )
 */
class OpenApiSpec
{
}
