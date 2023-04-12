<?php

namespace App\Services;

use Illuminate\Support\Facades\Response;

class ImageProviderService
{
    public function get($filename) {
        $path = public_path('public\\images\\').$filename;
        return Response::download($path);
    }
}
