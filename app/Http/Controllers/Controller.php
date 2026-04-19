<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "UTP TIS - Ecommerce API",
    version: "1.0.0",
    description: "Backend API sederhana ecommerce menggunakan Laravel dengan mock data JSON"
)]
abstract class Controller
{
}