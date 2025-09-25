<div>
    <h1>User Info</h1>
    <p>Name: {{ $user->name }}</p>
    <p>Email: {{ $user->email }}</p>

    <hr>

    <a href="{{ route('users.edit', $user) }}">Update</a>
    <a>Delete</a>
</div>