<form action="{{ url()->current() }}" method="GET" class="ms-auto join">
    <label class="input join-item">
        <input type="search" name="search" id="search" value="{{ request('search') }}" placeholder="Search" />
    </label>
    <button class="btn btn-soft join-item">
        <x-lucide-search />
    </button>
</form>