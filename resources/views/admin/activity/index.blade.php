@extends('layouts.admin.index')

@section('title', 'Kegiatan')

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css">
    <style>
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_info {
            margin-left: 1rem;
        }

        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_paginate {
            margin-right: 1rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- Filter Form -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filter_category">{{ __('Kategori Kegiatan') }}</label>
                                    <select id="filter_category" class="form-select" name="filter_category">
                                        <option disabled selected>-- Pilih Kategori Kegiatan --</option>
                                        @foreach ($activityCategories as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filter_level">{{ __('Tingkat Kegiatan') }}</label>
                                    <select id="filter_level" class="form-select" name="filter_level">
                                        <option disabled selected>-- Pilih Tingkat Kegiatan --</option>
                                        @foreach ($levels as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filter_year">{{ __('Tahun Kegiatan') }}</label>
                                    <input id="filter_year" class="form-control" name="filter_year" type="text">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filter_promotion">{{ __('Tahun Angkatan') }}</label>
                                    <input id="filter_enrollment" class="form-control" name="filter_enrollment"
                                        type="text">
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button id="filter" type="submit" class="btn btn-primary">Cari</button>
                            <button id="reset" type="reset" class="btn btn-outline-primary">Reset</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Data Kegiatan</h5>
                        <div class="btn-group">
                            @can('create activity')
                                <a class="btn btn-primary" href="{{ route('admin.activity.create') }}">
                                    <i class="bx bx-plus me-1"></i>Input Data Kegiatan
                                </a>
                            @endcan

                            @can('export activity')
                                <form id="exportForm" method="GET" action="{{ route('admin.activity.export') }}">
                                    <input type="hidden" name="filter_category">
                                    <input type="hidden" name="filter_level">
                                    <input type="hidden" name="filter_type">
                                    <input type="hidden" name="filter_year">
                                    <input type="hidden" name="filter_enrollment">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bxs-printer me-1"></i>Cetak Data
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>

                    <div class="table-responsive text-nowrap">
                        <table class="table" id="listData">
                            <thead>
                                <tr class="text-nowrap">
                                    <th>No.</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Angkatan</th>
                                    <th>Kategori</th>
                                    <th>Tingkat</th>
                                    <th>Jenis</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan dimuat melalui JavaScript atau Laravel -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" defer></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js" defer></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js" defer></script>
    <script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js" defer></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $('#listData').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                rowReorder: {
                    selector: 'td:nth-child(2)'
                },
                ajax: {
                    url: "{{ route('admin.activity.getStudent') }}",
                    type: 'GET',
                    data: function(d) {
                        d.filter_category = $('#filter_category').val();
                        d.filter_level = $('#filter_level').val();
                        d.filter_type = $('#filter_type').val();
                        d.filter_year = $('#filter_year').val();
                        d.filter_enrollment = $('#filter_enrollment').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                    },
                    {
                        data: 'student_number',
                    },
                    {
                        data: 'student_name',
                    },
                    {
                        data: 'student_enrollment',
                    },
                    {
                        data: 'category',
                    },
                    {
                        data: 'level',
                    },
                    {
                        data: 'award_type',
                    },
                    {
                        data: 'status',
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });

            $('#filter').click(function() {
                $('#listData').DataTable().draw(true);
            });

            $('#reset').click(function() {
                $('#filter_category').val('');
                $('#filter_level').val('');
                $('#filter_type').val('');
                $('#filter_year').val('');
                $('#filter_enrollment').val('');
            })

            $('#exportForm').on('submit', function(e) {
                // Set nilai filter ke input hidden sebelum submit form
                $(this).find('input[name="filter_category"]').val($('#filter_category').val());
                $(this).find('input[name="filter_level"]').val($('#filter_level').val());
                $(this).find('input[name="filter_type"]').val($('#filter_type').val());
                $(this).find('input[name="filter_year"]').val($('#filter_year').val());
                $(this).find('input[name="filter_enrollment"]').val($('#filter_enrollment').val());
            });


            // Sweet Alert Delete
            $("body").on('submit', `form[role='alert']`, function(event) {
                event.preventDefault();

                Swal.fire({
                    title: $(this).attr('alert-title'),
                    text: $(this).attr('alert-text'),
                    icon: "warning",
                    allowOutsideClick: false,
                    showCancelButton: true,
                    cancelButtonText: "Batal",
                    reverseButton: true,
                    confirmButtonText: "Hapus",
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.target.submit();
                    }
                })
            });
        });
    </script>
@endpush
