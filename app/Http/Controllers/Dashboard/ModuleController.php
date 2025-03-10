<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\ModuleContent;
use App\Models\Quiz;
use App\Models\QuizChoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ModuleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth'); // Pastikan hanya user login yang bisa mengakses controller ini
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $modules = Module::whereNull('deleted_at')
            ->get();

        $teachers = User::where('role', 'teacher')
            ->whereNull('deleted_at')
            ->get();

        return view('admin.pembelajaran.module', [
            'modules' => $modules,
            'teachers' => $teachers,
            'activeMenu' => 'module'
        ]);
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
        try {
            DB::beginTransaction(); // Mulai transaksi

            $request->validate([
                'teacherCreate' => 'required|exists:users,id',
                'titleCreate' => 'required|string|max:255',
                'statusCreate' => 'required|integer',
                'meetingsCreate' => 'required|integer',
                // selectedStudents berupa JSON string (opsional)
            ]);

            $teacherUser = User::find($request->teacherCreate);
            if (!$teacherUser || !$teacherUser->teacher) {
                return redirect()->back()
                    ->withInput()
                    ->with('toasts', [
                        [
                            'type' => 'danger',
                            'title' => 'Gagal',
                            'message' => 'Teacher data not found',
                            'time' => now()->diffForHumans(),
                        ]
                    ])->with('form_error', 'create');
            }
            $teacher_id = $teacherUser->teacher->id;

            // Buat record kelas baru
            $module = new Module();
            $module->title = $request->titleCreate;
            $module->status = $request->statusCreate;
            $module->teacher_id = $teacher_id;
            $module->save();

            // Loop sesuai jumlah pertemuan
            for ($i = 1; $i <= $request->meetingsCreate; $i++) {
                // Buat ModuleContent
                $content = new ModuleContent();
                $content->module_id = $module->id;
                $content->title = "Pertemuan $i";
                $content->save();

                // Buat minimal 1 quiz untuk setiap content
                $quiz = new Quiz();
                $quiz->content_id = $content->id;
                $quiz->question = "Pertanyaan kuis untuk pertemuan $i";
                $quiz->type = "multiple_choice";
                $quiz->save();

                // Buat minimal 1 quiz choice
                $choice = new QuizChoice();
                $choice->quiz_id = $quiz->id;
                $choice->choice_text = "Jawaban A";
                $choice->is_correct = true;
                $choice->feedback = "Jawaban benar";
                $choice->save();
            }

            DB::commit();

            return redirect()->back()->with('toasts', [
                [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'message' => "Kelas {$module->name} berhasil ditambahkan",
                    'time' => now()->diffForHumans()
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = $e->getMessage();

            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return redirect()->back()
                    ->withErrors($e->errors())
                    ->withInput()
                    ->with('toasts', [
                        [
                            'type' => 'danger',
                            'title' => 'Gagal',
                            'message' => 'Terjadi kesalahan saat validasi data.',
                            'time' => now()->diffForHumans(),
                        ]
                    ])->with('form_error', 'create');
            }

            return redirect()->back()
                ->withErrors(['general' => $errorMessage])
                ->withInput()
                ->with('toasts', [
                    [
                        'type' => 'danger',
                        'title' => 'Gagal',
                        'message' => 'Terjadi kesalahan: ' . $errorMessage,
                        'time' => now()->diffForHumans(),
                    ]
                ])->with('form_error', 'create');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $module = Module::find($id)->first();

        $contents = ModuleContent::where('module_id', $id)->get();

        $allTeachers = User::where('role', 'teacher')
            ->whereNull('deleted_at')
            ->get();

        return view('admin.pembelajaran.detailModule', [
            'module' => $module,
            'contents' => $contents,
            'allTeacher' => $allTeachers,
            'activeMenu' => 'module'
        ]);
    }

    public function getModuleContent(string $moduleId, string $contentId)
    {
        $content = ModuleContent::where('module_id', $moduleId)
            ->where('id', $contentId)
            ->first();
    
        if (!$content) {
            return response()->json(['error' => 'Content not found'], 404);
        }
    
        return response()->json([
            'id' => $content->id,
            'title' => $content->title,
            'summary' => $content->summary,
            'content' => $content->content
        ]);
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
                'titleEdit' => 'required|string|max:255',
                'statusEdit' => 'required|integer',
                'descriptionEdit' => 'nullable|string',
                'teacherEdit' => 'required|exists:users,id',
            ]);

            // Ambil data kelas berdasarkan ID

            $module = Module::findOrFail($id);

            $teacherUser = User::find($request->teacherEdit);
            if (!$teacherUser || !$teacherUser->teacher) {
                return redirect()->back()
                    ->withInput()
                    ->with('toasts', [
                        [
                            'type' => 'danger',
                            'title' => 'Gagal',
                            'message' => 'Teacher data not found',
                            'time' => now()->diffForHumans(),
                        ]
                    ])->with('form_error', 'update');
            }

            $module->title = $request->titleEdit;
            $module->status = $request->statusEdit;
            $module->description = $request->descriptionEdit;
            $module->teacher_id = $teacherUser->teacher->id;


            // **Save data**
            $module->save();

            DB::commit();

            return redirect()->back()->with('toasts', [
                [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'message' => "Modul {$module->title} berhasil diperbarui",
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

    public function uploadImage(Request $request, $id)
    {
        $request->validate([
            'imageUpload' => 'required|image|mimes:jpeg,png,gif|max:800',
        ]);

        $module = Module::findOrFail($id);

        // Simpan gambar baru
        $path = $request->file('imageUpload')->store('modules', 'public');
        $module->image = $path;
        $module->save();

        return response()->json(['message' => 'Image uploaded successfully']);
    }

    public function resetImage($id)
    {
        $module = Module::findOrFail($id);

        // Hapus gambar dari penyimpanan jika ada
        if ($module->image) {
            Storage::delete('public/' . $module->image);
            $module->image = null;
            $module->save();
        }

        return response()->json(['success' => true, 'message' => 'Gambar berhasil direset']);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
