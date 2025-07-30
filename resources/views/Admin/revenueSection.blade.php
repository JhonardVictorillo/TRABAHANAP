<div id="revenueSection" class="section" style="display: none;">
  <div class="section-header">
    <h1>Platform Revenue</h1>
    <p>Overall platform earning overview</p>
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
        <p>From Service Fees</p>
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
    
    <!-- New Card to Open Withdrawal Modal -->
   <div class="card action-card">
  <button type="button" onclick="openAdminWithdrawalModal()" class="admin-withdrawal-btn" style="background: white; width: 100%; border: none; display: flex; align-items: center; text-align: left; padding: 0;">
    <div class="icon">
      <span class="material-symbols-outlined">account_balance</span>
    </div>
    <div>
      <p>Withdraw Revenue</p>
      <span>To bank account</span>
    </div>
  </button>
</div>
  </div>

  <!-- Tab Navigation for Revenue and Withdrawals -->
  <div class="admin-revenue-tabs">
    <button class="admin-tab-btn active" onclick="showAdminTab('admin-revenue-transactions')">Revenue Transactions</button>
    <button class="admin-tab-btn" onclick="showAdminTab('admin-withdrawal-history')">Withdrawal History</button>
  </div>

  <!-- Revenue Transactions Tab -->
  <div id="admin-revenue-transactions" class="admin-tab-content active">
    <div class="section-content">
      <div class="table-header">
        <h2>Revenue Transactions</h2>
        
        <!-- Search Bar for Revenue Transactions -->
        <form method="GET" action="{{ route('admin.dashboard') }}" class="table-search-form">
          <input type="hidden" name="section" value="revenue">
          <input type="hidden" name="tab" value="revenue">
          <div class="search-container">
            <span class="material-symbols-outlined">search</span>
            <input
              type="text"
              name="revenue_search"
              value="{{ request('revenue_search') }}"
              placeholder="Search transactions..."
              class="search-input"
            >
            <button type="submit" class="search-button">Search</button>
          </div>
        </form>
      </div>
      
      <div class="admin-table-container">
        <table class="admin-table">
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
                  @if($transaction->source == 'commitment_fee_commission')
                    <span class="status" style="background-color: {{ getStatusColor('completed') }};">
                      Commitment Fee
                    </span>
                  @elseif($transaction->source == 'final_payment_commission')
                    <span class="status" style="background-color: #3B82F6;">
                      Service Fee
                    </span>
                  @elseif($transaction->source == 'late_cancellation_commission')
                    <span class="status" style="background-color: {{ getStatusColor('canceled') }};">
                      Cancellation Fee
                    </span>
                  @elseif($transaction->source == 'no_show_commission')
                    <span class="status" style="background-color: #EF4444;">
                      No-Show Fee
                    </span>
                  @else
                    <span class="status">
                      {{ ucfirst(str_replace('_', ' ', $transaction->source)) }}
                    </span>
                  @endif
                </td>
                <td>
                  @if($transaction->appointment && $transaction->appointment->customer)
                    {{ $transaction->appointment->customer->firstname }} {{ $transaction->appointment->customer->lastname }}
                  @else
                    N/A
                  @endif
                </td>
                <td>
                  @if($transaction->user)
                    {{ $transaction->user->firstname }} {{ $transaction->user->lastname }}
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
      </div>
      
      <!-- Pagination for Revenue -->
      @if(isset($revenueTransactions) && method_exists($revenueTransactions, 'links'))
          <div class="category-pagination-container">
            @if($revenueTransactions->previousPageUrl())
              <a href="{{ $revenueTransactions->appends(['section' => 'revenue', 'tab' => 'revenue', 'revenue_search' => request('revenue_search')])->previousPageUrl() }}" class="category-pagination-btn">
                <i class="fas fa-arrow-left"></i> Previous
              </a>
            @else
              <button class="category-pagination-btn category-btn-disabled" disabled>
                <i class="fas fa-arrow-left"></i> Previous
              </button>
            @endif
            
            @if($revenueTransactions->hasMorePages())
              <a href="{{ $revenueTransactions->appends(['section' => 'revenue', 'tab' => 'revenue', 'revenue_search' => request('revenue_search')])->nextPageUrl() }}" class="category-pagination-btn">
                Next <i class="fas fa-arrow-right"></i>
              </a>
            @else
              <button class="category-pagination-btn category-btn-disabled" disabled>
                Next <i class="fas fa-arrow-right"></i>
              </button>
            @endif
          </div>
        @endif
    </div>
  </div>

  <!-- Admin Withdrawal History Tab -->
  <div id="admin-withdrawal-history" class="admin-tab-content">
    <div class="section-content">
      <div class="table-header">
        <h2>Platform Withdrawal History</h2>
        
        <!-- Search Bar for Withdrawals -->
        <form method="GET" action="{{ route('admin.dashboard') }}" class="table-search-form">
          <input type="hidden" name="section" value="revenue">
          <input type="hidden" name="tab" value="withdrawals">
          <div class="search-container">
            <span class="material-symbols-outlined">search</span>
            <input
              type="text"
              name="withdrawal_search"
              value="{{ request('withdrawal_search') }}"
              placeholder="Search withdrawals..."
              class="search-input"
            >
            <button type="submit" class="search-button">Search</button>
          </div>
        </form>
      </div>
      
      <div class="admin-table-container">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Amount</th>
              <th>Bank/Account</th>
              <th>Reference</th>
              <th>Processed By</th>
              <th>Notes</th>
            </tr>
          </thead>
          <tbody>
            @forelse($platformWithdrawals ?? [] as $withdrawal)
              <tr>
                <td>{{ $withdrawal->created_at->format('M d, Y') }}</td>
                <td>Php.{{ number_format($withdrawal->amount, 2) }}</td>
                <td>{{ $withdrawal->bank_name }}</td>
                <td>{{ $withdrawal->reference_number ?? 'N/A' }}</td>
                <td>{{ $withdrawal->admin->firstname ?? 'N/A' }} {{ $withdrawal->admin->lastname ?? '' }}</td>
                <td>{{ $withdrawal->notes ?? 'N/A' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="6" style="text-align: center;">No platform withdrawals recorded yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      
      <!-- Pagination for Withdrawals -->
       @if(isset($platformWithdrawals) && method_exists($platformWithdrawals, 'links'))
          <div class="category-pagination-container">
            @if($platformWithdrawals->previousPageUrl())
              <a href="{{ $platformWithdrawals->appends(['section' => 'revenue', 'tab' => 'withdrawals', 'withdrawal_search' => request('withdrawal_search')])->previousPageUrl() }}" class="category-pagination-btn">
                <i class="fas fa-arrow-left"></i> Previous
              </a>
            @else
              <button class="category-pagination-btn category-btn-disabled" disabled>
                <i class="fas fa-arrow-left"></i> Previous
              </button>
            @endif
            
            @if($platformWithdrawals->hasMorePages())
              <a href="{{ $platformWithdrawals->appends(['section' => 'revenue', 'tab' => 'withdrawals', 'withdrawal_search' => request('withdrawal_search')])->nextPageUrl() }}" class="category-pagination-btn">
                Next <i class="fas fa-arrow-right"></i>
              </a>
            @else
              <button class="category-pagination-btn category-btn-disabled" disabled>
                Next <i class="fas fa-arrow-right"></i>
              </button>
            @endif
          </div>
        @endif
    </div>
  </div>
</div>

<!-- Admin Withdrawal Modal -->
<div id="adminWithdrawalModal" class="admin-withdrawal-modal">
  <div class="admin-withdrawal-modal-content">
    <span class="admin-withdrawal-modal-close" onclick="closeAdminWithdrawalModal()">&times;</span>
    <h2>Withdraw Platform Revenue</h2>
    
    <form id="adminWithdrawalForm" method="POST" action=" ">
      @csrf
      <div class="admin-withdrawal-form-group">
        <label for="withdrawal_amount">Amount (Php):</label>
        <input 
          type="number" 
          id="withdrawal_amount" 
          name="amount" 
          min="1" 
          step="0.01" 
          required 
          class="admin-withdrawal-form-control"
          max="{{ $availableRevenue ?? 0 }}"
        >
        <small class="admin-withdrawal-balance-info">Available revenue: <span id="available-revenue">Php.{{ number_format($availableRevenue ?? 0, 2) }}</span></small>
      </div>
      
      <div class="admin-withdrawal-form-group">
        <label for="bank_name">Bank/Account Name:</label>
        <input 
          type="text" 
          id="bank_name" 
          name="bank_name" 
          required 
          class="admin-withdrawal-form-control"
          placeholder="e.g., BDO, BPI, GCash"
        >
      </div>
      
      <div class="admin-withdrawal-form-group">
        <label for="account_number">Account Number:</label>
        <input 
          type="text" 
          id="account_number" 
          name="account_number" 
          required 
          class="admin-withdrawal-form-control"
          placeholder="e.g., 1234-5678-9012-3456"
        >
      </div>
      
      <div class="admin-withdrawal-form-group">
        <label for="reference_number">Reference Number (Optional):</label>
        <input 
          type="text" 
          id="reference_number" 
          name="reference_number" 
          class="admin-withdrawal-form-control"
          placeholder="Transaction reference if available"
        >
      </div>
      
      <div class="admin-withdrawal-form-group">
        <label for="withdrawal_notes">Notes:</label>
        <textarea 
          id="withdrawal_notes" 
          name="notes" 
          class="admin-withdrawal-form-control" 
          rows="2"
          placeholder="Optional notes about this transaction"
        ></textarea>
      </div>
      
      <div class="admin-withdrawal-form-actions">
        <button type="button" onclick="closeAdminWithdrawalModal()" class="admin-withdrawal-btn-secondary">Cancel</button>
        <button type="submit" class="admin-withdrawal-btn-primary">Withdraw Funds</button>
      </div>
    </form>
  </div>
</div>


<script>
// Tab Functions
function showAdminTab(tabId) {
  // Hide all tabs
  document.querySelectorAll('.admin-tab-content').forEach(tab => {
    tab.classList.remove('active');
  });
  
  // Remove active class from all tab buttons
  document.querySelectorAll('.admin-tab-btn').forEach(btn => {
    btn.classList.remove('active');
  });
  
  // Show selected tab
  document.getElementById(tabId).classList.add('active');
  
  // Activate the clicked button
  event.currentTarget.classList.add('active');
  
  // Update URL with active tab
  const urlParams = new URLSearchParams(window.location.search);
  urlParams.set('tab', tabId === 'admin-revenue-transactions' ? 'revenue' : 'withdrawals');
  history.replaceState(null, '', `${window.location.pathname}?${urlParams.toString()}`);
}

// Admin Withdrawal Modal Functions
function openAdminWithdrawalModal() {
  document.getElementById('adminWithdrawalModal').style.display = 'block';
}

function closeAdminWithdrawalModal() {
  document.getElementById('adminWithdrawalModal').style.display = 'none';
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  const adminWithdrawalModal = document.getElementById('adminWithdrawalModal');
  
  if (event.target == adminWithdrawalModal) {
    adminWithdrawalModal.style.display = 'none';
  }
}

// Form validation for admin withdrawal
document.addEventListener('DOMContentLoaded', function() {
  // Set initial tab based on URL parameter, but only if revenue section is active
  const urlParams = new URLSearchParams(window.location.search);
  const activeTab = urlParams.get('tab');
  const activeSection = urlParams.get('section') || localStorage.getItem('activeSection');
  
  // Only try to set the tab if we're on the revenue section
  if (activeSection === 'revenue' || document.getElementById('revenueSection').style.display === 'block') {
    if (activeTab === 'withdrawals') {
      // Make sure the function exists before calling it
      if (typeof showAdminTab === 'function') {
        showAdminTab('admin-withdrawal-history');
      }
    } else {
      // Make sure the function exists before calling it
      if (typeof showAdminTab === 'function') {
        showAdminTab('admin-revenue-transactions');
      }
    }
  }
  
  // Form validation
  const adminWithdrawalForm = document.getElementById('adminWithdrawalForm');
  if(adminWithdrawalForm) {
    adminWithdrawalForm.addEventListener('submit', function(event) {
      const amount = parseFloat(document.getElementById('withdrawal_amount').value);
      const availableRevenue = parseFloat('{{ $availableRevenue ?? 0 }}');
      
      if(amount > availableRevenue) {
        event.preventDefault();
        alert('Withdrawal amount cannot exceed available revenue.');
      }
      
      if(amount <= 0) {
        event.preventDefault();
        alert('Please enter a valid withdrawal amount.');
      }
    });
  }
});
</script>