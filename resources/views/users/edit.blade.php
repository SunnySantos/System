<div>
    <h1>User Management</h1>

    <hr>

    <h1>Edit User</h1>
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- Important for updating --}}

        <div>
            <label for="name">Name: </label>
            <input type="text" id="name" name="name" value="{{ old('name', isset($user->name) ? $user->name : '') }}">
            @error('name')
            <div style="color: red;">{{ $message }}</div>
            @endif
        </div>

        <div>
            <label for="email">Email: </label>
            <input type="email" id="email" name="email" value="{{ old('email', isset($user->email) ? $user->email : '') }}">
            @error('email')
            <div style="color: red;">{{ $message }}</div>
            @endif
        </div>

        <div>
            <label for="password">Password: </label>
            <input type="password" id="password" name="password">
            @error('password')
            <div style="color: red;">{{ $message }}</div>
            @endif
        </div>

        <div>
            <label for="password_confirmation">Confirm Password: </label>
            <input type="password" id="password_confirmation" name="password_confirmation">
            @error('password_confirmation')
            <div style="color: red;">{{ $message }}</div>
            @endif
        </div>

        <button type="submit">Update User</button>
    </form>
</div>