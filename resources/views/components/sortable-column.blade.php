<a href="{{ $url() }}">
    <span>{{ $label }}</span>
    <x-dynamic-component :component="$icon()" class="float-right" />
</a>