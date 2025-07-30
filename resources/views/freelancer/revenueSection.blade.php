<div id="revenueSection" class="section" style="display: none;">
  <div class="section-header">
    <h1>Freelancer Wallet & Earnings</h1>
    <p>Track your income, manage your wallet, and request withdrawals</p>
  </div>

  <!-- Earnings Summary Cards -->
  <div class="dashboard-cards">
    <div class="card primary-card">
      <div class="icon">
        <span class="material-symbols-outlined">account_balance_wallet</span>
      </div>
      <div>
        <p>Available Balance</p>
        <h3>₱{{ number_format($availableBalance ?? 0, 2) }}</h3>
        <button class="withdraw-btn" onclick="openWithdrawModal()">Withdraw Funds</button>
      </div>
    </div>
    
    <div class="card">
      <div class="icon">
        <span class="material-symbols-outlined">payments</span>
      </div>
      <div>
        <p>Total Earnings</p>
        <h3>₱{{ number_format(($serviceEarnings + $cancellationRevenue) ?? 0, 2) }}</h3>
      </div>
    </div>
    
    <div class="card">
      <div class="icon">
        <span class="material-symbols-outlined">calendar_today</span>
      </div>
      <div>
        <p>This Month</p>
        <h3>₱{{ number_format($currentMonthEarnings ?? 0, 2) }}</h3>
      </div>
    </div>
    
    <div class="card">
      <div class="icon">
        <span class="material-symbols-outlined">receipt_long</span>
      </div>
      <div>
        <p>Completed Services</p>
        <h3>{{ $completedServices ?? 0 }}</h3>
      </div>
    </div>
  </div>

  <!-- Tabbed navigation for earnings types -->
  <div class="revenue-tabs">
    <button class="tab-btn active" onclick="showTab('all-payments')">All Payments</button>
    <button class="tab-btn" onclick="showTab('withdrawals')">Withdrawal History</button>
  </div>

  <!-- All Payments Tab -->
  <div id="all-payments" class="tab-content active">
    <div class="section-content">
      <div class="table-header">
        <h2>Payment History</h2>
      </div>
      
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Amount</th>
            <th>Customer</th>
            <th>Service</th>
            <th>Type</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($allPayments ?? [] as $payment)
            <tr>
              <td>{{ \Carbon\Carbon::parse($payment->date)->format('M d, Y') }}</td>
              <td>₱{{ number_format($payment->amount, 2) }}</td>
              <td>
                @if($payment->appointment && $payment->appointment->customer)
                  {{ $payment->appointment->customer->firstname }} {{ $payment->appointment->customer->lastname }}
                @else
                  N/A
                @endif
              </td>
              <td>
                @if($payment->appointment && $payment->appointment->post)
                  {{ \Illuminate\Support\Str::limit($payment->appointment->post->title, 30) }}
                @else
                  General Service
                @endif
              </td>
              <td>
              @if($payment->source == 'service_payment')
              <span class="status" style="background-color: #10B981;">Final Payment</span>
            @elseif($payment->source == 'commitment_fee')
              <span class="status" style="background-color: #3B82F6;">Commitment Fee</span>
            @elseif($payment->source == 'late_cancellation_fee')
              <span class="status" style="background-color: #F59E0B;">Cancellation Fee</span>
            @elseif($payment->source == 'no_show_fee')
              <span class="status" style="background-color: #EF4444;">No-Show Fee</span>
            @else
              <span class="status" style="background-color: #6B7280;">Other</span>
           @endif
              </td>
              <td>
                <span class="status" style="background-color: #10B981;">
                  Paid
                </span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align: center;">No payments recorded yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
      
      <!-- Pagination -->
      @if(isset($allPayments) && method_exists($allPayments, 'links'))
        <div class="pagination-container">
          {{ $allPayments->links() }}
        </div>
      @endif
    </div>
  </div>
  
  <!-- Withdrawals Tab -->
  <div id="withdrawals" class="tab-content">
    <div class="section-content">
      <div class="table-header">
        <h2>Withdrawal History</h2>
      </div>
      
      <table>
        <thead>
          <tr>
            <th>Date Requested</th>
            <th>Amount</th>
            <th>Payment Method</th>
            <th>Account Details</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($withdrawals ?? [] as $withdrawal)
            <tr>
              <td>{{ \Carbon\Carbon::parse($withdrawal->created_at)->format('M d, Y') }}</td>
              <td>₱{{ number_format($withdrawal->amount, 2) }}</td>
              <td>{{ ucfirst($withdrawal->payment_method) }}</td>
              <td>
                @if($withdrawal->payment_details)
                  @if($withdrawal->payment_method == 'bank_transfer')
                    {{ strtoupper($withdrawal->payment_details['bank_name'] ?? '') }} ****{{ substr($withdrawal->payment_details['account_number'] ?? '', -4) }}
                  @elseif($withdrawal->payment_method == 'gcash')
                    GCash ****{{ substr($withdrawal->payment_details['gcash_number'] ?? '', -4) }}
                  @elseif($withdrawal->payment_method == 'paymaya')
                    PayMaya ****{{ substr($withdrawal->payment_details['paymaya_number'] ?? '', -4) }}
                  @elseif($withdrawal->payment_method == 'grab_pay')
                    GrabPay ****{{ substr($withdrawal->payment_details['grab_pay_number'] ?? '', -4) }}
                  @endif
                @else
                  N/A
                @endif
              </td>
              <td>
                <span class="status" style="background-color: 
                  @if($withdrawal->status == 'pending')
                    #F59E0B
                  @elseif($withdrawal->status == 'processing')
                    #3B82F6
                  @elseif($withdrawal->status == 'completed')
                    #10B981
                  @elseif($withdrawal->status == 'rejected')
                    #EF4444
                  @else
                    #6B7280
                  @endif
                ">
                  {{ ucfirst($withdrawal->status) }}
                </span>
              </td>
              <td>
                @if($withdrawal->status == 'pending')
                  <button class="cancel-btn" onclick="cancelWithdrawal({{ $withdrawal->id }})">Cancel</button>
                @else
                  <button class="details-btn" onclick="viewWithdrawalDetails({{ $withdrawal->id }})">Details</button>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align: center;">No withdrawal requests yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
      
      <!-- Pagination -->
      @if(isset($withdrawals) && method_exists($withdrawals, 'links'))
        <div class="pagination-container">
          {{ $withdrawals->links() }}
        </div>
      @endif
    </div>
  </div>
  
  <!-- Earnings Analytics Section -->
  <div class="section-content">
    <div class="table-header">
      <h2>Earnings Analytics</h2>
    </div>
    
    <div class="analytics-container">
      <div class="chart-container">
        <h3>Monthly Earnings</h3>
        <canvas id="monthlyEarningsChart"></canvas>
      </div>
      
      <div class="chart-container">
        <h3>Earnings Breakdown</h3>
        <canvas id="earningsBreakdownChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Withdraw Funds Modal (for completed service payments) -->
