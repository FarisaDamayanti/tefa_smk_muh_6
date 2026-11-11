<?php

namespace App\Http\Controllers\Schools;

use App\Http\Controllers\Controller;
use App\Http\Requests\School\GraduationDetailRequest;
use App\Services\School\GraduationDetailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GraduationDetailController extends Controller
{
    public function __construct
    (
        private GraduationDetailService $service,
    ){}

    /**
     * store or update graduation detail
     * 
     * @param GraduationDetailRequest $request
     * @return RedirectResponse
     */
    public function storeOrUpdate(GraduationDetailRequest $request): RedirectResponse
    {
        try {
            $this->service->storeOrUpdate($request);

            return redirect()->back()->with('success', 'Berhasil menyimpan data status kelulusan siswa');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal menyimpan data status kelulusan siswa. ' . $th->getMessage());
        }
    }
}
