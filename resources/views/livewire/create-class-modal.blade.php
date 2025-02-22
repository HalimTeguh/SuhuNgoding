<div class="modal fade" id="createClass" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="bs-stepper">
                    <!-- Step Indicators -->
                    <div class="bs-stepper-header" role="tablist">
                        <div class="step {{ $currentStep >= 1 ? 'active' : '' }}" data-target="#step-1">
                            <button type="button" class="step-trigger d-flex align-items-center">
                                <span class="bs-stepper-box custom-stepper-box bg-primary">1</span>
                                <span class="bs-stepper-label d-flex flex-column ms-3">
                                    <div class="step-title text-primary ">Account Details</div>
                                    <div class="step-subtitle text-secondary">Setup your account</div>
                                </span>
                            </button>
                        </div>
                        <div class="step {{ $currentStep >= 2 ? 'active' : '' }}" data-target="#step-2">
                            <div class="d-flex">
                                <i class="bx bx-chevron-right arrow-icon"></i>
                                <button type="button" class="step-trigger d-flex align-items-center">
                                    <span class="bs-stepper-box custom-stepper-box bg-primary">2</span>
                                    <span class="bs-stepper-label d-flex flex-column ms-3">
                                        <div class="step-title text-primary ">Users Class</div>
                                        <div class="step-subtitle text-secondary">Setup teachers and students in the
                                            classroom</div>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="step {{ $currentStep >= 3 ? 'active' : '' }}" data-target="#step-3">
                            <div class="d-flex">
                                <i class="bx bx-chevron-right arrow-icon"></i>
                                <button type="button" class="step-trigger d-flex align-items-center">
                                    <span class="bs-stepper-box custom-stepper-box bg-primary">3</span>
                                    <span class="bs-stepper-label d-flex flex-column ms-3">
                                        <div class="step-title text-primary ">Confirmation</div>
                                        <div class="step-subtitle text-secondary">Confirm class data</div>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="bs-stepper-header">
                        <!-- Step 1 -->
                        <div class="step {{ $currentStep >= 1 ? 'active' : '' }}">
                            <span class="bs-stepper-circle">1</span>
                            <span class="bs-stepper-label">Account Details</span>
                        </div>
                        <!-- Step 2 -->
                        <div class="step {{ $currentStep >= 2 ? 'active' : '' }}">
                            <span class="bs-stepper-circle">2</span>
                            <span class="bs-stepper-label">Users Class</span>
                        </div>
                        <!-- Step 3 -->
                        <div class="step {{ $currentStep >= 3 ? 'active' : '' }}">
                            <span class="bs-stepper-circle">3</span>
                            <span class="bs-stepper-label">Confirmation</span>
                        </div>
                    </div> --}}

                    <!-- Step Contents -->
                    <div class="bs-stepper-content">
                        <!-- Step 1 -->
                        @if($currentStep == 1)
                        <div id="step-1" class="content" role="tabpanel" aria-labelledby="step-1-trigger">


                            <div class="content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label>Class Image</label>
                                            @if($image)
                                            <img src="{{ $image->temporaryUrl() }}" class="img-preview">
                                            @else
                                            <div class="image-placeholder">
                                                <i class="fas fa-image"></i>
                                            </div>
                                            @endif
                                            <input type="file" wire:model="image" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label>Class Name</label>
                                            <input type="text" wire:model="name" class="form-control">
                                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label>Description</label>
                                            <textarea wire:model="description" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary" wire:click="nextStep">Next</button>
                            </div>
                        </div>

                        @endif


                        <!-- Step 2 -->
                        @if($currentStep == 2)
                        <div class="content">
                            <div class="mb-3">
                                <label>Teacher</label>
                                <select wire:model="teacherId" class="form-select">
                                    <option value="">Select Teacher</option>
                                    @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                                @error('teacherId') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <button class="btn btn-secondary" wire:click="previousStep">Back</button>
                            <button class="btn btn-primary" wire:click="nextStep">Next</button>
                        </div>
                        @endif

                        <!-- Step 3 -->
                        @if($currentStep == 3)
                        <div class="content">
                            <!-- Confirmation Content -->
                            <button class="btn btn-secondary" wire:click="previousStep">Back</button>
                            <button class="btn btn-success">Submit</button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>