<?php

namespace App\Http\Controllers\Api;

use App\Dto\ComponentAddDto;
use App\Dto\GetComponentListDto;
use App\Http\Controllers\BaseApiController;
use App\Http\Request\ComponentListRequest;
use App\Http\Request\ComponentRequest;
use App\Interfaces\ComponentListServiceInterface;
use App\Resources\ComponentListResource;
use Illuminate\Http\JsonResponse;

class ComponentController extends BaseApiController
{
    public function __construct(private readonly ComponentListServiceInterface $service)
    {

    }

    public function getComponentList(ComponentListRequest $request): JsonResponse
    {
        $response = $this->service->getComponentList(GetComponentListDto::fromArray($request->all()));

        return $this->response('successfully_got_component_list', ComponentListResource::collection($response));
    }

    public function addComponent (ComponentRequest $request): JsonResponse
    {
        $component = $this->service->createComponent(ComponentAddDto::fromArray($request->validated()));

        return $this->response('component_created_successfully', new ComponentListResource($component), status: 201);
    }
}
