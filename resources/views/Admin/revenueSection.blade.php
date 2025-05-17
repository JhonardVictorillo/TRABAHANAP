
<div id="revenueSection" class="section" style="display: none;">
  <div class="section-header">
    <h1>Platform Revenue</h1>
    <p>Commitment fee collection overview</p>
  </div>

  <!-- Revenue Summary Cards -->
  <div class="dashboard-cards">
    <div class="card">
      <div class="icon">
        <span class="material-symbols-outlined">payments</span>
      </div>
      <div>
        <p>Total Revenue</p>
        <h3>Php.{{ number_format($totalRevenue ?? 0, 2) }}</h3>
      </div>
    </div>
    
    <div class="card">
      <div class="icon">
        <span class="material-symbols-outlined">check_circle</span>
      </div>
      <div>
        <p>From Completions</p>
        <h3>Php.{{ number_format($revenueFromCompletions ?? 0, 2) }}</h3>
      </div>
    </div>
    
    <div class="card">
      <div class="icon">
        <span class="material-symbols-outlined">calendar_today</span>
      </div>
      <div>
        <p>This Month</p>
        <h3>Php.{{ number_format($currentMonthRevenue ?? 0, 2) }}</h3>
      </div>
    </div>
  </div>

  <!-- Recent Revenue Transactions -->
  <div class="section-content">
    <div class="table-header">
      <h2>Recent Transactions</h2>
    </div>
    
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Amount</th>
          <th>Source</th>
          <th>Customer</th>
          <th>Freelancer</th>
        </tr>
      </thead>
      <tbody>
        @forelse($revenueTransactions ?? [] as $transaction)
          <tr>
            <td>{{ \Carbon\Carbon::parse($transaction->date)->format('M d, Y') }}</td>
            <td>Php.{{ number_format($transaction->amount, 2) }}</td>
            <td>
              @if($transaction->source == 'commitment_fee')
                <span class="status" style="background-color: {{ getStatusColor('completed') }};">
                  Completion Fee
                </span>
              @elseif($transaction->source == 'late_cancellation_fee')
                <span class="status" style="background-color: {{ getStatusColor('canceled') }};">
                  Cancellation Fee
                </span>
              @else
                <span class="status">
                  {{ ucfirst(str_replace('_', ' ', $transaction->source)) }}
                </span>
              @endif
            </td>
            <td>
              @if($transaction->user)
                {{ $transaction->user->firstname }} {{ $transaction->user->lastname }}
              @else
                N/A
              @endif
            </td>
            <td>
              @if($transaction->appointment && $transaction->appointment->freelancer)
                {{ $transaction->appointment->freelancer->firstname }} {{ $transaction->appointment->freelancer->lastname }}
              @else
                N/A
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" style="text-align: center;">No revenue transactions recorded yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    
    <!-- Pagination -->
    @if(isset($revenueTransactions) && method_exists($revenueTransactions, 'links'))
      <div class="pagination-container">
        {{ $revenueTransactions->links() }}
      </div>
    @endif
  </div>
</div>