<?php

namespace App\Http\Controllers;

use App\Models\ModuleContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuleContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

        try {
            DB::beginTransaction(); // Mulai transaksi

            $request->validate([
                'chapterName' => 'required|string|max:255',
                'moduleSummary' => 'nullable|string',
                'moduleContent' => 'nullable|string', // Hilangkan max:255 jika isinya panjang
            ]);
    
            // Cari data berdasarkan ID yang dikirimkan
            $content = ModuleContent::findOrFail($id);
    
            // Update data
            $content->update([
                'title' => $request->chapterName,
                'summary' => $request->moduleSummary,
                'content' => $request->moduleContent,
            ]);
    
            DB::commit();

            return redirect()->back()->with('toasts', [
                [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'message' => "Materi {$content->title} berhasil diperbarui",
                    'time' => now()->diffForHumans()
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['general' => $e->getMessage()])
                ->withInput()
                ->with('toasts', [
                    [
                        'type' => 'danger',
                        'title' => 'Gagal',
                        'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                        'time' => now()->diffForHumans(),
                    ]
                ])->with('form_error', 'update');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
