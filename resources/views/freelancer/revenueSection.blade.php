<div id="revenueSection" class="section" style="display: none;">
  <div class="section-header">
    <h1>Cancellation & No-Show Revenue</h1>
    <p>Fee collection from late cancellations and no-show appointments</p>
  </div>

  <!-- Revenue Summary Cards -->
  <div class="dashboard-cards">
    <div class="card">
      <div class="icon">
        <span class="material-symbols-outlined">event_busy</span>
      </div>
      <div>
        <p>Late Cancellation Revenue</p>
        <h3>Php.{{ number_format($lateCancellationRevenue ?? 0, 2) }}</h3>
      </div>
    </div>
    
    <div class="card">
      <div class="icon">
        <span class="material-symbols-outlined">person_off</span>
      </div>
      <div>
        <p>No-Show Revenue</p>
        <h3>Php.{{ number_format($noShowRevenue ?? 0, 2) }}</h3>
      </div>
    </div>
    
    <div class="card">
      <div class="icon">
        <span class="material-symbols-outlined">calendar_today</span>
      </div>
      <div>
        <p>This Month</p>
        <h3>Php.{{ number_format($currentMonthCancellationRevenue ?? 0, 2) }}</h3>
      </div>
    </div>
  </div>

  <!-- Late Cancellation and No-Show Transactions -->
  <div class="section-content">
    <div class="table-header">
      <h2>Late Cancellation & No-Show Transactions</h2>
    </div>
    
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Amount</th>
          <th>Type</th>
          <th>Customer</th>
          <th>Freelancer</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($revenueTransactions ?? [] as $transaction)
          <tr>
            <td>{{ \Carbon\Carbon::parse($transaction->date)->format('M d, Y') }}</td>
            <td>Php.{{ number_format($transaction->amount, 2) }}</td>
            <td>
              @if($transaction->source == 'late_cancellation_fee')
                <span class="status" style="background-color: {{ getStatusColor('canceled') }};">
                  Late Cancellation
                </span>
              @elseif($transaction->source == 'no_show_fee')
                <span class="status" style="background-color: {{ getStatusColor('no_show_customer') }};">
                  No-Show
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
            <td>
              @if($transaction->appointment && $transaction->appointment->fee_status == 'forfeited')
                <span class="status" style="background-color: #4F46E5;">
                  Credited to Freelancer
                </span>
              @else
                <span class="status" style="background-color: #10B981;">
                  Platform Revenue
                </span>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" style="text-align: center;">No late cancellation or no-show transactions recorded yet.</td>
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
  
  <!-- Fee Distribution Stats -->
  <div class="section-content" style="margin-top: 20px;">
    <div class="table-header">
      <h2>Fee Distribution Summary</h2>
    </div>
    
    <div class="fee-distribution">
      <div class="fee-stat">
        <h3>Fee Distribution</h3>
        <div class="pie-chart-container">
          <canvas id="feeDistributionChart"></canvas>
        </div>
      </div>
      
      <div class="fee-details">
        <div class="fee-item">
          <span class="fee-label">Platform Revenue:</span>
          <span class="fee-value">Php.{{ number_format(($revenueTransactions->where('appointment.fee_status', '!=', 'forfeited')->sum('amount')) ?? 0, 2) }}</span>
        </div>
        <div class="fee-item">
          <span class="fee-label">Credited to Freelancers:</span>
          <span class="fee-value">Php.{{ number_format(($revenueTransactions->where('appointment.fee_status', 'forfeited')->sum('amount')) ?? 0, 2) }}</span>
        </div>
        <div class="fee-item">
          <span class="fee-label">Total Fees Collected:</span>
          <span class="fee-value">Php.{{ number_format($cancellationAndNoShowRevenue ?? 0, 2) }}</span>
        </div>
      </div>
    </div>
  </div>
</div>