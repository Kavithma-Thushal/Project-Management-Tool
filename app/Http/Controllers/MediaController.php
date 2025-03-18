<?php

namespace App\Http\Controllers;

use App\Classes\ErrorResponse;
use App\Http\Requests\MediaAddRequest;
use App\Http\Requests\MultipleMediaAddRequest;
use App\Http\Resources\MediaResource;
use App\Http\Resources\MultipleMediaResource;
use App\Http\Resources\SuccessResource;
use App\Http\Services\MediaService;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MediaController extends Controller
{
    public function __construct(private MediaService $mediaService)
    {
    }

    public function upload(MediaAddRequest $request)
    {
        try {
            $data = $this->mediaService->upload($request);
            return new SuccessResource([
                'message' => 'File uploaded',
                'data' => new MediaResource($data)
            ]);
        } catch (HttpException $e) {
            ErrorResponse::rollback($e);
        }
    }

    public function uploadMultiple(MultipleMediaAddRequest $request)
    {
        try {
            $data = $this->mediaService->uploadMultiple($request);
            return new SuccessResource([
                'message' => 'Files uploaded',
                'data' => MultipleMediaResource::collection($data)
            ]);
        } catch (HttpException $e) {
            ErrorResponse::rollback($e);
        }
    }
}
