<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\TrackService;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    protected $service;

    public function __construct(TrackService $service){ $this->service = $service; }

    public function getAll() { return $this->service->get(); }

    public function get($id) { return $this->service->get($id); }

    public function store(Request $request) { return $this->service->store($request); }

    public function update(Request $request, $id) { return $this->service->store($request, $id); }

    public function delete($id) { return $this->service->delete($id); }
}
