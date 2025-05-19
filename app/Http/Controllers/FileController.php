<?php

namespace App\Http\Controllers;

use App\Jobs\ConvertPdfJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class FileController extends Controller
{
    //
    public function convert(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:pdf|max:10240'
            ]);

            $uuid = (string) Str::uuid();
            $originalName = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME);

            $inputFile = $request->file('file');
            $inputFile->storeAs('convert2md/input', "$uuid.pdf");

            // Set status dan log awal
            Cache::put("convert:$uuid:status", 'processing', now()->addMinutes(30));
            Cache::put("convert:$uuid:log", ["Mulai proses konversi untuk $originalName..."], now()->addMinutes(30));

            dispatch(new ConvertPdfJob($uuid, $originalName));

            return response()->json(['uuid' => $uuid]);
        } catch (\Throwable $e) {
            Log::error("Convert Error: " . $e->getMessage());

            return response()->json([
                'status' => 'failed',
                'message' => 'Terjadi kesalahan saat memproses file.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function status($uuid)
    {
        return response()->json([
            'status' => Cache::get("convert:$uuid:status", 'not_found'),
            'error' => Cache::get("convert:$uuid:error", null),
            'log' => Cache::get("convert:$uuid:log", []),
        ]);
    }

    public function result($uuid)
    {
        $privateDir = storage_path("app/convert2md/output/$uuid");
        $publicDir = storage_path("app/public/convert2md/output/$uuid");
        $mdPath = "$publicDir/$uuid.md";

        // Jika belum tersedia di public, salin dari private ke public
        if (!file_exists($mdPath)) {
            if (file_exists($privateDir)) {
                File::ensureDirectoryExists($publicDir);
                File::copyDirectory($privateDir, $publicDir);
            } else {
                return response()->json(['error' => 'Belum tersedia'], 404);
            }
        }

        // Baca markdown dan ambil gambar
        $markdown = file_get_contents($mdPath);
        $images = array_values(array_filter(scandir($publicDir), fn($f) => preg_match('/\.(jpeg|jpg|png)$/i', $f)));

        return response()->json([
            'uuid' => $uuid,
            'markdown' => $markdown,
            'images' => array_map(fn($img) => asset("storage/convert2md/output/$uuid/$img"), $images)
        ]);
    }

    public function log($uuid)
    {
        $log = Cache::get("convert:$uuid:log", 'Belum ada log.');
        return response()->json(['log' => $log]);
    }


    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs('public/uploads/temp', $filename);

        return response()->json([
            'location' => asset('storage/uploads/temp/' . $filename)
        ]);
    }

    public function downloadTemplateStudentExcel(){
        $file = storage_path('app/public/templates/Template_import_data_siswa.xlsx');
    
        if (!file_exists($file)) {
            abort(404, 'Template not found');
        }
    
        return response()->download($file, 'template_students.xlsx');
    }
}
