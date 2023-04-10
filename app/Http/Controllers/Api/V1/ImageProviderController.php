<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ImageProviderService;
use Illuminate\Http\Request;

class ImageProviderController extends Controller
{
    protected $service;

    public function __construct(ImageProviderService $service){ $this->service = $service; }

    public function getAvatar($filename) { return $this->service->get($filename); }
}
