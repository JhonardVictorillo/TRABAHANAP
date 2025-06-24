<!-- Violations Section -->
<div id="violationsSection" class="details-section" style="display: none;">
    <h2 class="section-title"><span class="material-symbols-outlined align-middle">report_problem</span> Violations & No-Shows</h2>
    <div class="violation-table-container">
         <!-- Search Bar -->
         <form method="GET" action="{{ route('admin.dashboard') }}" class="table-search-form" style="margin-bottom: 1.2rem; display: flex; align-items: center; gap: 0.5rem;">
         <input type="hidden" name="section" value="violations">
         <div style="position: relative; width: 240px;">
                <span class="material-symbols-outlined"
                    style="color: #999; position: absolute; left: 12px; top: 50%; transform: translateY(-50%); pointer-events: none;">
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
            <button type="submit" style="padding: 8px 18px; border-radius: 20px; background: #00b86e; color: #fff; border: none; font-size: 15px; cursor: pointer;">
                Search
            </button>
          
        </form>
        <!-- Violations Table -->
       <div class="admin-table-container">
            <table class="admin-table">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Who</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Violation Type</th>
                </tr>
            </thead>
            <tbody>
            @foreach($violations as $appt)
                @if(str_contains($appt->status, 'no_show'))
                <tr>
                    <td>{{ $appt->id }}</td>
                    <td>
                        @if($appt->status == 'no_show_freelancer')
                            {{ $appt->freelancer->firstname ?? '' }} {{ $appt->freelancer->lastname ?? '' }}
                        @else
                            {{ $appt->customer->firstname ?? '' }} {{ $appt->customer->lastname ?? '' }}
                        @endif
                    </td>
                    <td>
                        @if($appt->status == 'no_show_freelancer')
                            Freelancer
                        @else
                            Customer
                        @endif
                    </td>
                    <td>
                        <span class="status-badge" style="background:{{ getStatusColor($appt->status) }}">
                            {{ ucfirst(str_replace('_', ' ', $appt->status)) }}
                        </span>
                    </td>
                    <td>{{ $appt->date }} {{ $appt->time }}</td>
                    <td>No-Show</td>
                </tr>
                @endif
            @endforeach
            </tbody>
        </table>
        </div>
        <!-- Pagination -->
        <div class="pagination-container" style="margin-top: 1.5rem;">
        {{ $violations->appends(['search' => request('search'), 'section' => 'violations'])->links() }}
        </div>
    </div>
</div>