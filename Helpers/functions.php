<?php


namespace Milebits\LaravelStream\Helpers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

if (!function_exists("videoStream")) {
    /**
     * @param string $path
     * @param Request|null $request
     * @return Application|ResponseFactory|Response|StreamedResponse
     */
    function videoStream(string $path, Request $request = null)
    {
        return response()->videoSteam($path, $request);
    }
}