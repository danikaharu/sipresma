<div class="row">
    <div class="col-md-6 mb-6">
        <label class="form-label" for="basic-default-fullname">Kategori Kegiatan</label>
        <select name="activity_category_id" id=""
            class="form-select @error('activity_category_id')
       invalid
   @enderror">
            <option disabled selected>-- Pilih Kategori --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ isset($activityType) && $activityType->activity_category_id == $category->id ? 'selected' : (old('activity_category_id') == $category->id ? 'selected' : '') }}>
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
        <label class="form-label" for="basic-default-fullname">Nama</label>
        <input type="text" name="name" class="form-control @error('name')
      invalid
  @enderror"
            value="{{ isset($activityType) ? $activityType->name : old('name') }}">
        @error('name')
            <div class="small text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
