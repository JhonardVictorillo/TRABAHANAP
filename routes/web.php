<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\FreelancerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\adminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\CategoryRequestController;
use App\Http\Controllers\Auth\RoleSwitchController;
use App\Http\Controllers\PlatformWithdrawalController;
use App\Http\Controllers\AdminPayoutController;
use App\Http\Controllers\FreelancerPayoutController;
use App\Http\Controllers\StripeConnectController;
use App\Http\Controllers\Admin\ViolationController;



use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
// Home Route
Route::get('/', [HomepageController::class, 'index'])->name('homepage');
// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// Email Verification Route
Route::get('/email/verify/{token}', [RegisterController::class, 'verifyEmail'])->name('register.verifyEmail');

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Logout Route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Role Selection Routes (Protected by 'auth' middleware)
// Route::get('/select-role', [RoleController::class, 'showRoleForm'])->name('select.role')->middleware('auth');
// Route::post('/select-role', [RoleController::class, 'saveRole'])->name('save.role')->middleware('auth');

// Freelancer Dashboard Route
Route::get('/freelancer-dashboard', function () {
    if (!Auth::check() || Auth::user()->role !== 'freelancer') {
        return redirect()->route('login')->with('error', 'You do not have access to this page.');
    }
    return (new FreelancerController)->dashboard(); // or return view('freelancer.dashboard');
})->name('freelancer.dashboard');

// POST or PATCH route for handling updates
// Route::post('/freelancer-dashboard/update', [FreelancerController::class, 'update'])->name('freelancer.dashboard.update');

Route::prefix('profile')->middleware('auth')->group(function () {
    // Route::get('/freelancer', [ProfileController::class, 'showFreelancerProfileForm'])
    //     ->name('profile.freelancer');

    // Route::get('/customer', [ProfileController::class, 'showCustomerProfileForm'])
    //     ->name('profile.customer');

    Route::post('/complete', [ProfileController::class, 'submitCompleteProfileForm'])
        ->name('profile.complete');
});




// Customer Dashboard Route
Route::get('/customer-dashboard', function () {
    if (!Auth::check() || Auth::user()->role !== 'customer') {
        return redirect()->route('login')->with('error', 'You do not have access to this page.');
    }
    return (new CustomerController)->dashboard(); // or return view('customer.dashboard');
})->name('customer.dashboard');


//Admin routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});
Route::put('/admin/posts/{id}/approve', [AdminController::class, 'approvePost'])->name('admin.approvePost');
Route::put('/admin/posts/{id}/reject', [AdminController::class, 'rejectPost'])->name('admin.rejectPost');
Route::get('/admin/freelancer/{id}/details', [AdminController::class, 'getFreelancerDetails'])->name('admin.freelancer.details');
    
Route::post('/admin/freelancer/{id}/verify', [AdminController::class, 'verifyFreelancer'])->name('admin.verifyFreelancer');
Route::post('/admin/freelancer/{id}/reject', [AdminController::class, 'rejectFreelancer'])->name('admin.rejectFreelancer');

Route::post('/admin/notifications/mark-all-as-read', [AdminController::class, 'markNotificationsAsRead'])->name('admin.notifications.markAllAsRead');
Route::post('/admin/notifications/{id}/mark-as-read', [AdminController::class, 'markSingleNotificationAsRead'])->name('admin.notifications.markSingleAsRead');
Route::get('/admin/notifications', [AdminController::class, 'getNotifications'])->name('admin.notifications.get');

Route::prefix('admin')->group(function () {
    // Category request routes
    Route::post('/category-requests/{id}/approve', [CategoryRequestController::class, 'approve'])
        ->name('admin.category-requests.approve');
    Route::post('/category-requests/{id}/decline', [CategoryRequestController::class, 'decline'])
        ->name('admin.category-requests.decline');
    Route::get('/category-requests/pending-count', [CategoryRequestController::class, 'pendingCount'])
        ->name('admin.category-requests.pending-count');

         // Platform withdrawal routes
    Route::post('/admin/platform-withdrawals', [PlatformWithdrawalController::class, 'store'])
        ->name('admin.withdraw.platform.revenue');
});

//complete profile route
Route::post('/profile/complete', [ProfileController::class, 'submitCompleteProfileForm'])->name('profile.complete');



//    customer profile
Route::get('/profile', [CustomerController::class, 'showProfile'])->name('customer.profile');

