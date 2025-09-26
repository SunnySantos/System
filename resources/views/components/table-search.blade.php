<form action="{{ url()->current() }}" method="GET" class="ms-auto">
    <label class="input">
        <x-lucide-search />
        <input type="search" name="search" id="search" value="{{ request('search') }}" placeholder="Search" />
    </label>
</form>