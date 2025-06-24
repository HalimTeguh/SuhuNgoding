@extends('layout.dashboard')

@section('content')

@php
$tTest = $user->student->tTests()->first();
@endphp

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    @if($tTest && $tTest->class_type == null)
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class=" card-body">
                <div class="alert alert-warning">
                    Tipe kelas anda belum di bagi, pastikan anda telah mengerjakan <strong>pretest</strong>. Hubungi <strong>Admin atau Guru</strong>
                    untuk
                    informasi lebih lanjut
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            @foreach ($classes as $class)
            <div class="col-xxl-6 col-md-6 mb-4">
                <div class="card text-white overflow-hidden border-0 rounded shadow" style="background-image: url('{{ asset('storage/' . $class->image) }}');
                        background-size: cover;
                        background-position: center;
                        position: relative; min-height: 200px;">

                    {{-- Gradient overlay --}}
                    <div style="position: absolute;
                            inset: 0;
                            background: linear-gradient(to right, rgba(0,0,0,0.7), rgba(0,0,0,0.1));
                            z-index: 1;">
                    </div>

                    {{-- Content --}}
                    <div class="card-body d-flex justify-content-between align-items-center h-100 position-relative"
                        style="z-index: 2;">
                        <div>
                            <h5 class="card-title mb-2 text-white">{{ $class->name }}</h5>
                            <p class="mb-1"><strong>Guru:</strong> {{ $class->teacher->user->name ?? '-' }}</p>
                            <p class="mb-0">{{ Str::limit($class->description, 80) }}</p>
                        </div>
                        <div>
                            <a href="{{ route('dashboard.student.class.show', $class->id) }}"
                                class="btn btn-m btn-primary shadow">
                                Masuk Kelas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    <!-- / Content -->
    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->

@endsection