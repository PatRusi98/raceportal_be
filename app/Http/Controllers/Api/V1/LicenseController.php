<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\LicenseService;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    protected $licenseService;

    public function __construct(LicenseService $licenseService){ $this->licenseService = $licenseService; }

    public function getAll() { return $this->licenseService->get(); }

    public function get($id) { return $this->licenseService->get($id); }

    public function store(Request $request) { return $this->licenseService->store($request); }

    public function update(Request $request, $id) { return $this->licenseService->store($request, $id); }

    public function delete($id) { return $this->licenseService->delete($id); }
}
