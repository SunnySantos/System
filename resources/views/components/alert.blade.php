<div role="alert" class="alert {{ $classes() }} alert-soft mb-4">
    {{-- Icon --}}
    <x-dynamic-component :component="$icon()"/>
    {{-- Message --}}
    <span>{{ $message }}</span>
</div>