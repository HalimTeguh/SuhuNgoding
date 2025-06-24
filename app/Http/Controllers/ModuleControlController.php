<?php

namespace App\Http\Controllers;

use App\Models\ControlModuleContent;
use App\Models\Module;
use App\Models\ModuleContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuleControlController extends Controller
{
    //

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'material_link' => 'required|string',
                'test_link' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            $controlContent = ControlModuleContent::where('module_content_id', $request->id)->first();

            // Jika belum ada data, buat baru
            if (!$controlContent) {
                $controlContent = new ControlModuleContent();
                $controlContent->module_content_id = $request->id;
            }

            $controlContent->material_link = $request->material_link;
            $controlContent->test_link = $request->test_link;
            $controlContent->notes = $request->notes;
            $controlContent->save();

            DB::commit();

            $content = ModuleContent::findOrFail($request->id);

            return redirect()->back()->with('toasts', [[
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => "Materi {$content->title} berhasil diperbarui",
                'time' => now()->diffForHumans()
            ]]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['general' => $e->getMessage()])
                ->withInput()
                ->with('toasts', [[
                    'type' => 'danger',
                    'title' => 'Gagal',
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                    'time' => now()->diffForHumans()
                ]])
                ->with('form_error', 'update');
        }
    }
}
