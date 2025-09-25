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
</script>
@endpush