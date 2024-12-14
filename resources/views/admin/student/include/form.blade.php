<div class="row">
    <div class="col-md-6 mb-6">
        <label class="form-label" for="basic-default-fullname">NIM</label>
        <input type="number" name="student_number"
            class="form-control @error('student_number')
           invalid
       @enderror"
            value="{{ isset($student) ? $student->student_number : old('student_number') }}">
        @error('student_number')
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
            value="{{ isset($student) ? $student->name : old('name') }}">
        @error('name')
            <div class="small text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6 mb-6">
        <label class="form-label" for="basic-default-fullname">Program Studi</label>
        <select name="program" class="form-select @error('program')
      invalid
  @enderror">
            <option disabled selected>-- Pilih Program Studi --</option>
            <option value="1"
                {{ isset($student) && $student->program == 1 ? 'selected' : (old('program') == '1' ? 'selected' : '') }}>
                Sistem Informasi</option>
            <option value="2"
                {{ isset($student) && $student->program == 2 ? 'selected' : (old('program') == '2' ? 'selected' : '') }}>
                Pendidikan Teknologi Informasi</option>
        </select>
        @error('program')
            <div class="small text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6 mb-6">
        <label class="form-label" for="basic-default-fullname">Angkatan</label>
        <input type="number" name="enrollment_year"
            class="form-control @error('enrollment_year')
          invalid
      @enderror"
            value="{{ isset($student) ? $student->enrollment_year : old('enrollment_year') }}">
        @error('enrollment_year')
            <div class="small text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6 mb-6">
        <label class="form-label" for="basic-default-fullname">Tanggal Lahir</label>
        <input type="date" name="birth_date"
            class="form-control @error('birth_date')
           invalid
       @enderror"
            value="{{ isset($student) ? $student->birth_date : old('birth_date') }}">
        @error('birth_date')
            <div class="small text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="col-md-6 mb-6">
        <label class="form-label" for="basic-default-fullname">Alamat</label>
        <textarea name="address" class="form-control @error('address')
        invalid
    @enderror">
            {{ isset($student) ? $student->address : old('address') }}
        </textarea>
        @error('address')
            <div class="small text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
