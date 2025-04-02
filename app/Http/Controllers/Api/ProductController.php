<?php

namespace App\Http\Controllers\Api;

use App\Dto\GetProductListDto;
use App\Http\Controllers\BaseApiController;
use App\Http\Request\ProductListRequest;
use App\Interfaces\ProductListServiceInterface;
use App\Resources\ProductListResource;
use Illuminate\Http\JsonResponse;

final class ProductController extends BaseApiController
{
    public function __construct(private readonly ProductListServiceInterface $service){}

    public function getProductList(ProductListRequest $request): JsonResponse
    {
        $response = $this->service->getProductList(GetProductListDto::fromArray($request->all()));

        return $this->response('Here is your products list', ProductListResource::collection($response));
    }
}
