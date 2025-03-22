<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class CodeExecutionController extends Controller
{
    //
    public function executePython(Request $request)
    {
        // Validasi input
        // Validasi input
        $request->validate([
            'code' => 'required|string',
        ]);

        // Ambil kode dari request
        $userCode = $request->input('code');

        // Jalankan kode dalam container Docker
        $process = new Process([
            'C:\Program Files\Docker\Docker\resources\bin\docker.exe', 
            'run', '--rm', '-i', 'python-sandbox', '/app/entrypoint.sh', $userCode
        ]);

        // Kirim kode Python ke Docker melalui stdin
        $process->setInput($userCode);
        $process->run();

        // Periksa apakah eksekusi berhasil
        if (!$process->isSuccessful()) {
            return response()->json(['error' => $process->getErrorOutput()], 500);
        }

        // Kembalikan output ke frontend
        return response()->json(['output' => $process->getOutput()]);
    }}
