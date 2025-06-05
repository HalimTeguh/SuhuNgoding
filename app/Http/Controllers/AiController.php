<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    //
    public function generateSoalFromLLM(Request $request)
    {
        $request->validate([
            'materi' => 'required|string'
        ]);

        $materi = $request->input('materi');

        // Prompt dengan Heredoc
        $prompt = <<<PROMPT
Kamu adalah seorang guru profesional yang berfokus pada pengajaran materi pemrograman untuk tingkat Sekolah Menengah Atas (SMA). Tugasmu adalah membuat soal latihan berbasis JSON sesuai materi yang diberikan.

**Buat 10 soal** yang mencakup kelima level Taksonomi Bloom berikut:
1. **Mengingat (Remember)** – 2 soal
2. **Memahami (Understand)** – 2 soal
3. **Menerapkan (Apply)** – 2 soal
4. **Menganalisis (Analyze)** – 2 soal
5. **Mengevaluasi (Evaluate)** – 2 soal

###  Format Soal:
- **Remember, Understand, Analyze, Evaluate** ➔ **pilihan ganda (multiple choice)** dengan **4 opsi jawaban** (termasuk satu jawaban benar dan tiga jawaban salah).  
  Setiap opsi memiliki:
  - `answer`: teks jawaban
  - `feedback`: penjelasan benar/salah
  - `is_correct`: true/false
  
- **Apply** ➔ soal studi kasus kode dengan:
  - `reference_code`: potongan kode yang relevan
  - `output`: arahan langkah-langkah yang jelas (misalnya: buat variabel, gunakan print(), dsb). **Tidak perlu field feedback**.
  - **Catatan:** gunakan data dummy dari variabel, jangan gunakan fungsi input().

- **Evaluate** ➔ pertanyaan pilihan ganda untuk menilai kode program:
  - WAJIB sertakan `reference_code` jika menilai kode.
  - Sertakan **4 opsi jawaban** dengan satu jawaban benar.

###  Assessment Terms per Level (wajib digunakan untuk membuat soal):
- **Remember**:
  - Mengidentifikasi, Mengenali implementasi, Mengingat konsep materi dan bagian kode yang dipelajari
- **Understand**:
  - Memahami, Menerjemahkan, dan Menjelaskan konsep algoritma tertentu
- **Apply**:
  - Mengimplementasikan konsep yang dipelajari dan Menyelesaikan studi kasus sederhana
- **Analyze**:
  - Memecah tugas program menjadi beberapa komponen
  - Mengidentifikasi komponen penting dan yang tidak penting
- **Evaluate**:
  - Menentukan apakah sebuah kode dapat menyelesaikan studi kasus tertentu
  - Menilai kualitas dan standar kode dengan benar

###  Format Output JSON:
Berikan jawaban **langsung dalam format JSON array** (bahasa Indonesia), **tanpa narasi pembuka**, dan **langsung mulai dengan tanda kurung siku** (`[`).

Contoh:
[
  {
    "level": "Memahami",
    "question": "Apa yang dimaksud dengan ekspresi dalam Python?",
    "choices": [
      {
        "answer": "Gabungan dari operator dan operand yang menghasilkan nilai",
        "feedback": "Benar, karena ekspresi melibatkan operator dan operand.",
        "is_correct": true
      },
      {
        "answer": "Variabel yang menyimpan nilai",
        "feedback": "Salah, karena ekspresi tidak hanya menyimpan nilai.",
        "is_correct": false
      },
    ]
  },
  {
    "level": "Menerapkan",
    "question": "Tulislah program Python untuk menentukan apakah sebuah angka adalah ganjil atau genap.",
    "reference_code": "def cek_ganjil_genap(angka):\n    if angka % 2 == 0:\n        return \"Genap\"\n    else:\n        return \"Ganjil\"\n\nprint(cek_ganjil_genap(10))",
    "output": "Arahan: \n- Buat fungsi cek_ganjil_genap yang menerima satu parameter angka.\n- Gunakan percabangan if-else untuk menentukan ganjil atau genap.\n- Tampilkan hasil menggunakan print()."
  }
]

Gunakan materi berikut sebagai dasar penyusunan soal:
$materi
PROMPT;

        $promptJson = json_encode($prompt, JSON_UNESCAPED_UNICODE);
        try {
            $response = Http::timeout(90)
                ->post('https://iswara-code-evaluation-system.onrender.com/askLlama', [
                    'prompt' => $promptJson
                ]);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghubungi AI service.'
                ], 500);
            }

            // Langsung ambil array dari response JSON
            $data = $response->json('data');
            if (!$data || !is_array($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada hasil yang dikembalikan oleh AI.'
                ], 422);
            }

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memanggil API: ' . $e->getMessage()
            ], 500);
        }
    }
}
