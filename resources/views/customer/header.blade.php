<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}"> <!-- Adjust if necessary -->
   
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

  <header class="header">
      <h1>trabahanap.</h1>

    
    <div class="header-center">
    <form action="{{ route('search') }}" method="GET" style="display: flex; align-items: center; width: 100%;">
       
    <input 
            type="text" 
            name="q" 
            placeholder="What service are you looking for today?" 
            class="search-bar"
            value="{{ request('q') }}"
       
        >
        <button type="submit" class="search-button">
            <i class="fas fa-search"></i>
        </button>
    </form>
    </div>


    <div class="header-right">
            <i class="bx bx-bell" id="notification-icon" data-text="Notifications">
            @if($notifications->count() > 0)
        <span class="notification-count">{{ $notifications->count() }}</span>
          @endif
            </i>

        <!-- Notification Dropdown -->
        <div id="notification-section" class="notification-section" style="display: none;">
            <h3>Notifications</h3>
            <ul id="notification-list">
                <!-- Notifications will be populated here -->
                @foreach(auth()->user()->unreadNotifications as $notification)
                    <li>{{ $notification->data['message'] }}</li>
                @endforeach
            </ul>
        </div>

        <i class='bx bx-envelope' data-text="Messages"></i>
        <i class='bx bxs-heart' data-text="Favorites"></i>
        <div class="profile">
          <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile" class="profile-img">
          <div class="dropdown-menu">
            <div class="profile-info">
              <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile" class="profile-thumb">
              <div class="profile-name">{{ $user->firstname }} {{ $user->lastname }}</div>
            </div>
            <hr>
            <a href="{{ route('customer.profile') }}">
              <i class='bx bx-user-circle'></i>Profile</a>
            <a href="#">
              <i class='bx bx-cog' ></i>Settings</a>
              <a href="{{ route('customer.appointments') }}">
              <i class='bx bx-calendar'></i>Appointments</a>
            <a href="#">
              <i class='bx bx-help-circle'></i> Help</a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class='bx bx-log-in-circle' ></i>Logout
            </a>
        </div>
    </div>
  </header>
  