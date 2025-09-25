<div>
    <h1>Friends</h1>
    <ul>
        @foreach ($users as $user)
        <li>{{ $user->name }} - {{ $user->email }}</li>
        @endforeach
    </ul>
</div>
