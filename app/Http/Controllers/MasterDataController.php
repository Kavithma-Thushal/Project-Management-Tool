<?php

namespace App\Http\Controllers;

use App\Classes\ErrorResponse;
use App\Http\Resources\PriorityTypesResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\TaskTypesResource;
use App\Http\Services\MasterDataService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MasterDataController extends Controller
{

    private MasterDataService $masterDataService;

    public function __construct(MasterDataService $masterDataService)
    {
        $this->masterDataService = $masterDataService;
    }

    public function getTaskTypes()
    {
        try {
            $data = $this->masterDataService->getTaskTypes();
            return new SuccessResource(['data' => TasktypesResource::collection($data)]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }

    public function getPriorityTypes()
    {
        try {
            $data = $this->masterDataService->getPriorityTypes();
            return new SuccessResource(['data' => SuccessResource::collection($data)]);
        } catch (HttpException $e) {
            ErrorResponse::throwException($e);
        }
    }
}
