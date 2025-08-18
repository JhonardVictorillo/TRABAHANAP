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
         <button class="payment-methods-btn" onclick="openPaymentMethodsModal()">
          <i class="ri-bank-card-line mr-1"></i> Payment Methods
        </button>
      </div>
    </div>
    
    <div class="card">
      <div class="icon">
        <span class="material-symbols-outlined">payments</span>
      </div>
      <div>
        <p>Total Earnings</p>
        <h3>₱{{ number_format($totalEarnings ?? 0, 2) }}</h3>
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
          @php
          // Process payments to combine commitment fees with service payments
          $displayPayments = [];
          $appointmentPayments = [];
          
          // First, group all payments by appointment ID
          foreach($allPayments ?? [] as $payment) {
            if ($payment->appointment_id) {
              if (!isset($appointmentPayments[$payment->appointment_id])) {
                $appointmentPayments[$payment->appointment_id] = [];
              }
              $appointmentPayments[$payment->appointment_id][] = $payment;
            } else {
              // Payments without appointment IDs go directly to display list
              $displayPayments[] = $payment;
            }
          }
          
          // Process each appointment's payments
          foreach($appointmentPayments as $appointmentId => $payments) {
            $servicePayment = null;
            $commitmentFees = [];
            $otherPayments = [];
            
            // Categorize the payments
            foreach($payments as $payment) {
              if ($payment->source === 'service_payment') {
                $servicePayment = $payment;
              } elseif (in_array($payment->source, ['commitment_fee', 'commitment_fee_bonus'])) {
                $commitmentFees[] = $payment;
              } else {
                $otherPayments[] = $payment;
              }
            }
            
            // If we have both service payment and commitment fees, combine them
            if ($servicePayment && count($commitmentFees) > 0) {
              $combinedPayment = clone $servicePayment;
              
              // Add all commitment fee amounts
              foreach($commitmentFees as $fee) {
                $combinedPayment->amount += $fee->amount;
              }
              
              // Mark as combined
              $combinedPayment->is_combined = true;
              $displayPayments[] = $combinedPayment;
            } 
            // If we only have service payment, add it directly
            elseif ($servicePayment) {
              $displayPayments[] = $servicePayment;
            }
            
            // Add all other payment types (except commitment fees)
            foreach($otherPayments as $payment) {
              $displayPayments[] = $payment;
            }
          }
          
          // Sort by date (newest first)
          usort($displayPayments, function($a, $b) {
            return strtotime($b->date) - strtotime($a->date);
          });
        @endphp
        
        @forelse($displayPayments as $payment)
            <tr @if(!empty($payment->is_combined)) class="combined-row" @endif>
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
              <div>
                <strong>{{ \Illuminate\Support\Str::limit($payment->appointment->post->title, 30) }}</strong>
                
                @if($payment->appointment->post->subservices && $payment->appointment->post->subservices->count() > 0)
                  <div class="subservices-list">
                    <small style="display: block; margin-top: 5px; color: #555;">Services included:</small>
                    <ul style="margin: 3px 0 0 15px; padding-left: 0; font-size: 0.85em;">
                      @foreach($payment->appointment->post->subServices as $subservice)
                        <li style="margin-bottom: 2px;">{{ $subservice->sub_service }}</li>
                      @endforeach
                    </ul>
                  </div>
                @else
                  <small style="display: block; margin-top: 5px; color: #777;">No additional services</small>
                @endif
              </div>
            @else
              General Service
            @endif
          </td>
            <td>
              @if($payment->source == 'service_payment' && !empty($payment->is_combined))
                <span class="status" style="background-color: #10B981;">Service Payment</span>
              @elseif($payment->source == 'service_payment')
                <span class="status" style="background-color: #10B981;">Final Payment</span>
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
              <th>Reference</th>
            <th>Payment Method</th>
            <th>Account Details</th>
            <th>Status</th>
              <th>Type</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($withdrawals ?? [] as $withdrawal)
            <tr>
              <td>{{ \Carbon\Carbon::parse($withdrawal->created_at)->format('M d, Y') }}</td>
              <td>₱{{ number_format($withdrawal->amount, 2) }}</td>
               <td>
                {{ $withdrawal->reference }}
                @if($withdrawal->is_automatic ?? false)
                  <span class="badge badge-info">Automatic</span>
                @endif
              </td>
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
              @if($withdrawal->appointment_id)
                <a href="{{ route('freelancer.appointments.view', $withdrawal->appointment_id) }}" class="service-link">
                  View Service
                </a>
              @else
                Manual
              @endif
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
           @if(auth()->user()->stripe_connect_id)
            <option value="stripe" data-badge="Instant">Stripe Connect (Instant)</option>
        @else
            <option value="" disabled data-badge="Unavailable">Stripe Connect (Connect account first)</option>
        @endif
          <option value="bank_transfer">Bank Transfer</option>
          <option value="gcash">GCash</option>
          <option value="paymaya">PayMaya</option>
          <option value="grab_pay">GrabPay</option>
        </select>

         @if(!auth()->user()->stripe_connect_id)
        <div class="mt-2 text-warning">
            <small>
                <i class="fas fa-info-circle"></i> 
                <a href="{{ route('stripe.connect.authorize') }}" target="_blank">Connect your Stripe account</a> 
                to enable instant withdrawals
            </small>
        </div>
    @endif

      </div>

      <!-- Stripe Connect details -->
    <div id="stripe_details" class="payment-details" style="display: none;">
      <div class="payment-form-group">
        <div class="payment-info-card">
          <div class="payment-info-icon">
            <span class="material-symbols-outlined">bolt</span>
          </div>
          <div class="payment-info-content">
            <p><strong>Instant withdrawal via Stripe Connect</strong></p>
            <p>Your funds will be processed immediately to your Stripe account.</p>
            <p><small>Note: This is a sandbox implementation for demonstration purposes.</small></p>
          </div>
        </div>
      </div>
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