<div id="withdrawModal" class="payment-modal">
  <div class="payment-modal-content">
    <div class="payment-modal-header">
      <h2>Withdraw Funds</h2>
      <span class="payment-modal-close" onclick="closeWithdrawModal()">&times;</span>
    </div>
    
    <form id="withdrawForm" method="POST" action="{{ route('freelancer.withdraw') }}">
      @csrf
      
      <div class="payment-form-group">
        <label for="amount">Amount to Withdraw (₱)</label>
        <input type="number" id="amount" name="amount" step="0.01" min="100" max="{{ $availableBalance ?? 0 }}" required>
        <small>Available Balance: ₱{{ number_format($availableBalance ?? 0, 2) }}</small>
        <small>Minimum withdrawal: ₱100.00</small>
      </div>
      
      <div class="payment-form-group">
        <label for="payment_method">Payment Method</label>
        <select id="payment_method" name="payment_method" required>
          <option value="">Select Payment Method</option>
          <option value="bank_transfer">Bank Transfer</option>
          <option value="gcash">GCash</option>
          <option value="paymaya">PayMaya</option>
          <option value="grab_pay">GrabPay</option>
        </select>
      </div>
      
      <!-- Bank transfer details -->
      <div id="bank_transfer_details" class="payment-details" style="display: none;">
        <div class="payment-form-group">
          <label for="bank_name">Bank Name</label>
          <select id="bank_name" name="bank_name">
            <option value="">Select Bank</option>
            <option value="bdo">BDO</option>
            <option value="bpi">BPI</option>
            <option value="metrobank">Metrobank</option>
            <option value="landbank">Landbank</option>
            <option value="pnb">PNB</option>
            <option value="security_bank">Security Bank</option>
            <option value="unionbank">Unionbank</option>
            <option value="other">Other Bank</option>
          </select>
        </div>
        
        <div id="other_bank_container" style="display: none;">
          <div class="payment-form-group">
            <label for="other_bank">Specify Bank Name</label>
            <input type="text" id="other_bank" name="other_bank">
          </div>
        </div>
        
        <div class="payment-form-group">
          <label for="account_number">Account Number</label>
          <input type="text" id="account_number" name="account_number" placeholder="Enter your account number">
        </div>
        
        <div class="payment-form-group">
          <label for="account_name">Account Name</label>
          <input type="text" id="account_name" name="account_name" placeholder="Name on the bank account">
        </div>
      </div>
      
      <!-- GCash details -->
      <div id="gcash_details" class="payment-details" style="display: none;">
        <div class="payment-form-group">
          <label for="gcash_number">GCash Number</label>
          <input type="text" id="gcash_number" name="gcash_number" placeholder="09XXXXXXXXX">
          <small>Format: 09XXXXXXXXX or +63XXXXXXXXXX</small>
        </div>
        
        <div class="payment-form-group">
          <label for="gcash_name">Name on GCash Account</label>
          <input type="text" id="gcash_name" name="gcash_name" placeholder="Full name as shown in GCash">
        </div>
      </div>
      
      <!-- PayMaya details -->
      <div id="paymaya_details" class="payment-details" style="display: none;">
        <div class="payment-form-group">
          <label for="paymaya_number">PayMaya Number</label>
          <input type="text" id="paymaya_number" name="paymaya_number" placeholder="09XXXXXXXXX">
          <small>Format: 09XXXXXXXXX or +63XXXXXXXXXX</small>
        </div>
        
        <div class="payment-form-group">
          <label for="paymaya_name">Name on PayMaya Account</label>
          <input type="text" id="paymaya_name" name="paymaya_name" placeholder="Full name as shown in PayMaya">
        </div>
      </div>
      
      <!-- GrabPay details -->
      <div id="grab_pay_details" class="payment-details" style="display: none;">
        <div class="payment-form-group">
          <label for="grab_pay_number">GrabPay Number</label>
          <input type="text" id="grab_pay_number" name="grab_pay_number" placeholder="09XXXXXXXXX">
          <small>Format: 09XXXXXXXXX or +63XXXXXXXXXX</small>
        </div>
        
        <div class="payment-form-group">
          <label for="grab_pay_name">Name on GrabPay Account</label>
          <input type="text" id="grab_pay_name" name="grab_pay_name" placeholder="Full name as shown in GrabPay">
        </div>
      </div>
      
      <!-- Withdrawal notes -->
      <div class="payment-form-group">
        <label for="notes">Additional Notes (Optional)</label>
        <textarea id="notes" name="notes" rows="2" placeholder="Any specific instructions"></textarea>
      </div>
      
      <div class="payment-info-section">
        <div class="payment-info-card">
          <div class="payment-info-icon">
            <span class="material-symbols-outlined">info</span>
          </div>
          <div class="payment-info-content">
            <p>Withdrawals are typically processed within 3-5 business days. A small processing fee may apply depending on your selected payment method.</p>
          </div>
        </div>
      </div>
      
      <div class="payment-form-group">
        <button type="submit" class="payment-submit-btn">Request Withdrawal</button>
      </div>
    </form>
  </div>
