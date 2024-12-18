@extends('layouts.admin.index')

@section('title', 'Edit Data Role')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-2"><span class="text-muted fw-light">Beranda / Data Role / </span> Edit Data Role</h5>
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Edit Data Role</h5>
                        <h6 class="card-subtitle text-muted">Halaman Mengubah Data Role</h6>
                        <form class="my-4" action="{{ route('admin.role.update', $role->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            @include('admin.role.include.form')

                            <div class="mt-3">
                                <a href="{{ route('admin.role.index') }}" class="btn btn-secondary"><i
                                        class="bx bx-arrow-back"></i> Kembali</a>
                                <button type="submit" class="btn btn-primary"><i class="bx bxs-save"></i> Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection