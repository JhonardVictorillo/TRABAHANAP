<!-- 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Freelancer Dashboard</title>
    <link rel="stylesheet" href="{{asset ('css/freelancer.css')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="preload" href="https://fonts.gstatic.com/s/materialsymbolsoutlined/v226/kJF1BvYX7BgnkSrUwT8OhrdQw4oELdPIeeII9v6oDMzByHX9rA6RzaxHMPdY43zj-jCxv3fzvRNU22ZXGJpEpjC_1v-p_4MrImHCIJIZrDCvHOej.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
  <body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" class="profile-picture">
            <a href="#" id ="profile-link" class="profile-link">
            <h3>{{ Auth::user()->firstname }}</h3>
            </a>
            <span>{{ Auth::user()->role }}</span>
        </div>
        <ul class="sidebar-links">
        <li>
            <a href="" id="dashboard-link" class="">
                <span class="material-symbols-outlined">dashboard</span>Dashboard
            </a>
        </li>
        <li>
            <a href="#"  id="post-link"      class="">
                <span class="material-symbols-outlined">post_add</span>Post
            </a>
        </li>
        <li>
            <a href=""  id="appointment-link" class="">
                <span class="material-symbols-outlined">event</span>Appointment
                @if($notifications->count() > 0)
                <span class="notification-count">{{ $notifications->count() }}</span>
                @endif
            </a>
        </li>
        <li>
            <a href=""  id="message-link" class="">
                <span class="material-symbols-outlined">chat</span>Messages <span class="message-count">1</span>
            </a>
        </li>
        <li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="#" id ="logout-button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="material-symbols-outlined">logout</span>Logout
            </a>
        </li>
    </ul>
    </aside>
    <main class="main-content">

   -->


   <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Freelancer Dashboard</title>
  
  <link  rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"/>
  <link rel="stylesheet"href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/>
  <link rel="stylesheet" href="{{asset ('css/NewFreelancer.css')}}" />
  <!-- FullCalendar CSS -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
   <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"rel="stylesheet"/>
 <!-- FullCalendar CSS -->
 <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
 <!-- Tailwind CSS (Optional for better styling) -->
 <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-header">
        <h2 class="logo-header">
            <span class="logo-icon">MG</span>
            Mingla<span class="brand-span">Gawa</span>
        </h2>          
    </div>
    <ul class="sidebar-links">
      <li>
        <a href="#profileSection" id="profileLink" class="active"><span class="material-symbols-outlined">person</span>Profile</a>
      </li>
      <li>
        <a href="#dashboardSection" id="dashboardLink"><span class="material-symbols-outlined">dashboard</span>Dashboard</a>
      </li>
      <li>
        <a href="#appointmentCalendar" id="appointmentLink"><span class="material-symbols-outlined">calendar_today</span>Appointment</a>
      </li>
      <li>
        <a href="#postContainer" id="postLink"><span class="material-symbols-outlined">person</span>Post</a>
      </li>
       <li>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="#" id ="logout-button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="material-symbols-outlined">logout</span>Logout
            </a>
      </li>
    </ul>
  </aside>

  <header class="top-nav">
    <div class="search-area">
      <span class="material-symbols-outlined">search</span>
      <input type="text" placeholder="Search..." />
    </div>

    <div class="user-area">
      <div class="notification">
        <span class="material-symbols-outlined">notifications</span>
        <span class="count">3</span>
      </div>
      <div class="message">
        <span class="material-symbols-outlined">email</span>
        <span class="count">5</span>
      </div>
      <!-- Profile Picture in Header -->
       <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="User Avatar" class="avatar cursor-pointer" id="profilePic" />
      <div class="user-info">
        <span class="user-name">{{ Auth::user()->firstname }}</span>
        <p class="freelancer">{{ Auth::user()->role }}</p>
    </div>
    
    </div>
  </header>
