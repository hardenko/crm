<?php

namespace App\Http\Controllers\Api;

use App\Dto\GetComponentListDto;
use App\Http\Controllers\BaseApiController;
use App\Http\Request\ComponentListRequest;
use App\Interfaces\ComponentListServiceInterface;
use App\Models\Component;
use App\Resources\ComponentListResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ComponentController extends BaseApiController
{
    public function __construct(private readonly ComponentListServiceInterface $service)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function getComponentList(ComponentListRequest $request): JsonResponse
    {
        $response = $this->service->getComponentList(GetComponentListDto::fromArray($request->all()));

        return $this->response('successfully_got_component_list', ComponentListResource::collection($response));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $component = Component::find($id);
        if (!$component) {
            return response()->json(['message' => 'Component not found'], 404);
        }
        return response()->json($component);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
