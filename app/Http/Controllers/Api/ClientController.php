<?php

namespace App\Http\Controllers\Api;

use App\Dto\GetClientListDto;
use App\Http\Controllers\BaseApiController;
use App\Http\Request\ClientListRequest;
use App\Interfaces\ClientListServiceInterface;
use App\Resources\ClientListResource;
use Illuminate\Http\JsonResponse;

class ClientController extends BaseApiController
{
    public function __construct(private readonly ClientListServiceInterface $service)
    {

    }

    public function getClientList(ClientListRequest $request): JsonResponse
    {
        $response = $this->service->getClientList(GetClientListDto::fromArray($request->all()));

        return $this->response('successfully_got_client_list', ClientListResource::collection($response));
    }

}
