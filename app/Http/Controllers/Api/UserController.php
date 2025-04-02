<?php

namespace App\Http\Controllers\Api;

use App\Dto\GetUserListDto;
use App\Http\Controllers\BaseApiController;
use App\Http\Request\UserListRequest;
use App\Interfaces\UserListServiceInterface;
use App\Resources\UserListResource;
use Illuminate\Http\JsonResponse;

class UserController extends BaseApiController
{
    public function __construct(private readonly UserListServiceInterface $service) {}

    public function getUserList(UserListRequest $request): JsonResponse
    {
        $response = $this->service->getUserList(GetUserListDto::fromArray($request->all()));

        return $this->response('successfully_got_user_list', UserListResource::collection($response));
    }
}
