<form action="{{ route($route) }}" method="POST" id="bulk_delete_form" data-singular="{{ $singular }}" data-plural="{{ $plural }}">
    @csrf
    @method('DELETE')
    <input type="hidden" name="ids" id="selected_ids">
    <button class="btn btn-soft btn-error" id="delete_selected_id_btn" disabled="disabled">
        <x-lucide-trash />
        Delete
    </button>
</form>