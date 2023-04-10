<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ScoringService;
use Illuminate\Http\Request;

class ScoringController extends Controller
{
    protected $scoringService;

    public function __construct(ScoringService $scoringService) { $this->scoringService = $scoringService; }

    public function getAll() { return $this->scoringService->get(); }

    public function get($id) { return $this->scoringService->get($id); }

    public function store(Request $request) { return $this->scoringService->store($request); }

    public function update(Request $request, $id) { return $this->scoringService->store($request, $id); }

    public function delete($id) { return $this->scoringService->delete($id); }
}