//customer appointments
// Render the calendar page
Route::get('/appointments', [CustomerController::class, 'showAppointments'])->name('customer.appointments.view');
Route::get('/customer/appointments', [CustomerController::class, 'getAppointments'])->name('customer.appointments.data');
Route::get('/customer/appointments/{id}', [CustomerController::class, 'getAppointmentDetails'])->name('customer.appointment.details');
Route::post('/customer/appointments/cancel/{id}', [CustomerController::class, 'cancelAppointment'])->name('customer.appointments.cancel');
Route::post('/customer/appointments/reschedule/{id}', [CustomerController::class, 'rescheduleAppointment'])->name('customer.appointments.reschedule');

//customer notifications
Route::post('/notifications/mark-as-read/{id}', [CustomerController::class, 'markNotificationAsRead'])->name('notifications.mark-as-read');
Route::post('/notifications/mark-all-as-read', [CustomerController::class, 'markAllNotificationsAsRead'])->name('notifications.mark-all-as-read');
// post store
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

    // post see profile and add appointments in customer view
Route::get('/freelancer/{id}/profile/{postId?}', [CustomerController::class, 'showFreelancerProfile'])->name('freelancer.profile');
Route::post('/book-appointment', [CustomerController::class, 'bookAppointment'])->name('book.appointment');


// notification routes
Route::post('/appointments/accept/{id}', [FreelancerController::class, 'acceptAppointment'])->name('appointments.accept');
Route::post('/appointments/decline/{id}', [FreelancerController::class, 'declineAppointment'])->name('appointments.decline');

//freelancer schedule

Route::post('/freelancer/set-availability', [FreelancerController::class, 'setAvailability'])->name('freelancer.setAvailability');
Route::get('/freelancer/availabilities/{id}/edit', [FreelancerController::class, 'editAvailability'])->name('freelancer.availabilities.edit');
Route::put('/freelancer/availabilities/{id}', [FreelancerController::class, 'updateAvailability'])->name('freelancer.availabilities.update');
Route::delete('/freelancer/availabilities/{id}', [FreelancerController::class, 'destroyAvailability'])->name('freelancer.availabilities.destroy');
//Appointment done status
Route::post('/appointments/{appointmentId}/complete', [FreelancerController::class, 'markAsCompleted'])->name('appointments.complete');

//rating appointment
Route::post('/customer/appointments/review/{id}', [CustomerController::class, 'rateAppointment'])->name('customer.appointments.review');
//search routes
Route::get('/search', [CustomerController::class, 'search'])->name('search');
// Route::get('/freelancer/dashboard', [FreelancerController::class, 'dashboard'])->name('freelancer.dashboard');
//edit category part
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
Route::get('/categories/{category}/users', [CategoryController::class, 'getUsers'])->name('categories.users');
// post edit
Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
Route::delete('posts/{id}/', [PostController::class, 'delete'])->name('posts.delete');
Route::get('/posts/{id}/view', [FreelancerController::class, 'viewPost'])->name('posts.view');
//freelancer notifications
Route::post('/freelancer/notifications/mark-all-as-read', [FreelancerController::class, 'markNotificationsAsRead'])->name('freelancer.notifications.markAllAsRead');
Route::post('/freelancer/notifications/{id}/mark-as-read', [FreelancerController::class, 'markSingleNotificationAsRead'])->name('freelancer.notifications.markSingleAsRead');
Route::get('/freelancer/notifications', [FreelancerController::class, 'getNotifications'])->name('notifications.get');
//appointment routes
Route::get('/freelancer/appointments', [FreelancerController::class, 'getAppointments'])->name('appointments.get');
Route::get('/freelancer/appointments/{id}', [FreelancerController::class, 'show'])->name('appointments.show');

//freelancer updateprofile
Route::put('/freelancer/profile/update', [FreelancerController::class, 'update'])->name('freelancer.updateProfile');

// Forgot Password Form
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');

// Send Reset Link Email
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Reset Password Form
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

// Handle Password Reset
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::post('/validate-email', [ForgotPasswordController::class, 'validateEmail'])->name('validate.email');

