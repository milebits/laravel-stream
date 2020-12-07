<?php


namespace Milebits\LaravelStream\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use function request;
use function response;

class StreamServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Response::macro('videoStream',
            /**
             * @param string $path
             * @param Request|null $request
             * @return Application|ResponseFactory|Response|StreamedResponse
             */
            function (string $path, Request $request = null) {
                $request = $request ?? request();

                if (!File::exists($path))
                    return response('File not found', 404);

                $type = mime_content_type($path);
                $size = File::size($path);

                $start = 0;
                $end = $size;
                $length = $size;
                $status = 200;

                $headers = [
                    'Content-Type' => $type,
                    'Content-Length' => $length,
                    'Accept-Ranges' => "bytes $start-$end",
                    'Content-Size' => $size,
                    'Cache-Control' => 'max-age=2592000, public',
                    'Expires' => now()->addMonth()->format('D, d M Y H:i:s') . ' GMT',
                    'Last-Modified' => gmdate('D, d M Y H:i:s', @filemtime($path)) . ' GMT',
                    'Content-Range' => "bytes $start-$end/$size",
                ];

                $range = $request->server('Range', false);     // False or bytes=$startByte-$endByte

                if (!is_bool($range)) {
                    $range = Str::of($range)->trim()->replace('bytes=', '');

                    list($rangeStart, $rangeEnd) = Str::of($range)->explode('-');

                    $rangeStart = intval(str_replace('-', '', $rangeStart));
                    $rangeEnd = intval(str_replace('-', '', $rangeEnd));

                    $start = ($rangeStart === '') ? $start : intval($rangeStart);
                    $end = ($rangeEnd === '') ? $end : intval($rangeEnd);

                    $length = $end - $start + 1;

                    $headers['Content-Range'] = "bytes $start-$end/$size";
                    $headers['Content-Length'] = $length;
                    $status = 206;
                }

                return response()->stream(
                    function () use ($path, $start, $length) {
                        $file = fopen($path, 'rb');
                        fseek($file, $start);
                        while (!feof($file) && !connection_aborted()) {
                            echo fread($file, $length);
                        }
                        fclose($file);
                    }
                    , $status, $headers);
            });
    }

    public function register()
    {

    }
}