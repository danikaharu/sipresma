<div class="d-flex">

    @can('edit level')
        <a class="btn btn-warning me-2" href="{{ route('admin.level.edit', $id) }}"><i class="bx bx-edit-alt me-1"></i>
            Edit</a>
    @endcan

    @can('delete level')
        <form action="{{ route('admin.level.destroy', $id) }}" method="POST" role="alert" alert-title="Hapus Data"
            alert-text="Yakin ingin menghapusnya?">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger me-2"><i class="bx bx-trash">Hapus</i>
            </button>
        </form>
    @endcan
</div>
