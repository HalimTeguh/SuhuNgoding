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

        $prompt = <<<PROMPT
Kamu adalah seorang guru profesional yang berfokus pada pengajaran materi pemrograman untuk tingkat Sekolah Menengah Atas (SMA). Tugasmu adalah membuat soal latihan berbasis JSON sesuai materi yang diberikan.

Setiap materi harus memiliki **10 soal latihan**, terdiri dari 5 level Taksonomi Bloom berikut:

1. **Mengingat (Remember)** – 2 soal
2. **Memahami (Understand)** – 2 soal
3. **Menerapkan (Apply)** – 2 soal
4. **Menganalisis (Analyze)** – 2 soal
5. **Mengevaluasi (Evaluate)** – 2 soal

### 🔹 Format Soal:

- **Level Remember, Understand, Analyze, Evaluate**: berbentuk **pilihan ganda** (multiple choice, 4 opsi)
- **Level Apply**: berbentuk **studi kasus kode** yang membutuhkan **referensi kode dan penjelasan output**

### 🔹 Assessment Terms per Level (wajib dijadikan dasar menyusun pertanyaan):

- **Remember**
    - Mengidentifikasi bagian kode
    - Mengenali implementasi konsep
    - Mengenali deskripsi yang sesuai
    - Mengingat materi yang dipelajari
- **Understand**
    - Menerjemahkan algoritma tertentu
    - Menjelaskan konsep algoritma
    - Memahami contoh konsep atau algoritma
- **Apply**
    - Mengimplementasikan konsep yang dipelajari
    - Menyelesaikan studi kasus sederhana
- **Analyze**
    - Memecah tugas program menjadi beberapa komponen
    - Mengidentifikasi komponen penting dan yang tidak penting
- **Evaluate**
    - Menentukan apakah sebuah kode dapat menyelesaikan studi kasus tertentu
    - Menilai kualitas dan standar kode dengan benar

Berikan jawaban langsung dalam format JSON array, **tanpa narasi pembuka**, dan mulai dengan tanda kurung siku.

Contoh Format Output JSON (berbahasa Indonesia):
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
        "answer": "Hanya simbol matematika dalam kode",
        "feedback": "Salah, itu hanya operator bukan ekspresi.",
        "is_correct": false
      },
      ...
    ]
  },
  {
    "level": "Menerapkan",
    "question": "Berikut adalah potongan kode. Apa output dari kode berikut?",
    "reference_code": "x = 3 + 4 * 2\\nprint(x)",
    "output": "11",
    "feedback": "Operator * memiliki prioritas lebih tinggi dari +, sehingga 4 * 2 = 8, lalu 3 + 8 = 11."
  }
]


Gunakan materi berikut sebagai dasar penyusunan soal:

$materi
PROMPT;

        try {
            $response = Http::withToken(env('HUGGINGFACE_API_TOKEN'))
                ->timeout(60)
                ->post('https://router.huggingface.co/together/v1/chat/completions', [
                    'model' => 'meta-llama/Llama-3.3-70B-Instruct-Turbo',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => 0.3,
                    'top_p' => 0.85,
                    'max_tokens' => 3072
                ]);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Model gagal merespons.'
                ], 500);
            }

            $jsonText = $response->json()['choices'][0]['message']['content'] ?? null;

            if (!$jsonText) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada konten yang dikembalikan oleh model.'
                ], 422);
            }

            // Hapus teks pembuka sebelum tanda [
            $jsonStart = strpos($jsonText, '[');
            if ($jsonStart === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Output tidak mengandung JSON array.'
                ], 422);
            }

            $cleanJson = substr($jsonText, $jsonStart);
            $data = json_decode($cleanJson, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal parsing JSON: ' . json_last_error_msg(),
                    'raw' => $cleanJson
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
