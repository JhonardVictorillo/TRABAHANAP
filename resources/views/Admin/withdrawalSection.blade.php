
<div id="withdrawalsSection" class="withdrawal-section" style="display: none;">
    <div class="withdrawal-container">
        <div class="withdrawal-header">
            <h2 class="section-title">Manage Withdrawal Requests</h2>

        </div>
        
        <!-- Stats Cards -->
        <div class="withdrawal-dashboard-cards">
            <div class="withdrawal-card withdrawal-primary-card">
                <div class="withdrawal-icon">
                    <span class="material-symbols-outlined">pending</span>
                </div>
                <div class="withdrawal-card-content">
                    <p>Pending Withdrawals</p>
                    <h3>{{ $stats['pending_count'] }} requests</h3>
                    <small>Total: ₱{{ number_format($stats['total_pending_amount'], 2) }}</small>
                </div>
            </div>
            
            <div class="withdrawal-card">
                <div class="withdrawal-icon">
                    <span class="material-symbols-outlined">hourglass_top</span>
                </div>
                <div class="withdrawal-card-content">
                    <p>Processing</p>
                    <h3>{{ $stats['processing_count'] }} requests</h3>
                    <small>Total: ₱{{ number_format($stats['total_processing_amount'], 2) }}</small>
                </div>
            </div>
            
            <div class="withdrawal-card">
                <div class="withdrawal-icon">
                    <span class="material-symbols-outlined">task_alt</span>
                </div>
                <div class="withdrawal-card-content">
                    <p>Completed</p>
                    <h3>{{ $stats['completed_count'] }} requests</h3>
                    <small>Total: ₱{{ number_format($stats['total_completed_amount'], 2) }}</small>
                </div>
            </div>
            
            <div class="withdrawal-card">
                <div class="withdrawal-icon">
                    <span class="material-symbols-outlined">cancel</span>
                </div>
                <div class="withdrawal-card-content">
                    <p>Rejected</p>
                    <h3>{{ $stats['rejected_count'] }}</h3>
                    <small>requests</small>
                </div>
            </div>
        </div>
        
        <!-- Status messages -->
        @if(session('success'))
            <div class="withdrawal-alert withdrawal-alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button class="withdrawal-alert-close">&times;</button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="withdrawal-alert withdrawal-alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button class="withdrawal-alert-close">&times;</button>
            </div>
        @endif
        
        <!-- Tabbed navigation for withdrawal status -->
        <div class="withdrawal-tabs">
            <button class="withdrawal-tab-btn {{ $status == 'all' ? 'active' : '' }}" 
                    onclick="showWithdrawalTab('all-withdrawals')">All Withdrawals</button>
            <button class="withdrawal-tab-btn {{ $status == 'pending' ? 'active' : '' }}" 
                    onclick="showWithdrawalTab('pending-withdrawals')">
                Pending 
                @if($stats['pending_count'] > 0)
                    <span class="withdrawal-badge">{{ $stats['pending_count'] }}</span>
                @endif
            </button>
            <button class="withdrawal-tab-btn {{ $status == 'processing' ? 'active' : '' }}" 
                    onclick="showWithdrawalTab('processing-withdrawals')">
                Processing
                @if($stats['processing_count'] > 0)
                    <span class="withdrawal-badge">{{ $stats['processing_count'] }}</span>
                @endif
            </button>
            <button class="withdrawal-tab-btn {{ $status == 'completed' ? 'active' : '' }}" 
                    onclick="showWithdrawalTab('completed-withdrawals')">Completed</button>
            <button class="withdrawal-tab-btn {{ $status == 'rejected' ? 'active' : '' }}" 
                    onclick="showWithdrawalTab('rejected-withdrawals')">Rejected</button>
        </div>
        
        <!-- All Withdrawals Tab -->
        <div id="all-withdrawals" class="withdrawal-tab-content {{ $status == 'all' ? 'active' : '' }}">
            <div class="withdrawal-section-content">
                <div class="withdrawal-table-header">
                    <h2>All Withdrawal Requests</h2>
                    
                    <!-- Search form -->
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="withdrawal-search-form">
                        <input type="hidden" name="section" value="withdrawals">
                        <div class="withdrawal-search-container">
                            <span class="material-symbols-outlined">search</span>
                            <input 
                                type="text" 
                                name="search" 
                                placeholder="Search by name or email..." 
                                value="{{ request('search') }}" 
                                class="withdrawal-search-input"
                            >
                            <button type="submit" class="withdrawal-search-btn">Search</button>
                        </div>
                    </form>
                </div>
                
               <div class="admin-table-container">
                        <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Freelancer</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Requested</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allWithdrawals ?? $withdrawals as $withdrawal)
                                <tr>
                                    <td>{{ $withdrawal->id }}</td>
                                    <td>
                                        <div class="withdrawal-user-info">
                                            <div class="withdrawal-user-details">
                                                <span class="withdrawal-user-name">{{ $withdrawal->freelancer->firstname }} {{ $withdrawal->freelancer->lastname }}</span>
                                                <span class="withdrawal-user-email">{{ $withdrawal->freelancer->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="withdrawal-amount">₱{{ number_format($withdrawal->amount, 2) }}</td>
                                    <td>
                                        @if($withdrawal->payment_method == 'stripe')
                                            <span class="withdrawal-method withdrawal-method-stripe">
                                                <i class="fab fa-stripe-s"></i> Stripe Connect
                                            </span>
                                        @elseif($withdrawal->payment_method == 'bank_transfer')
                                            <span class="withdrawal-method withdrawal-method-bank">
                                                <i class="fas fa-university"></i> Bank Transfer
                                            </span>
                                        @elseif($withdrawal->payment_method == 'gcash')
                                            <span class="withdrawal-method withdrawal-method-gcash">
                                                <i class="fas fa-mobile-alt"></i> GCash
                                            </span>
                                        @else
                                            <span class="withdrawal-method withdrawal-method-other">
                                                <i class="fas fa-wallet"></i> {{ ucfirst($withdrawal->payment_method) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="withdrawal-status withdrawal-status-{{ $withdrawal->status }}">
                                            {{ ucfirst($withdrawal->status) }}
                                        </span>
                                    </td>
                                    <td class="withdrawal-date">
                                        <div class="withdrawal-date-info">
                                            <span class="withdrawal-date-full">{{ $withdrawal->created_at->format('M d, Y') }}</span>
                                            <span class="withdrawal-date-relative">{{ $withdrawal->created_at->diffForHumans() }}</span>
                                        </div>
                                    </td>
                                    <td>
                                      <a href="javascript:void(0);" 
                                        onclick="viewWithdrawalDetails({{ $withdrawal->id }})" 
                                        class="withdrawal-btn withdrawal-btn-success">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="withdrawal-empty-state">
                                        <div class="withdrawal-empty-container">
                                            <div class="withdrawal-empty-icon">
                                                <i class="fas fa-file-invoice-dollar"></i>
                                            </div>
                                            <h3>No withdrawal requests found</h3>
                                            <p>When freelancers make withdrawal requests, they will appear here.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
               @if(isset($allWithdrawals) && method_exists($allWithdrawals, 'links'))
                <div class="withdrawal-pagination-container">
                    {{ $allWithdrawals->appends(['status' => $status])->links() }}
                </div>
            @endif
            </div>
        </div>
        
        <!-- Pending Withdrawals Tab -->
        <div id="pending-withdrawals" class="withdrawal-tab-content {{ $status == 'pending' ? 'active' : '' }}">
            <div class="withdrawal-section-content">
                <div class="withdrawal-table-header">
                    <h2>Pending Withdrawal Requests</h2>
                </div>
                
               <div class="admin-table-container">
                     <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Freelancer</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Requested</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                           @forelse($pendingWithdrawals as $withdrawal)
                                <tr>
                                    <td>{{ $withdrawal->id }}</td>
                                    <td>
                                        <div class="withdrawal-user-info">
                                            <div class="withdrawal-user-details">
                                                <span class="withdrawal-user-name">{{ $withdrawal->freelancer->firstname }} {{ $withdrawal->freelancer->lastname }}</span>
                                                <span class="withdrawal-user-email">{{ $withdrawal->freelancer->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="withdrawal-amount">₱{{ number_format($withdrawal->amount, 2) }}</td>
                                    <td>
                                        @if($withdrawal->payment_method == 'stripe')
                                            <span class="withdrawal-method withdrawal-method-stripe">
                                                <i class="fab fa-stripe-s"></i> Stripe Connect
                                            </span>
                                        @elseif($withdrawal->payment_method == 'bank_transfer')
                                            <span class="withdrawal-method withdrawal-method-bank">
                                                <i class="fas fa-university"></i> Bank Transfer
                                            </span>
                                        @elseif($withdrawal->payment_method == 'gcash')
                                            <span class="withdrawal-method withdrawal-method-gcash">
                                                <i class="fas fa-mobile-alt"></i> GCash
                                            </span>
                                        @else
                                            <span class="withdrawal-method withdrawal-method-other">
                                                <i class="fas fa-wallet"></i> {{ ucfirst($withdrawal->payment_method) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="withdrawal-date">
                                        <div class="withdrawal-date-info">
                                            <span class="withdrawal-date-full">{{ $withdrawal->created_at->format('M d, Y') }}</span>
                                            <span class="withdrawal-date-relative">{{ $withdrawal->created_at->diffForHumans() }}</span>
                                        </div>
                                    </td>
                                    <td>
                                       <a href="javascript:void(0);" 
                                        onclick="viewWithdrawalDetails({{ $withdrawal->id }})" 
                                        class="withdrawal-btn withdrawal-btn-success">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="withdrawal-empty-state">
                                        <div class="withdrawal-empty-container">
                                            <div class="withdrawal-empty-icon">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                            <h3>No pending withdrawal requests</h3>
                                            <p>All withdrawal requests have been processed.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination for pending -->
                @if(isset($pendingWithdrawals) && method_exists($pendingWithdrawals, 'links'))
                    <div class="withdrawal-pagination-container">
                        {{ $pendingWithdrawals->appends(['status' => 'pending'])->links() }}
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Processing Withdrawals Tab -->
        <div id="processing-withdrawals" class="withdrawal-tab-content {{ $status == 'processing' ? 'active' : '' }}">
            <div class="withdrawal-section-content">
                <div class="withdrawal-table-header">
                    <h2>Processing Withdrawal Requests</h2>
                </div>
                
               <div class="admin-table-container">
                     <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Freelancer</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Requested</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                          @forelse($processingWithdrawals as $withdrawal)
                                <tr>
                                    <td>{{ $withdrawal->id }}</td>
                                    <td>
                                        <div class="withdrawal-user-info">
                                            <div class="withdrawal-user-details">
                                                <span class="withdrawal-user-name">{{ $withdrawal->freelancer->firstname }} {{ $withdrawal->freelancer->lastname }}</span>
                                                <span class="withdrawal-user-email">{{ $withdrawal->freelancer->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="withdrawal-amount">₱{{ number_format($withdrawal->amount, 2) }}</td>
                                    <td>
                                        @if($withdrawal->payment_method == 'stripe')
                                            <span class="withdrawal-method withdrawal-method-stripe">
                                                <i class="fab fa-stripe-s"></i> Stripe Connect
                                            </span>
                                        @elseif($withdrawal->payment_method == 'bank_transfer')
                                            <span class="withdrawal-method withdrawal-method-bank">
                                                <i class="fas fa-university"></i> Bank Transfer
                                            </span>
                                        @elseif($withdrawal->payment_method == 'gcash')
                                            <span class="withdrawal-method withdrawal-method-gcash">
                                                <i class="fas fa-mobile-alt"></i> GCash
                                            </span>
                                        @else
                                            <span class="withdrawal-method withdrawal-method-other">
                                                <i class="fas fa-wallet"></i> {{ ucfirst($withdrawal->payment_method) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="withdrawal-date">
                                        <div class="withdrawal-date-info">
                                            <span class="withdrawal-date-full">{{ $withdrawal->created_at->format('M d, Y') }}</span>
                                            <span class="withdrawal-date-relative">{{ $withdrawal->created_at->diffForHumans() }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <button 
                                            onclick="completeWithdrawal({{ $withdrawal->id }})" 
                                            class="withdrawal-btn withdrawal-btn-info complete-withdrawal-btn">
                                            <i class="fas fa-check-double"></i> Mark as Complete
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="withdrawal-empty-state">
                                        <div class="withdrawal-empty-container">
                                            <div class="withdrawal-empty-icon">
                                                <i class="fas fa-hourglass"></i>
                                            </div>
                                            <h3>No withdrawals being processed</h3>
                                            <p>There are currently no withdrawals in the processing state.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination for processing -->
                <!-- Processing Withdrawals Tab -->
                @if(isset($processingWithdrawals) && method_exists($processingWithdrawals, 'links'))
                    <div class="withdrawal-pagination-container">
                        {{ $processingWithdrawals->appends(['status' => 'processing'])->links() }}
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Completed Withdrawals Tab -->
        <div id="completed-withdrawals" class="withdrawal-tab-content {{ $status == 'completed' ? 'active' : '' }}">
            <div class="withdrawal-section-content">
                <div class="withdrawal-table-header">
                    <h2>Completed Withdrawals</h2>
                </div>

               <div class="admin-table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Freelancer</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Requested</th>
                                <th>Completed</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                           @forelse($completedWithdrawals as $withdrawal)
                                <tr>
                                    <td>{{ $withdrawal->id }}</td>
                                    <td>
                                        <div class="withdrawal-user-info">
                                            <div class="withdrawal-user-details">
                                                <span class="withdrawal-user-name">{{ $withdrawal->freelancer->firstname }} {{ $withdrawal->freelancer->lastname }}</span>
                                                <span class="withdrawal-user-email">{{ $withdrawal->freelancer->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="withdrawal-amount">₱{{ number_format($withdrawal->amount, 2) }}</td>
                                    <td>
                                        @if($withdrawal->payment_method == 'stripe')
                                            <span class="withdrawal-method withdrawal-method-stripe">
                                                <i class="fab fa-stripe-s"></i> Stripe Connect
                                            </span>
                                        @elseif($withdrawal->payment_method == 'bank_transfer')
                                            <span class="withdrawal-method withdrawal-method-bank">
                                                <i class="fas fa-university"></i> Bank Transfer
                                            </span>
                                        @elseif($withdrawal->payment_method == 'gcash')
                                            <span class="withdrawal-method withdrawal-method-gcash">
                                                <i class="fas fa-mobile-alt"></i> GCash
                                            </span>
                                        @else
                                            <span class="withdrawal-method withdrawal-method-other">
                                                <i class="fas fa-wallet"></i> {{ ucfirst($withdrawal->payment_method) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="withdrawal-date">
                                        {{ $withdrawal->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="withdrawal-date">
                                        {{ \Carbon\Carbon::parse($withdrawal->processed_at)->format('M d, Y') }}
                                    </td>
                                    <td>
                                       <a href="javascript:void(0);" 
                                        onclick="viewWithdrawalDetails({{ $withdrawal->id }})" 
                                        class="withdrawal-btn withdrawal-btn-success">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="withdrawal-empty-state">
                                        <div class="withdrawal-empty-container">
                                            <div class="withdrawal-empty-icon">
                                                <i class="fas fa-file-invoice"></i>
                                            </div>
                                            <h3>No completed withdrawals</h3>
                                            <p>When withdrawals are completed, they will appear here.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination for completed -->
                @if(isset($completedWithdrawals) && method_exists($completedWithdrawals, 'links'))
                    <div class="withdrawal-pagination-container">
                        {{ $completedWithdrawals->appends(['status' => 'completed'])->links() }}
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Rejected Withdrawals Tab -->
        <div id="rejected-withdrawals" class="withdrawal-tab-content {{ $status == 'rejected' ? 'active' : '' }}">
            <div class="withdrawal-section-content">
                <div class="withdrawal-table-header">
                    <h2>Rejected Withdrawals</h2>
                </div>

               <div class="admin-table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Freelancer</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Requested</th>
                                <th>Rejected</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                           @forelse($rejectedWithdrawals as $withdrawal)
                                <tr>
                                    <td>{{ $withdrawal->id }}</td>
                                    <td>
                                        <div class="withdrawal-user-info">
                                            <div class="withdrawal-user-details">
                                                <span class="withdrawal-user-name">{{ $withdrawal->freelancer->firstname }} {{ $withdrawal->freelancer->lastname }}</span>
                                                <span class="withdrawal-user-email">{{ $withdrawal->freelancer->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="withdrawal-amount">₱{{ number_format($withdrawal->amount, 2) }}</td>
                                    <td>
                                        @if($withdrawal->payment_method == 'stripe')
                                            <span class="withdrawal-method withdrawal-method-stripe">
                                                <i class="fab fa-stripe-s"></i> Stripe Connect
                                            </span>
                                        @elseif($withdrawal->payment_method == 'bank_transfer')
                                            <span class="withdrawal-method withdrawal-method-bank">
                                                <i class="fas fa-university"></i> Bank Transfer
                                            </span>
                                        @elseif($withdrawal->payment_method == 'gcash')
                                            <span class="withdrawal-method withdrawal-method-gcash">
                                                <i class="fas fa-mobile-alt"></i> GCash
                                            </span>
                                        @else
                                            <span class="withdrawal-method withdrawal-method-other">
                                                <i class="fas fa-wallet"></i> {{ ucfirst($withdrawal->payment_method) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="withdrawal-date">
                                        {{ $withdrawal->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="withdrawal-date">
                                        {{ \Carbon\Carbon::parse($withdrawal->processed_at)->format('M d, Y') ?? 'N/A' }}
                                    </td>
                                    <td>
                                          <a href="javascript:void(0);" 
                                            onclick="viewWithdrawalDetails({{ $withdrawal->id }})" 
                                            class="withdrawal-btn withdrawal-btn-secondary">
                                                <i class="fas fa-eye"></i> View Details
                                            </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="withdrawal-empty-state">
                                        <div class="withdrawal-empty-container">
                                            <div class="withdrawal-empty-icon">
                                                <i class="fas fa-ban"></i>
                                            </div>
                                            <h3>No rejected withdrawals</h3>
                                            <p>There are currently no rejected withdrawal requests.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination for rejected -->
                @if(isset($rejectedWithdrawals) && method_exists($rejectedWithdrawals, 'links'))
                    <div class="withdrawal-pagination-container">
                        {{ $rejectedWithdrawals->appends(['status' => 'rejected'])->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div id="withdrawalDetailsModal" class="withdrawal-details-modal">
    <div class="withdrawal-details-modal-content">
        <div class="withdrawal-details-modal-header">
            <h3>
                <span class="material-symbols-outlined">receipt_long</span>
                Withdrawal Request Details
            </h3>
            <span class="withdrawal-details-modal-close">&times;</span>
        </div>
        
        <div class="withdrawal-details-loading" id="withdrawalDetailsLoading">
            <div class="withdrawal-details-spinner"></div>
            <p>Loading withdrawal details...</p>
        </div>
        
        <div class="withdrawal-details-body" id="withdrawalDetailsBody" style="display: none;">
            <!-- Header with status and amount -->
            <div class="withdrawal-details-header">
                <div class="withdrawal-details-status">
                    <span id="withdrawal-status-badge" class="withdrawal-status"></span>
                </div>
                <div class="withdrawal-details-amount">
                    <h2 id="withdrawal-amount">₱0.00</h2>
                </div>
            </div>
            
            <!-- Freelancer info -->
            <div class="withdrawal-details-section">
                <h4>
                    <span class="material-symbols-outlined">person</span>
                    Freelancer Information
                </h4>
                <div class="withdrawal-details-freelancer">
                    <div class="withdrawal-details-profile">
                        <img id="freelancer-avatar" src="" alt="Freelancer" class="withdrawal-details-avatar">
                        <div>
                            <h5 id="freelancer-name">Loading...</h5>
                            <p id="freelancer-email">email@example.com</p>
                        </div>
                    </div>
                    <div class="withdrawal-details-stats">
                        <div class="withdrawal-details-stat">
                            <span class="withdrawal-details-stat-label">Account Balance</span>
                            <span class="withdrawal-details-stat-value" id="freelancer-balance">₱0.00</span>
                        </div>
                        <div class="withdrawal-details-stat">
                            <span class="withdrawal-details-stat-label">Total Earnings</span>
                            <span class="withdrawal-details-stat-value" id="freelancer-earnings">₱0.00</span>
                        </div>
                        <div class="withdrawal-details-stat">
                            <span class="withdrawal-details-stat-label">Past Withdrawals</span>
                            <span class="withdrawal-details-stat-value" id="freelancer-withdrawals">₱0.00</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Payment method details -->
            <div class="withdrawal-details-section">
                <h4>
                    <span class="material-symbols-outlined">account_balance</span>
                    Payment Method
                </h4>
                <div class="withdrawal-details-payment">
                    <div class="withdrawal-details-payment-icon" id="payment-method-icon">
                        <span class="material-symbols-outlined">credit_card</span>
                    </div>
                    <div class="withdrawal-details-payment-info">
                        <h5 id="payment-method-name">Bank Transfer</h5>
                        <div id="payment-method-details"></div>
                    </div>
                </div>
            </div>
            
            <!-- Transaction timeline -->
            <div class="withdrawal-details-section">
                <h4>
                    <span class="material-symbols-outlined">schedule</span>
                    Transaction Timeline
                </h4>
                <div class="withdrawal-details-timeline">
                    <div class="withdrawal-timeline-item">
                        <div class="withdrawal-timeline-indicator requested"></div>
                        <div class="withdrawal-timeline-content">
                            <h5>Requested</h5>
                            <p id="requested-date">Jan 1, 2023</p>
                        </div>
                    </div>
                    
                    <div class="withdrawal-timeline-item" id="processing-timeline-item">
                        <div class="withdrawal-timeline-indicator processing"></div>
                        <div class="withdrawal-timeline-content">
                            <h5>Processing</h5>
                            <p id="processing-date">Jan 2, 2023</p>
                        </div>
                    </div>
                    
                    <div class="withdrawal-timeline-item" id="completed-timeline-item">
                        <div class="withdrawal-timeline-indicator completed"></div>
                        <div class="withdrawal-timeline-content">
                            <h5>Completed</h5>
                            <p id="completed-date">Jan 3, 2023</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Admin notes -->
            <div class="withdrawal-details-section" id="admin-notes-section">
                <h4>
                    <span class="material-symbols-outlined">note</span>
                    Admin Notes
                </h4>
                <div class="withdrawal-details-notes" id="admin-notes">
                    No notes available.
                </div>
            </div>
            
            <!-- Action buttons based on status -->
            <div class="withdrawal-details-actions" id="withdrawal-actions">
                <!-- Buttons will be dynamically inserted here based on status -->
            </div>
        </div>
    </div>
</div>


<script>
  function showWithdrawalTab(tabId) {
    // Hide all tabs
    document.querySelectorAll('.withdrawal-tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.withdrawal-tab-btn').forEach(button => {
        button.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabId).classList.add('active');
    
    // Add active class to clicked button
    event.currentTarget.classList.add('active');
    
    // Update URL to maintain state
    const status = tabId.replace('-withdrawals', '');
    const url = new URL(window.location);
    url.searchParams.set('status', status === 'all' ? 'all' : status);
    window.history.pushState({}, '', url);
}

// Close alert messages
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.withdrawal-alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 500);
        }, 5000);
    });
    
    // Close on click
    const closeButtons = document.querySelectorAll('.withdrawal-alert-close');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const alert = this.closest('.withdrawal-alert');
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 500);
        });
    });
});

function completeWithdrawal(id) {
    if (confirm('Are you sure you want to mark this withdrawal as completed?')) {
        // Show loading state
        const button = event.target.closest('.complete-withdrawal-btn');
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        
        // Log the request details
        console.log('Sending request to mark withdrawal complete:', id);
        
        fetch(`/admin/withdrawals/${id}/complete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Show success message
                const alertContainer = document.createElement('div');
                alertContainer.className = 'withdrawal-alert withdrawal-alert-success';
                alertContainer.innerHTML = `
                    <i class="fas fa-check-circle"></i> ${data.message}
                    <button class="withdrawal-alert-close" onclick="this.parentElement.remove()">&times;</button>
                `;
                document.querySelector('.withdrawal-header').after(alertContainer);
                
                // Remove the row or refresh the page
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                // Restore button
                button.disabled = false;
                button.innerHTML = originalText;
                
                // Show error message
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            button.disabled = false;
            button.innerHTML = originalText;
            alert('An error occurred while processing your request: ' + error.message);
        });
    }
}




// Function to fetch and display withdrawal details
function viewWithdrawalDetails(id) {
    // Show modal
    const modal = document.getElementById('withdrawalDetailsModal');
    modal.style.display = 'block';
    
    // Show loading state, hide content
    document.getElementById('withdrawalDetailsLoading').style.display = 'block';
    document.getElementById('withdrawalDetailsBody').style.display = 'none';
    
    // Fetch withdrawal details
    fetch(`/admin/withdrawals/${id}/details`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Received data:", data); // Debug info
            if (data.success) {
                populateWithdrawalDetails(data.withdrawal);
            } else {
                throw new Error(data.message || 'Failed to load withdrawal details');
            }
        })
        .catch(error => {
            console.error('Error loading withdrawal details:', error);
            alert('Error: ' + error.message);
            // Keep modal open but show error
            document.getElementById('withdrawalDetailsLoading').style.display = 'none';
            document.getElementById('withdrawalDetailsBody').innerHTML = 
                `<div class="withdrawal-details-error">
                    <span class="material-symbols-outlined">error</span>
                    <p>Failed to load withdrawal details.</p>
                    <p class="withdrawal-details-error-message">${error.message}</p>
                </div>`;
            document.getElementById('withdrawalDetailsBody').style.display = 'block';
        });
}

// Function to populate the modal with withdrawal details
function populateWithdrawalDetails(withdrawal) {
    // Hide loading, show content
    document.getElementById('withdrawalDetailsLoading').style.display = 'none';
    document.getElementById('withdrawalDetailsBody').style.display = 'block';
    
    // Set status badge
    const statusBadge = document.getElementById('withdrawal-status-badge');
    statusBadge.textContent = withdrawal.status.charAt(0).toUpperCase() + withdrawal.status.slice(1);
    statusBadge.className = `withdrawal-status withdrawal-status-${withdrawal.status}`;
    
    // Set amount
    document.getElementById('withdrawal-amount').textContent = `₱${parseFloat(withdrawal.amount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    
    // Set freelancer information
    document.getElementById('freelancer-name').textContent = `${withdrawal.freelancer.firstname} ${withdrawal.freelancer.lastname}`;
    document.getElementById('freelancer-email').textContent = withdrawal.freelancer.email;
    
    // Set freelancer avatar
    const avatarImg = document.getElementById('freelancer-avatar');
    if (withdrawal.freelancer.profile_photo_path) {
        avatarImg.src = `/storage/${withdrawal.freelancer.profile_photo_path}`;
    } else {
        // Default avatar
        avatarImg.src = 'https://ui-avatars.com/api/?name=' + 
            encodeURIComponent(`${withdrawal.freelancer.firstname} ${withdrawal.freelancer.lastname}`) + 
            '&color=7F9CF5&background=EBF4FF';
    }
    
    // Set financial stats
    document.getElementById('freelancer-balance').textContent = `₱${parseFloat(withdrawal.balance || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    document.getElementById('freelancer-earnings').textContent = `₱${parseFloat(withdrawal.total_earnings || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    document.getElementById('freelancer-withdrawals').textContent = `₱${parseFloat(withdrawal.previous_withdrawals || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    
    // Set payment method details - SIMPLIFIED APPROACH
    const paymentMethodIcon = document.getElementById('payment-method-icon');
    const paymentMethodName = document.getElementById('payment-method-name');
    const paymentMethodDetails = document.getElementById('payment-method-details');
    
    // Clear previous details
    paymentMethodDetails.innerHTML = '';
    
    // Set icon and name based on payment method
    switch(withdrawal.payment_method) {
        case 'stripe':
            paymentMethodIcon.className = 'withdrawal-details-payment-icon stripe';
            paymentMethodIcon.innerHTML = '<i class="fab fa-stripe-s"></i>';
            paymentMethodName.textContent = 'Stripe Connect';
            
            // Create details list - without parsing JSON
            if (withdrawal.payment_details_html) {
                paymentMethodDetails.innerHTML = withdrawal.payment_details_html;
            }
            break;
            
        case 'bank_transfer':
            paymentMethodIcon.className = 'withdrawal-details-payment-icon bank';
            paymentMethodIcon.innerHTML = '<span class="material-symbols-outlined">account_balance</span>';
            paymentMethodName.textContent = 'Bank Transfer';
            
            // Create details list - without parsing JSON
            if (withdrawal.payment_details_html) {
                paymentMethodDetails.innerHTML = withdrawal.payment_details_html;
            }
            break;
            
        case 'gcash':
            paymentMethodIcon.className = 'withdrawal-details-payment-icon gcash';
            paymentMethodIcon.innerHTML = '<span class="material-symbols-outlined">smartphone</span>';
            paymentMethodName.textContent = 'GCash';
            
            // Create details list - without parsing JSON
            if (withdrawal.payment_details_html) {
                paymentMethodDetails.innerHTML = withdrawal.payment_details_html;
            }
            break;
            
        default:
            paymentMethodIcon.className = 'withdrawal-details-payment-icon other';
            paymentMethodIcon.innerHTML = '<span class="material-symbols-outlined">wallet</span>';
            paymentMethodName.textContent = withdrawal.payment_method ? withdrawal.payment_method.charAt(0).toUpperCase() + withdrawal.payment_method.slice(1) : 'Other';
    }
    
    // Timeline items
    document.getElementById('requested-date').textContent = formatDate(withdrawal.created_at);
    
    // Processing date (show/hide based on status)
    const processingItem = document.getElementById('processing-timeline-item');
    if (withdrawal.status === 'processing' || withdrawal.status === 'completed') {
        processingItem.style.display = 'block';
        document.getElementById('processing-date').textContent = withdrawal.started_processing_at ? 
            formatDate(withdrawal.started_processing_at) : 
            formatDate(withdrawal.updated_at);
    } else {
        processingItem.style.display = 'none';
    }
    
    // Completed date (show/hide based on status)
    const completedItem = document.getElementById('completed-timeline-item');
    if (withdrawal.status === 'completed') {
        completedItem.style.display = 'block';
        document.getElementById('completed-date').textContent = withdrawal.processed_at ? 
            formatDate(withdrawal.processed_at) : 
            formatDate(withdrawal.updated_at);
    } else {
        completedItem.style.display = 'none';
    }
    
    // Add rejected timeline item if applicable
    if (withdrawal.status === 'rejected') {
        // Remove completed item
        completedItem.style.display = 'none';
        
        // Add rejected item (check if it doesn't already exist first)
        const existingRejected = document.querySelector('.withdrawal-timeline-indicator.rejected');
        if (!existingRejected) {
            const rejectedItem = document.createElement('div');
            rejectedItem.className = 'withdrawal-timeline-item';
            rejectedItem.innerHTML = `
                <div class="withdrawal-timeline-indicator rejected"></div>
                <div class="withdrawal-timeline-content">
                    <h5>Rejected</h5>
                    <p>${withdrawal.processed_at ? formatDate(withdrawal.processed_at) : formatDate(withdrawal.updated_at)}</p>
                </div>
            `;
            document.querySelector('.withdrawal-details-timeline').appendChild(rejectedItem);
        }
    }
    
    // Admin notes
    const adminNotesSection = document.getElementById('admin-notes-section');
    const adminNotes = document.getElementById('admin-notes');
    
    if (withdrawal.admin_notes) {
        adminNotesSection.style.display = 'block';
        adminNotes.textContent = withdrawal.admin_notes;
    } else {
        adminNotesSection.style.display = 'none';
    }
    
    // Action buttons based on status
    const actionsContainer = document.getElementById('withdrawal-actions');
    actionsContainer.innerHTML = ''; // Clear previous buttons
    
    // Add close button (always present)
    const closeButton = document.createElement('button');
    closeButton.className = 'withdrawal-details-btn withdrawal-details-btn-back';
    closeButton.innerHTML = '<span class="material-symbols-outlined">close</span> Close';
    closeButton.addEventListener('click', function() {
        document.getElementById('withdrawalDetailsModal').style.display = 'none';
    });
    
    // Add status-specific buttons
    if (withdrawal.status === 'pending') {
        // Process button
        const processButton = document.createElement('button');
        processButton.className = 'withdrawal-details-btn withdrawal-details-btn-process';
        processButton.innerHTML = '<span class="material-symbols-outlined">sync</span> Process Withdrawal';
        processButton.addEventListener('click', function() {
            processWithdrawal(withdrawal.id);
        });
        actionsContainer.appendChild(processButton);
        
        // Reject button
        const rejectButton = document.createElement('button');
        rejectButton.className = 'withdrawal-details-btn withdrawal-details-btn-reject';
        rejectButton.innerHTML = '<span class="material-symbols-outlined">cancel</span> Reject';
        rejectButton.addEventListener('click', function() {
            rejectWithdrawal(withdrawal.id);
        });
        actionsContainer.appendChild(rejectButton);
    } 
    else if (withdrawal.status === 'processing') {
        // Complete button
        const completeButton = document.createElement('button');
        completeButton.className = 'withdrawal-details-btn withdrawal-details-btn-complete';
        completeButton.innerHTML = '<span class="material-symbols-outlined">check_circle</span> Mark as Completed';
        completeButton.addEventListener('click', function() {
            completeWithdrawal(withdrawal.id, true);
        });
        actionsContainer.appendChild(completeButton);
    }
    
    actionsContainer.appendChild(closeButton);
}

// Helper function to format dates
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Process withdrawal function
function processWithdrawal(id) {
    if (confirm('Are you sure you want to start processing this withdrawal?')) {
        fetch(`/admin/withdrawals/${id}/process`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Withdrawal is now being processed!');
                // Refresh the page or modal
                document.getElementById('withdrawalDetailsModal').style.display = 'none';
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error processing withdrawal:', error);
            alert('An error occurred: ' + error.message);
        });
    }
}

// Reject withdrawal function
function rejectWithdrawal(id) {
    const reason = prompt('Please provide a reason for rejection:');
    if (reason === null) return; // User cancelled
    
    if (reason.trim() === '') {
        alert('Please provide a rejection reason.');
        return;
    }
    
    fetch(`/admin/withdrawals/${id}/reject`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            reason: reason
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Withdrawal has been rejected!');
            // Refresh the page or modal
            document.getElementById('withdrawalDetailsModal').style.display = 'none';
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error rejecting withdrawal:', error);
        alert('An error occurred: ' + error.message);
    });
}

// Complete withdrawal function (can be called directly or from modal)
function completeWithdrawal(id, fromModal = false) {
    if (confirm('Are you sure you want to mark this withdrawal as completed?')) {
        // Show loading state if not from modal
        if (!fromModal) {
            const button = event.target.closest('.complete-withdrawal-btn');
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        }
        
        fetch(`/admin/withdrawals/${id}/complete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Withdrawal marked as completed!');
                
                if (fromModal) {
                    document.getElementById('withdrawalDetailsModal').style.display = 'none';
                } else {
                    // Show success message for table view
                    const alertContainer = document.createElement('div');
                    alertContainer.className = 'withdrawal-alert withdrawal-alert-success';
                    alertContainer.innerHTML = `
                        <i class="fas fa-check-circle"></i> ${data.message}
                        <button class="withdrawal-alert-close" onclick="this.parentElement.remove()">&times;</button>
                    `;
                    document.querySelector('.withdrawal-header').after(alertContainer);
                }
                
                // Refresh the page
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                alert('Error: ' + data.message);
                
                // Restore button if not from modal
                if (!fromModal) {
                    const button = event.target.closest('.complete-withdrawal-btn');
                    button.disabled = false;
                    button.innerHTML = originalText;
                }
            }
        })
        .catch(error => {
            console.error('Error completing withdrawal:', error);
            alert('An error occurred: ' + error.message);
            
            // Restore button if not from modal
            if (!fromModal) {
                const button = event.target.closest('.complete-withdrawal-btn');
                button.disabled = false;
                button.innerHTML = originalText;
            }
        });
    }
}

// Modal close button handler
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('withdrawalDetailsModal');
    const closeBtn = document.querySelector('.withdrawal-details-modal-close');
    
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });
    
    // Close on click outside
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});

// Update the view details links to use the modal instead
document.addEventListener('DOMContentLoaded', function() {
    // Update all "View Details" links to use the modal
    document.querySelectorAll('.withdrawal-btn-primary, .withdrawal-btn-success, .withdrawal-btn-secondary, .withdrawal-btn-warning').forEach(link => {
        if (link.getAttribute('href') && link.getAttribute('href').includes('/admin/withdrawals/')) {
            const id = link.getAttribute('href').split('/').pop();
            link.setAttribute('href', 'javascript:void(0);');
            link.setAttribute('onclick', `viewWithdrawalDetails(${id})`);
        }
    });
});
</script>