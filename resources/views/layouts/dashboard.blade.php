@extends('layouts.base')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<style>
    .icon {
        width: 3rem;
    }

    .item {
        width: 100%;
    }


    [data-theme="dark"] .ts-control,
    [data-theme="dark"] .ts-wrapper.single.input-active .ts-control,
    [data-theme="dark"] .ts-dropdown {
        background-color: var(--color-base-100);
    }

    [data-theme="dark"] .ts-dropdown,
    [data-theme="dark"] .ts-control>input {
        color: var(--color-base-content);
    }

    [data-theme="dark"] .ts-dropdown .active {
        background-color: var(--color-base-200);
        color: var(--color-base-content);
    }

    .ts-dropdown,
    .ts-control,
    .ts-control input {
        color: var(--color-base-content);
        font-size: 0.875rem;
        line-height: inherit;
    }

    .ts-control {
        padding-inline: calc(0.25rem * 3);
        border-radius: var(--radius-field);
    }

    #profile_edit {
        translate: var(--indicator-x, 50%) var(--indicator-y, -50%);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

<script>
    const api = 'http://localhost:8000/api';

    websiteTheme();
    dataTableCheckbox();
    bulkDeleteForm();
    geoDropdowns();

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
                const isChecked = e.target.checked;
                e.target.checked = !isChecked;
                allCheckboxes.forEach(checkbox => checkbox.checked = isChecked);
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


    function geoDropdowns() {
        if (document.querySelectorAll('#country, #state, #city').length !== 3) return;

        let countrySelect = new TomSelect('#country', {
            valueField: 'id',
            labelField: 'name',
            searchField: 'name',
            preload: true,
            load: async function(query, callback) {
                try {
                    const response = await fetch(`${api}/countries`);
                    const data = await response.json();
                    callback(data);
                } catch (e) {
                    callback();
                }
            },
            render: {
                option: (item, escape) => `<div>${escape(item.name)}</div>`,
                item: (item, escape) => {
                    document.getElementById('country_name').value = item.name;
                    return `<div>${escape(item.name)}</div>`;
                },
            },
        });


        // State select (depends on country)
        let stateSelect = new TomSelect("#state", {
            valueField: "id",
            labelField: "name",
            searchField: "name",
            load: function(query, callback) {
                let countryId = countrySelect.getValue();
                if (!countryId.length) return callback();

                fetch(`/api/states/${countryId}`)
                    .then(response => response.json())
                    .then(data => callback(data))
                    .catch(() => callback());
            },
            render: {
                option: (item, escape) => `<div>${item.name}</div>`,
                item: (item, escape) => {
                    document.getElementById('state_name').value = item.name;
                    return `<div>${item.name}</div>`;
                }
            },
        });

        // City select (depends on state)
        let citySelect = new TomSelect("#city", {
            valueField: "id",
            labelField: "name",
            searchField: "name",
            load: function(query, callback) {
                let stateId = stateSelect.getValue();
                if (!stateId.length) return callback();

                fetch(`/api/cities/${stateId}`)
                    .then(response => response.json())
                    .then(data => callback(data))
                    .catch(() => callback());
            },
            render: {
                option: (item, escape) => `<div>${item.name}</div>`,
                item: (item, escape) => {
                    document.getElementById('city_name').value = item.name;
                    return `<div>${item.name}</div>`;
                }
            },
        });

        // Reload states when country changes
        countrySelect.on("change", function() {
            stateSelect.clear(); // Clear old state selection
            stateSelect.clearOptions(); // Remove old options
            stateSelect.load(stateSelect.settings.load); // Reload states
            citySelect.clear();
            citySelect.clearOptions();
        });

        // Reload cities when state changes
        stateSelect.on("change", function() {
            citySelect.clear();
            citySelect.clearOptions();
            citySelect.load(citySelect.settings.load);
        });
    }


    const profileEditButton = document.getElementById('profile_edit');
    const profileInput = document.getElementById('profile')
    if (profileEditButton && profileInput) {
        profileEditButton.addEventListener('click', function() {
            profileInput.click();
        });

        profileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile_picture').src = e.target.result;
                }
                reader.readAsDataURL(file)
            }
        });
    }
</script>
@endpush