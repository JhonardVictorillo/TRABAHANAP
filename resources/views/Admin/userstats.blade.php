<!-- User Stats Section -->
<div id="userStatsSection" class="details-section" style="display: none;">
    <h2 class="section-title"><span class="material-symbols-outlined align-middle">bar_chart</span> User Violation Stats</h2>
    <div class="userstats-table-container">
         <!-- Search Bar -->
         <form method="GET" action="{{ route('admin.dashboard') }}" class="table-search-form" style="margin-bottom: 1.2rem; display: flex; align-items: center; gap: 0.5rem;">
         <input type="hidden" name="section" value="userstats">
         <div style="position: relative; width: 240px;">
                <span class="material-symbols-outlined"
                    style="color: #2563eb; position: absolute; left: 12px; top: 50%; transform: translateY(-50%); pointer-events: none;">
                    search
                </span>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name..."
                    style="padding: 8px 14px 8px 38px; border: 1px solid #ccc; border-radius: 20px; font-size: 15px; width: 100%; background: #f2f2f2;"
                >
            </div>
            <button type="submit" style="padding: 8px 18px; border-radius: 20px; background: #2563eb; color: #fff; border: none; font-size: 15px; cursor: pointer;">
                Search
            </button>
           
        </form>
        <!-- User Stats Table -->
         <div class="admin-table-container">
            <table class="admin-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>No-Show Count</th>
                    <th>Late Cancel Count</th>
                    <th>Violation Count</th>
                    <th>Last Violation</th>
                </tr>
            </thead>
            <tbody>
            @foreach($userStats as $user)
                <tr>
                    <td>{{ $user->firstname }} {{ $user->lastname }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>{{ $user->no_show_count }}</td>
                    <td>{{ $user->late_cancel_count }}</td>
                    <td>{{ $user->violation_count }}</td>
                    <td>{{ $user->last_violation_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </div>
        <!-- Pagination -->
        <div class="category-pagination-container">
            @if($userStats->previousPageUrl())
            <a href="{{ $userStats->appends(['search' => request('search'), 'activeSection' => 'userstats'])->previousPageUrl() }}" class="category-pagination-btn">
                <i class="fas fa-arrow-left"></i> Previous
            </a>
            @else
            <button class="category-pagination-btn category-btn-disabled" disabled>
                <i class="fas fa-arrow-left"></i> Previous
            </button>
            @endif
            
            @if($userStats->hasMorePages())
            <a href="{{ $userStats->appends(['search' => request('search'), 'activeSection' => 'userstats'])->nextPageUrl() }}" class="category-pagination-btn">
                Next <i class="fas fa-arrow-right"></i>
            </a>
            @else
            <button class="category-pagination-btn category-btn-disabled" disabled>
                Next <i class="fas fa-arrow-right"></i>
            </button>
            @endif
        </div>
    </div>
</div>