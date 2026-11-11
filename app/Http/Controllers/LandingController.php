<?php

namespace App\Http\Controllers;

use App\Services\LandingService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

class LandingController extends Controller
{
    private LandingService $service;

    public function __construct(LandingService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {
            $faqs = $this->service->faqs();
            return view('welcome', compact('faqs'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan pada server');
        }
    }
}
