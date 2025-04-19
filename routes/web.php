

<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\FreelancerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\adminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
// Home Route
Route::get('/', function () {
    return view('homepage'); // Ensure you have 'home.blade.php'
})->name('homepage');

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Logout Route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Role Selection Routes (Protected by 'auth' middleware)
Route::get('/select-role', [RoleController::class, 'showRoleForm'])->name('select.role')->middleware('auth');
Route::post('/select-role', [RoleController::class, 'saveRole'])->name('save.role')->middleware('auth');

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
    Route::get('/freelancer', [ProfileController::class, 'showFreelancerProfileForm'])
        ->name('profile.freelancer');

    Route::get('/customer', [ProfileController::class, 'showCustomerProfileForm'])
        ->name('profile.customer');

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
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::put('/admin/posts/{id}/approve', [AdminController::class, 'approvePost'])->name('admin.approvePost');
Route::put('/admin/posts/{id}/reject', [AdminController::class, 'rejectPost'])->name('admin.rejectPost');

    
Route::post('/admin/freelancer/{id}/verify', [AdminController::class, 'verifyFreelancer'])->name('admin.verifyFreelancer');
Route::post('/admin/freelancer/{id}/reject', [AdminController::class, 'rejectFreelancer'])->name('admin.rejectFreelancer');


//complete profile route
Route::post('/profile/complete', [ProfileController::class, 'submitCompleteProfileForm'])->name('profile.complete');

//category routes
Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');

//    customer profile
Route::get('/profile', [CustomerController::class, 'showProfile'])->name('customer.profile');

//customer appointments
Route::get('/appointments', [CustomerController::class, 'showAppointments'])->name('customer.appointments');
Route::post('/customer/appointments/{id}/cancel', [CustomerController::class, 'cancelAppointment'])->name('customer.appointments.cancel');

// post store
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

    // post see profile and add appointments in customer view
Route::get('/freelancer/{id}/profile', [CustomerController::class, 'showFreelancerProfile'])->name('freelancer.profile');
Route::post('/book-appointment', [CustomerController::class, 'bookAppointment'])->name('book.appointment');


// notification routes
Route::post('/appointments/accept/{id}', [FreelancerController::class, 'acceptAppointment'])->name('appointments.accept');
Route::post('/appointments/decline/{id}', [FreelancerController::class, 'declineAppointment'])->name('appointments.decline');

//Appointment done status
Route::post('/appointments/{appointment}/complete', [FreelancerController::class, 'markAsCompleted'])->name('appointments.complete');

//rating appointment
Route::post('/customer/appointments/rate/{appointmentId}', [CustomerController::class, 'rateAppointment'])->name('customer.appointments.rate');
//search routes
Route::get('/search', [CustomerController::class, 'search'])->name('search');

//edit category part
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

// post edit
Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
Route::delete('posts/{id}/', [PostController::class, 'delete'])->name('posts.delete');

Route::post('/freelancer/notifications/read', [FreelancerController::class, 'markNotificationsAsRead'])->name('notifications.markAsRead');
Route::post('/freelancer/notifications/read/{id}', [FreelancerController::class, 'markSingleNotificationAsRead']);
Route::get('/freelancer/notifications', [FreelancerController::class, 'getNotifications'])->name('notifications.get');
//appointment routes
Route::get('/freelancer/appointments', [FreelancerController::class, 'getAppointments'])->name('appointments.get');
Route::get('/freelancer/appointments/{id}', [FreelancerController::class, 'show'])->name('appointments.show');

//freelancer updateprofile
Route::put('/freelancer/profile/update', [FreelancerController::class, 'update'])->name('freelancer.updateProfile');