</div>

<!-- Add JavaScript for tabs and withdrawal functionality -->
<script>
  // Tab functionality
  function showTab(tabId) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
      tab.classList.remove('active');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-btn').forEach(button => {
      button.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabId).classList.add('active');
    
    // Add active class to clicked button
    event.target.classList.add('active');
  }
  
  
  
  // Cancel withdrawal
  function cancelWithdrawal(withdrawalId) {
    if (confirm('Are you sure you want to cancel this withdrawal request?')) {
      fetch(`/freelancer/withdrawals/${withdrawalId}/cancel`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Withdrawal request cancelled successfully');
          location.reload();
        } else {
          alert(data.message || 'Error cancelling withdrawal request');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while cancelling your withdrawal request');
      });
    }
  }
  
  // View withdrawal details
  function viewWithdrawalDetails(withdrawalId) {
    window.location.href = `/freelancer/withdrawals/${withdrawalId}`;
  }
  
  // Initialize Charts when the page loads
  document.addEventListener('DOMContentLoaded', function() {
    // Monthly Earnings Chart
    const monthlyEarningsCtx = document.getElementById('monthlyEarningsChart').getContext('2d');
    const monthlyEarningsChart = new Chart(monthlyEarningsCtx, {
      type: 'line',
      data: {
        labels: {!! json_encode($monthlyLabels ?? []) !!},
        datasets: [{
          label: 'Monthly Earnings',
          data: {!! json_encode($monthlyEarnings ?? []) !!},
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 2,
          tension: 0.1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return '₱' + value;
              }
            }
          }
        },
        plugins: {
          tooltip: {
            callbacks: {
              label: function(context) {
                return '₱' + context.parsed.y;
              }
            }
          }
        }
      }
    });
    
    // Revenue Breakdown Chart
    const revenueBreakdownCtx = document.getElementById('revenueBreakdownChart').getContext('2d');
    const revenueBreakdownChart = new Chart(revenueBreakdownCtx, {
      type: 'pie',
      data: {
        labels: ['Service Payments', 'Cancellation Fees', 'No-Show Fees'],
        datasets: [{
          data: [
            {{ $serviceEarnings ?? 0 }},
            {{ $lateCancellationRevenue ?? 0 }},
            {{ $noShowRevenue ?? 0 }}
          ],
          backgroundColor: [
            'rgba(54, 162, 235, 0.8)',
            'rgba(255, 159, 64, 0.8)',
            'rgba(255, 99, 132, 0.8)'
          ]
        }]
      },
      options: {
        plugins: {
          tooltip: {
            callbacks: {
              label: function(context) {
                const label = context.label || '';
                const value = context.parsed || 0;
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                return `${label}: ₱${value} (${percentage}%)`;
              }
            }
          }
        }
      }
    });
  });

  // Payment method selection
  document.getElementById('payment_method')?.addEventListener('change', function() {
    // Hide all payment details first
    document.querySelectorAll('.payment-details').forEach(element => {
      element.style.display = 'none';
    });
    
    // Show the selected payment method details
    const method = this.value;
    if (method) {
      document.getElementById(`${method}_details`).style.display = 'block';
    }
  });
  
  // Other bank handling
  document.getElementById('bank_name')?.addEventListener('change', function() {
    if (this.value === 'other') {
      document.getElementById('other_bank_container').style.display = 'block';
    } else {
      document.getElementById('other_bank_container').style.display = 'none';
      document.getElementById('other_bank').value = '';
    }
  });
  
  // Withdraw modal functionality
  function openWithdrawModal() {
    document.getElementById('withdrawModal').style.display = 'block';
  }
  
  function closeWithdrawModal() {
    document.getElementById('withdrawModal').style.display = 'none';
    document.getElementById('withdrawForm').reset();
    // Hide all payment details sections
    document.querySelectorAll('.payment-details').forEach(element => {
      element.style.display = 'none';
    });
  }
  
  // Form validation
  document.getElementById('withdrawForm')?.addEventListener('submit', function(e) {
    const amount = document.getElementById('amount').value;
    const paymentMethod = document.getElementById('payment_method').value;
    const maxAmount = {{ $availableBalance ?? 0 }};
    
    if (parseFloat(amount) <= 0) {
      e.preventDefault();
      alert('Please enter a valid withdrawal amount');
      return false;
    }
    
    if (parseFloat(amount) > parseFloat(maxAmount)) {
      e.preventDefault();
      alert('Withdrawal amount cannot exceed your available balance');
      return false;
    }
    
    // Validate specific payment method fields
    if (paymentMethod === 'bank_transfer') {
      const bankName = document.getElementById('bank_name').value;
      const accountNumber = document.getElementById('account_number').value;
      const accountName = document.getElementById('account_name').value;
      
      if (!bankName || !accountNumber || !accountName) {
        e.preventDefault();
        alert('Please complete all bank details');
        return false;
      }
      
      if (bankName === 'other' && !document.getElementById('other_bank').value) {
        e.preventDefault();
        alert('Please specify the bank name');
        return false;
      }
    } 
    else if (paymentMethod === 'gcash') {
      const gcashNumber = document.getElementById('gcash_number').value;
      const gcashName = document.getElementById('gcash_name').value;
      
      if (!gcashNumber || !gcashName) {
        e.preventDefault();
        alert('Please complete all GCash details');
        return false;
      }
    }
    else if (paymentMethod === 'paymaya') {
      const paymayaNumber = document.getElementById('paymaya_number').value;
      const paymayaName = document.getElementById('paymaya_name').value;
      
      if (!paymayaNumber || !paymayaName) {
        e.preventDefault();
        alert('Please complete all PayMaya details');
        return false;
      }
    }
    else if (paymentMethod === 'grab_pay') {
      const grabPayNumber = document.getElementById('grab_pay_number').value;
      const grabPayName = document.getElementById('grab_pay_name').value;
      
      if (!grabPayNumber || !grabPayName) {
        e.preventDefault();
        alert('Please complete all GrabPay details');
        return false;
      }
    }
    
    // If everything is valid, show loading state
    const submitButton = this.querySelector('.payment-submit-btn');
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Processing...';
  });
</script>