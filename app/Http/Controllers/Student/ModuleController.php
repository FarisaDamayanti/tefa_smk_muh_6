<?php

namespace App\Http\Controllers\Student;

use App\Contracts\Repositories\ModuleRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;

class ModuleController extends Controller
{

    public function __construct(
        protected ModuleRepository $moduleRepository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modules = $this->moduleRepository->whereStudent(auth_student()->id);
        return view('', compact('modules'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $module = $this->moduleRepository->show($id);

            $module->loadMissing([
                'topic.subject',
                'topic.classroom.levelClass',
            ]);

            return view('multi-role.pages.modules.detail-material', compact('module'));
        } catch (Throwable $th) {
            return redirect()->back()->with('error', 'Terjadi kesalahan. ' . $th->getMessage());
        }
    }
}
