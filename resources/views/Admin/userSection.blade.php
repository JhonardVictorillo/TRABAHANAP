<div id="users" class="section" style="display: none;"> <!-- Initially hidden -->
        <h2>All Users</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @if(isset($users) && $users->count() > 0)
                @foreach($users as $user) <!-- Loop through users -->
                    <tr>
                        <td>{{ $user->firstname }} {{ $user->lastname }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                            <button class="verify-btn">Verify</button>
                            <button class="delete-btn">Delete</button>
                        </td>
                    </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="4">No users found.</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
