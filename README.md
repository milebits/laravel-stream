Laravel Stream
==
A laravel package that allows you to easily integrate Video streaming ability in your laravel application.
# Installation
You can install this package using this composer command:
```
composer require milebits/laravel-stream
```
for development and contribution purposes please use
```
composer reuquire milebits/laravel-stream --dev
```
# How to use
To use this package, you can call it using two ways, the first is by using the Milebits Laravel Stream helper file.
Todo so, add these lines to your code!
```
<?php

namespace App\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;

use function Milebits\LaravelStream\Helpers\videoStream;

class VideoController extends Controller
{
    public function showVideo_1(Request $request, string $path)
    {
        return videoStream($path, $request);
    }

    public function showVideo_2(string $path)
    {
        return videoStream($path);
    }

    public function showVideo_3(string $path)
    {
        return Response::videoStream($path);
    }

    public function showVideo_4(Request $request, string $path)
    {
        return Response::videoStream($path, $request);
    }
}
```
# Contributions
If in any case while using this package, and you which to request a new functionality to it, please contact us at suggestions@os.milebits.com and mention the package you are willing to contribute or suggest a new functionality.

# Vulnerabilities
If in any case while using this package, you encounter security issues or security vulnerabilities, please do report them as soon as possible by issuing an issue here in Github or by sending an email to security@os.milebits.com with the mention **Vulnerability Report milebits/laravel-stream** as your subject.