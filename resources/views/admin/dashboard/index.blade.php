@extends('layouts.admin.index')

@section('title', 'Dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-3">
            <div class="card-header">
                <h5>Prestasi Akademik</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($acamedicCountByLevel as $activity)
                        <div class="col-lg-4 col-sm-6 mb-2">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar me-4">
                                    <span class="avatar-initial rounded bg-label-primary"><i
                                            class="bx bx-notepad bx-lg"></i></span>
                                </div>
                                <h4 class="mb-0">{{ $activity->total ?? 0 }}</h4>
                            </div>
                            <p class="mb-2">{{ $activity->name }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">
                <h5>Prestasi Non Akademik</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($nonAcamedicCountByLevel as $activity)
                        <div class="col-lg-4 col-sm-6 mb-2">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar me-4">
                                    <span class="avatar-initial rounded bg-label-primary"><i
                                            class="bx bx-notepad bx-lg"></i></span>
                                </div>
                                <h4 class="mb-0">{{ $activity->total ?? 0 }}</h4>
                            </div>
                            <p class="mb-2">{{ $activity->name }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
@endsection