<!-- Withdrawal Details Modal -->
<div id="withdrawalDetailsModal" class="payment-modal">
  <div class="payment-modal-content">
    <div class="payment-modal-header">
      <h2>Withdrawal Details</h2>
      <span class="payment-modal-close" onclick="closeWithdrawalDetailsModal()">&times;</span>
    </div>
    
    <div id="withdrawal-details-content">
      <div class="loader">Loading...</div>
    </div>
  </div>
</div>

<div id="paymentMethodsModal" class="payment-modal">
  <div class="payment-modal-content">
    <div class="payment-modal-header">
      <h3>Payment Methods</h3>
      <span class="payment-modal-close" onclick="closePaymentMethodsModal()">&times;</span>
    </div>
    
    <div class="payment-methods-list">
      <div class="payment-method-item auto-transfer-toggle">
  <div class="payment-method-logo">
    <i class="ri-refresh-line"></i>
  </div>
  
  <div class="payment-method-info">
        <h4>Automatic Transfers</h4>
        <div class="toggle-container">
          <form action="{{ route('freelancer.settings.update-auto-transfer') }}" method="POST">
            @csrf
            <label class="toggle-switch">
              <input type="checkbox" 
                    name="auto_transfer_enabled" 
                    id="auto_transfer_enabled"
                    {{ auth()->user()->auto_transfer_enabled ? 'checked' : '' }}
                    onchange="this.form.submit()">
              <span class="toggle-slider"></span>
            </label>
            <div class="toggle-label">
              <strong>{{ auth()->user()->auto_transfer_enabled ? 'Enabled' : 'Disabled' }}</strong>
              <p>When enabled, your earnings will be automatically transferred to your 
              Stripe account as soon as customers pay for completed services.</p>
            </div>
          </form>
        </div>
      </div>
    </div>
      <!-- Stripe Connect -->
      <div class="payment-method-item">
        <div class="payment-method-logo">
          <img src="https://cdn.jsdelivr.net/gh/stripe-samples/stripe-logo@master/blue/stripe_blue.svg" alt="Stripe" height="40">
        </div>
        
        <div class="payment-method-info">
          <h4>Stripe Connect</h4>
          
          @if(auth()->user()->stripe_connect_id)
            <div class="payment-method-status connected">
              <span class="status-badge">Connected</span>
            <p>Account ID: <code>{{ auth()->user()->stripe_connect_id }}</code></p>
              <p>Your account is linked to Stripe for instant withdrawals</p>
            </div>
          @else
            <div class="payment-method-status not-connected">
              <span class="status-badge">Not Connected</span>
              <p>Connect your Stripe account for instant withdrawals</p>
            </div>
          @endif
        </div>
        
        <div class="payment-method-actions">
           @if(auth()->user()->stripe_connect_id)
            <a href="#" onclick="fetchStripeStatus()" class="manage-btn">
              View Account Details
            </a>
          @else
            <a href="{{ route('stripe.connect') }}" class="connect-btn">Connect Test Account</a>
          @endif
        </div>
      </div>
      
      <div class="payment-method-info-box">
        <div class="info-icon">
          <i class="ri-information-line"></i>
        </div>
        <div class="info-text">
          <h5>Why connect Stripe?</h5>
          <ul>
            <li>Receive funds directly to your bank account</li>
            <li>Faster withdrawals (1-2 business days)</li>
            <li>Secure payment processing</li>
            <li>One-time setup, use for all future withdrawals</li>
          </ul>
        </div>
      </div>
    </div>
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
    event.currentTarget.classList.add('active');
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
  

  
  // Withdraw modal functionality
  function openWithdrawModal() {
    document.getElementById('withdrawModal').style.display = 'block';
  }
  
  function closeWithdrawModal() {
    document.getElementById('withdrawModal').style.display = 'none';
    const form = document.getElementById('withdrawForm');
    if (form) {
      form.reset();
    }
    
    // Hide all payment details sections
    document.querySelectorAll('.payment-details').forEach(element => {
      element.style.display = 'none';
    });
  }
  
  // Wait for DOM to be fully loaded before attaching event listeners
  document.addEventListener('DOMContentLoaded', function() {
    // Payment method selection - First check if element exists
    const paymentMethodSelect = document.getElementById('payment_method');
    if (paymentMethodSelect) {
      paymentMethodSelect.addEventListener('change', function() {
        // Hide all payment details first
        document.querySelectorAll('.payment-details').forEach(element => {
          element.style.display = 'none';
        });
        
        // Show the selected payment method details
        const method = this.value;
        if (method) {
          const detailsElement = document.getElementById(`${method}_details`);
          if (detailsElement) {
            detailsElement.style.display = 'block';
          }
        }
      });
    }
    
    // Other bank handling - First check if element exists
    const bankNameSelect = document.getElementById('bank_name');
    if (bankNameSelect) {
      bankNameSelect.addEventListener('change', function() {
        const otherBankContainer = document.getElementById('other_bank_container');
        const otherBankField = document.getElementById('other_bank');
        
        if (otherBankContainer && otherBankField) {
          if (this.value === 'other') {
            otherBankContainer.style.display = 'block';
          } else {
            otherBankContainer.style.display = 'none';
            otherBankField.value = '';
          }
        }
      });
    }
    
    // Form validation - First check if element exists
    const withdrawForm = document.getElementById('withdrawForm');
    if (withdrawForm) {
      withdrawForm.addEventListener('submit', function(e) {
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
        if (paymentMethod === 'stripe') {
          // No additional validation needed for Stripe
        } else if (paymentMethod === 'bank_transfer') {
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
    }
   
  });

  // View withdrawal details
function viewWithdrawalDetails(withdrawalId) {
  // Show modal with loading state
  document.getElementById('withdrawalDetailsModal').style.display = 'block';
  document.getElementById('withdrawal-details-content').innerHTML = '<div class="loader">Loading...</div>';
  
  // Fetch withdrawal details using AJAX
  fetch(`/freelancer/withdrawals/${withdrawalId}/details`, {
    method: 'GET',
    headers: {
      'Accept': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      let status = '';
      if (data.withdrawal.status === 'pending') status = '<span class="status" style="background-color: #F59E0B;">Pending</span>';
      else if (data.withdrawal.status === 'processing') status = '<span class="status" style="background-color: #3B82F6;">Processing</span>';
      else if (data.withdrawal.status === 'completed') status = '<span class="status" style="background-color: #10B981;">Completed</span>';
      else if (data.withdrawal.status === 'rejected') status = '<span class="status" style="background-color: #EF4444;">Rejected</span>';
      
      let accountDetails = '';
      if (data.withdrawal.payment_method === 'stripe') {
        accountDetails = 'Withdrawal processed through Stripe Connect';
        if (data.withdrawal.stripe_payout_id) {
          accountDetails += `<div class="mt-2 text-muted">Stripe Transfer ID: ${data.withdrawal.stripe_payout_id}</div>`;
        }
      } else if (data.withdrawal.payment_method === 'bank_transfer' && data.withdrawal.payment_details) {
        accountDetails = `Bank: ${data.withdrawal.payment_details.bank_name || 'N/A'}<br>`;
        accountDetails += `Account Number: ****${(data.withdrawal.payment_details.account_number || '').slice(-4)}<br>`;
        accountDetails += `Account Name: ${data.withdrawal.payment_details.account_name || 'N/A'}`;
      } else if (data.withdrawal.payment_method === 'gcash' && data.withdrawal.payment_details) {
        accountDetails = `GCash Number: ****${(data.withdrawal.payment_details.gcash_number || '').slice(-4)}<br>`;
        accountDetails += `Account Name: ${data.withdrawal.payment_details.gcash_name || 'N/A'}`;
      } else if (data.withdrawal.payment_method === 'paymaya' && data.withdrawal.payment_details) {
        accountDetails = `PayMaya Number: ****${(data.withdrawal.payment_details.paymaya_number || '').slice(-4)}<br>`;
        accountDetails += `Account Name: ${data.withdrawal.payment_details.paymaya_name || 'N/A'}`;
      } else if (data.withdrawal.payment_method === 'grab_pay' && data.withdrawal.payment_details) {
        accountDetails = `GrabPay Number: ****${(data.withdrawal.payment_details.grab_pay_number || '').slice(-4)}<br>`;
        accountDetails += `Account Name: ${data.withdrawal.payment_details.grab_pay_name || 'N/A'}`;
      } else {
        accountDetails = 'No details available';
      }
      // Add other payment methods...
      
      // Build the HTML for the modal content
      document.getElementById('withdrawal-details-content').innerHTML = `
        <div class="withdrawal-detail-item">
          <span>Withdrawal ID:</span>
          <span>#${data.withdrawal.id}</span>
        </div>
        <div class="withdrawal-detail-item">
          <span>Date Requested:</span>
          <span>${new Date(data.withdrawal.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
        </div>
        <div class="withdrawal-detail-item">
          <span>Amount:</span>
          <span class="amount">₱${parseFloat(data.withdrawal.amount).toFixed(2)}</span>
        </div>
        <div class="withdrawal-detail-item">
          <span>Payment Method:</span>
          <span>${data.withdrawal.payment_method.charAt(0).toUpperCase() + data.withdrawal.payment_method.slice(1)}</span>
        </div>
        <div class="withdrawal-detail-item">
          <span>Status:</span>
          <span>${status}</span>
        </div>
        <div class="withdrawal-detail-item">
          <span>Account Details:</span>
          <div class="account-details">${accountDetails}</div>
        </div>
        ${data.withdrawal.notes ? `
        <div class="withdrawal-detail-item">
          <span>Your Notes:</span>
          <div class="notes">${data.withdrawal.notes}</div>
        </div>
        ` : ''}
        ${data.withdrawal.is_automatic ? `
        <div class="withdrawal-detail-item">
          <span>Type:</span>
          <span><span class="badge badge-info">Automatic</span></span>
        </div>
        ` : ''}

        ${data.withdrawal.appointment_id ? `
        <div class="withdrawal-detail-item">
          <span>Related Service:</span>
          <span><a href="/freelancer/appointments/${data.withdrawal.appointment_id}" class="service-link">View Service Details</a></span>
        </div>
        ` : ''}
        ${data.withdrawal.admin_notes ? `
        <div class="withdrawal-detail-item">
          <span>Admin Notes:</span>
          <div class="admin-notes">${data.withdrawal.admin_notes}</div>
        </div>
        ` : ''}
      `;
    } else {
      document.getElementById('withdrawal-details-content').innerHTML = `
        <div class="error-message">
          <p>Error loading withdrawal details</p>
          <p>${data.message || 'Please try again later'}</p>
        </div>
      `;
    }
  })
  .catch(error => {
    console.error('Error:', error);
    document.getElementById('withdrawal-details-content').innerHTML = `
      <div class="error-message">
        <p>Error loading withdrawal details</p>
        <p>Please try again later</p>
      </div>
    `;
  });
}

function closeWithdrawalDetailsModal() {
  document.getElementById('withdrawalDetailsModal').style.display = 'none';
}

function fetchStripeStatus() {
  fetch('/stripe/status', {
    method: 'GET',
    headers: {
      'Accept': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.connected) {
      // For development testing, show a special testing status
      let statusDisplay = '<span class="status-badge info">Test Mode</span>';
      
      // Use your existing modal or create a simple alert
      alert('Stripe Account Details:\n\n' + 
            'Account ID: ' + data.account_id + '\n' + 
            'Status: Test Mode (Ready for development testing)');
    } else {
      alert('Error loading account details: ' + data.message);
    }
  })
  .catch(error => {
    console.error('Error fetching Stripe status:', error);
    alert('Could not load account information. Please try again later.');
  });
}
  

// Payment methods modal functionality
  function openPaymentMethodsModal() {
    document.getElementById('paymentMethodsModal').style.display = 'block';
  }
  
  function closePaymentMethodsModal() {
    document.getElementById('paymentMethodsModal').style.display = 'none';
  }
  
  // Close modal when clicking outside of it
  window.onclick = function(event) {
    const withdrawModal = document.getElementById('withdrawModal');
    const paymentMethodsModal = document.getElementById('paymentMethodsModal');
    
    if (event.target == withdrawModal) {
      withdrawModal.style.display = "none";
    }
    
    if (event.target == paymentMethodsModal) {
      paymentMethodsModal.style.display = "none";
    }
  }
  
</script>