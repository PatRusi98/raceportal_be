<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\CarService;
use Illuminate\Http\Request;

class CarController extends Controller
{
    protected $carService;

    public function __construct(CarService $carService){ $this->carService = $carService; }

    public function getAll() { return $this->carService->getAll(); }

    public function get($id) { return $this->carService->get($id); }

    public function store(Request $request) { return $this->carService->store($request); }

    public function update(Request $request, $id) { return $this->carService->store($request, $id); }

    public function delete($id) { return $this->carService->delete($id); }
}
