<div class="row">
    <div class="col-md-6 mb-6">
        <label class="form-label" for="basic-default-fullname">Nama Kegiatan</label>
        <input type="text" name="name" class="form-control @error('name')
      invalid
  @enderror"
            value="{{ isset($activity) ? $activity->name : old('name') }}">
        @error('name')
            <div class="small text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6 mb-6">
        <label class="form-label" for="basic-default-fullname">Kategori Kegiatan</label>
        <select name="activity_category_id" id="activity_category"
            class="form-select @error('activity_category_id')
       invalid
   @enderror">
            <option disabled selected>-- Pilih Kategori --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ isset($activity) && $activity->activity_category_id == $category->id ? 'selected' : (old('activity_category_id') == $category->id ? 'selected' : '') }}>
                    {{ $category->name }}</option>
            @endforeach
        </select>
        @error('activity_category_id')
            <div class="small text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6 mb-6">
        <label class="form-label" for="basic-default-fullname">Jenis Kegiatan</label>
        <select id="activity_type" name="activity_type_id" id=""
            class="form-select @error('activity_type_id')
       invalid
   @enderror">
            <option disabled selected>-- Pilih Jenis Kegiatan --</option>
        </select>
        @error('activity_type_id')
            <div class="small text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-6 mb-6">
        <label class="form-label" for="basic-default-fullname">Tanggal Pelaksanaan</label>
        <input type="date" name="date" class="form-control @error('date')
      invalid
  @enderror"
            value="{{ isset($activity) ? $activity->date : old('date') }}">
        @error('date')
            <div class="small text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6 mb-6">
        <label class="form-label" for="basic-default-fullname">Kota Tempat Kegiatan</label>
        <input type="text" name="place" class="form-control @error('place')
      invalid
  @enderror"
            value="{{ isset($activity) ? $activity->place : old('place') }}">
        @error('place')
            <div class="small text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6 mb-6">
        <label class="form-label" for="basic-default-fullname">Tingkat Kegiatan</label>
        <select name="level_id" id="" class="form-select @error('level_id')
       invalid
   @enderror">
            <option disabled selected>-- Pilih Tingkat --</option>
            @foreach ($levels as $level)
                <option value="{{ $level->id }}"
                    {{ isset($activity) && $activity->level_id == $level->id ? 'selected' : (old('level_id') == $level->id ? 'selected' : '') }}>
                    {{ $level->name }}</option>
            @endforeach
        </select>
        @error('level_id')
            <div class="small text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6 mb-6">
        <label class="form-label" for="basic-default-fullname">Jenis Prestasi</label>
        <select name="award_type" class="form-select @error('award_type')
       invalid
   @enderror">
            <option disabled selected>-- Pilih Jenis Prestasi --</option>
            <option value="1"
                {{ isset($activity) && $activity->award_type == 1 ? 'selected' : (old('award_type') == '1' ? 'selected' : '') }}>
                Akademik</option>
            <option value="2"
                {{ isset($activity) && $activity->award_type == 2 ? 'selected' : (old('award_type') == '2' ? 'selected' : '') }}>
                Non Akademik</option>
        </select>
        @error('award_type')
            <div class="small text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6 mb-6">
        <label class="form-label" for="basic-default-fullname">Prestasi</label>
        <select id="award" name="award_id" id=""
            class="form-select @error('award_id')
       invalid
   @enderror">
            <option disabled selected>-- Pilih Prestasi --</option>
        </select>
        @error('award_id')
            <div class="small text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-6 mb-6">
        <label class="form-label" for="basic-default-fullname">Foto Kegiatan</label>
        <input type="file" name="photo" class="form-control @error('photo')
      invalid
  @enderror">
        @error('photo')
            <div class="small text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-6 mb-6">
        <label class="form-label" for="basic-default-fullname">Upload Sertifikat</label>
        <input type="file" name="file" class="form-control @error('file')
      invalid
  @enderror">
        @error('file')
            <div class="small text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-12 mb-6">
        <label class="form-label" for="basic-default-fullname">Deskripsi Kegiatan</label>
        <textarea name="description" class="form-control @error('description')
        invalid
    @enderror">
            {{ isset($activity) ? $activity->description : old('description') }}
        </textarea>
        @error('description')
            <div class="small text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

@push('script')
    <script>
        $(document).ready(function() {
            // Pre-select the activity type and award based on selected category
            let selectedCategoryId = $('#activity_category').val();
            let selectedAwardId = "{{ isset($activity) ? $activity->award_id : old('award_id') }}";
            if (selectedCategoryId) {
                // Load activity types and awards if a category is selected
                $.ajax({
                    url: '/admin/get-activity-details',
                    method: 'GET',
                    data: {
                        category_id: selectedCategoryId
                    },
                    success: function(response) {
                        // Populate activity types
                        response.types.forEach(function(type) {
                            $('#activity_type').append(
                                `<option value="${type.id}" ${selectedCategoryId == type.id ? 'selected' : ''}>${type.name}</option>`
                            );
                        });

                        // Populate awards
                        response.awards.forEach(function(award) {
                            $('#award').append(
                                `<option value="${award.id}" ${selectedAwardId == award.id ? 'selected' : ''}>${award.name}</option>`
                            );
                        });
                    }
                });
            }

            // Handle category change and update dependent fields
            $('#activity_category').on('change', function() {
                let categoryId = $(this).val();
                $('#award').empty().append('<option value="">Pilih Prestasi</option>');
                $('#activity_type').empty().append('<option value="">Pilih Jenis Kegiatan</option>');

                if (categoryId) {
                    $.ajax({
                        url: '/admin/get-activity-details',
                        method: 'GET',
                        data: {
                            category_id: categoryId
                        },
                        success: function(response) {
                            response.types.forEach(function(type) {
                                $('#activity_type').append(
                                    `<option value="${type.id}">${type.name}</option>`
                                );
                            });

                            response.awards.forEach(function(award) {
                                $('#award').append(
                                    `<option value="${award.id}">${award.name}</option>`
                                );
                            });
                        },
                        error: function() {
                            alert('Terjadi kesalahan, silakan coba lagi.');
                        }
                    });
                }
            });
        });
    </script>
@endpush
