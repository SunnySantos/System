@extends('layouts.base')

@push('styles')
<style>
    .test {
        color: gray;
    }
</style>
@endpush

@push('scripts')
<script>
    websiteTheme();
    dataTableCheckbox();
    bulkDeleteForm();

    function websiteTheme() {
        // Restore theme from localStorage
        const storedTheme = localStorage.getItem("theme") || "light";
        document.documentElement.setAttribute("data-theme", storedTheme);

        // Watch for changes from the toggle
        document.querySelectorAll('.theme-controller').forEach(toggle => {
            toggle.checked = storedTheme === "dark";

            toggle.addEventListener('change', (e) => {
                const theme = e.target.checked ? 'dark' : 'light';
                document.documentElement.setAttribute("data-theme", theme);
                localStorage.setItem("theme", theme);
            });
        });
    }

    function dataTableCheckbox() {
        const dataTable = document.getElementById('data_table');
        const selectAllCheckboxes = document.getElementById('select_all_checkboxes');
        const deleteSelectedIdBtn = document.getElementById('delete_selected_id_btn');
        const selectedIds = document.getElementById('selected_ids');

        if (selectAllCheckboxes && dataTable && deleteSelectedIdBtn && selectedIds) {
            const allCheckboxes = dataTable.querySelectorAll('tbody .checkbox');

            selectAllCheckboxes.addEventListener('click', (e) => {
                allCheckboxes.forEach(checkbox => checkbox.checked = e.target.checked);
            });

            // Event delegation: handle individual checkbox changes
            dataTable.addEventListener('change', (e) => {
                const cb = e.target;
                if (cb.classList.contains('checkbox')) {
                    const checkedBoxes = dataTable.querySelectorAll('tbody .checkbox:checked');
                    selectAllCheckboxes.checked = checkedBoxes.length === allCheckboxes.length;
                    deleteSelectedIdBtn.disabled = checkedBoxes.length === 0;
                    selectedIds.value = Array.from(checkedBoxes).map(c => c.value).join(',');
                }
            });
        }
    }

    function bulkDeleteForm() {
        let form = document.getElementById('bulk_delete_form');

        if (form) {
            form.addEventListener('submit', (e) => {
                const singular = form.dataset.singular;
                const plural = form.dataset.plural;
                const checkedBox = document.querySelectorAll('#data_table tbody .checkbox:checked');

                if (!confirm(`Are you sure you want to delete ${checkedBox.length > 1 ? 'these ' + plural : 'this ' + singular }`)) {
                    e.preventDefault(); // only block submission when user clicks "Cancel"
                }
            });
        }
    }
</script>
@endpush