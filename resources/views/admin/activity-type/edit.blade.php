@extends('layouts.admin.index')

@section('title', 'Edit Jenis Kegiatan')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <form action="{{ route('admin.activity-type.update', $activityType->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card mb-6">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Edit Data Jenis Kegiatan</h5>
                        </div>
                        <div class="card-body">
                            @include('admin.activity-type.include.form')
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