// customer edit profile
Route::put('/customer/profile/update', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');
//customer view freelancer schedule
Route::get('/freelancer/{freelancerId}/availability', [CustomerController::class, 'getAvailability'])->name('freelancer.availability');

//payment routes
Route::post('/pay/commitment-fee', [PaymentController::class, 'createCheckoutSession'])->name('pay.commitment');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
Route::post('/appointment/{id}/complete-payment', [PaymentController::class, 'createFinalPaymentSession'])->name('payment.final');
Route::get('/payment/final-success', [PaymentController::class, 'finalPaymentSuccess'])->name('payment.final.success');
Route::get('/payment/final-cancel', [PaymentController::class, 'finalPaymentCancel'])->name('payment.final.cancel');


//banned & unbanned freelancer
Route::post('/admin/user/{id}/ban', [AdminController::class, 'banUser'])->name('admin.user.ban');
Route::post('/admin/user/{id}/unban', [AdminController::class, 'unbanUser'])->name('admin.user.unban');

// no-show appointment
Route::post('/appointments/{id}/no-show', [AppointmentController::class, 'markNoShow'])->name('appointments.no_show');
Route::get('/freelancer/{id}/available-schedules', [AppointmentController::class, 'getAvailableSchedules']);



// Role switching routes
Route::post('/switch-mode', [RoleSwitchController::class, 'switchMode'])->name('switch.mode');

// Become a freelancer/customer
Route::get('/become-freelancer', [RoleSwitchController::class, 'becomeFreelancer'])->name('become.freelancer');
Route::post('/become-freelancer', [RoleSwitchController::class, 'processFreelancerApplication']);

Route::get('/become-customer', [RoleSwitchController::class, 'becomeCustomer'])->name('become.customer');
Route::post('/become-customer', [RoleSwitchController::class, 'processCustomerApplication']);

// Onboarding routes
Route::get('/freelancer-onboarding', [RoleSwitchController::class, 'freelancerOnboarding'])->name('freelancer.onboarding');
Route::post('/freelancer-onboarding', [RoleSwitchController::class, 'completeFreelancerOnboarding']);

Route::get('/customer-onboarding', [RoleSwitchController::class, 'customerOnboarding'])->name('customer.onboarding');
Route::post('/customer-onboarding', [RoleSwitchController::class, 'completeCustomerOnboarding']);

// Freelancer withdrawal routes - simplified without middleware
Route::prefix('freelancer')->name('freelancer.')->group(function () {
    Route::post('/withdraw', [FreelancerPayoutController::class, 'store'])->name('withdraw');
    Route::post('/withdrawals/{id}/cancel', [FreelancerPayoutController::class, 'cancel'])->name('withdraw.cancel');
  
    Route::get('/withdrawals/{id}/details', [FreelancerPayoutController::class, 'getDetails'])->name('withdraw.details');
    Route::post('/settings/auto-transfer', [FreelancerController::class, 'updatePaymentSettings'])->name('settings.update-auto-transfer');
});

// Admin withdrawal management routes - simplified without middleware
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/withdrawals', [AdminPayoutController::class, 'index'])->name('withdrawals.index');
    Route::get('/withdrawals/{id}', [AdminPayoutController::class, 'show'])->name('withdrawals.show');
    Route::post('/withdrawals/{id}/process', [AdminPayoutController::class, 'process'])->name('withdrawals.process');
    Route::post('/withdrawals/{id}/reject', [AdminPayoutController::class, 'reject'])->name('withdrawals.reject');
  
});
Route::get('admin/withdrawals/{id}/details', [AdminPayoutController::class, 'getDetails']);

Route::post('/admin/withdrawals/{id}/complete', [AdminController::class, 'completeWithdrawal'])
    ->name('admin.withdrawals.complete')
    ->middleware('auth');

 Route::post('/admin/platform-withdrawals', [PlatformWithdrawalController::class, 'store'])
    ->name('admin.platform.withdrawals.store')
    ->middleware('auth'); 

Route::post('/admin/platform-withdrawals/{id}/complete', [PlatformWithdrawalController::class, 'complete'])
    ->name('admin.platform.withdrawals.complete')
    ->middleware('auth'); 

Route::prefix('stripe/connect')->name('stripe.connect.')->group(function() {
    Route::get('/authorize', [StripeConnectController::class, 'redirectToStripeConnect'])->name('authorize');
    Route::get('/callback', [StripeConnectController::class, 'handleCallback'])->name('callback');
    Route::get('/dashboard', [StripeConnectController::class, 'showDashboard'])->name('dashboard');
     Route::get('/refresh', [StripeConnectController::class, 'refreshAccountLink'])->name('refresh');
});


  Route::get('/stripe/connect', [StripeConnectController::class, 'connectAccount'])
    ->name('stripe.connect')
    ->middleware('auth');
    
Route::get('/stripe/status', [StripeConnectController::class, 'getStatus'])
    ->name('stripe.status')
    ->middleware('auth');

// Admin Violation Routes
Route::prefix('admin/violations')->middleware('auth')->group(function () {
    Route::get('/get-details/{id}', [ViolationController::class, 'getDetails']);
    Route::post('/warning', [ViolationController::class, 'sendWarning']);
    Route::post('/suspend', [ViolationController::class, 'toggleSuspension']);
    Route::post('/apply-action', [ViolationController::class, 'applyAction']);
});

Route::post('/admin/violation-settings', [ViolationController::class, 'saveSettings'])
    ->middleware('auth');

Route::post('/freelancers/{id}/commission', [AdminController::class, 'updateCommission'])
    ->name('admin.freelancers.updateCommission');