


    <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  
  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
  />

  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
  />
  
  <link rel="stylesheet" href="{{asset('css/newAdmin.css')}}" />
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
        <a href="#" id="homeLink" class="active"><span class="material-symbols-outlined">home</span>Home</a>
      </li>
      <li>
        <a href="#" id="dashboardLink"><span class="material-symbols-outlined">dashboard</span>Dashboard</a>
      </li>
       
      <li>
        <a href="#" id="categoriesLink"><span class="material-symbols-outlined">category</span>Categories</a>
      </li>
   
      <li>
      <a href="#bookingsSection" id="bookingsLink"><span class="material-symbols-outlined">event_note</span>Bookings</a>
    </li>
    <li>
      <a href="#violationsSection" id="violationsLink"><span class="material-symbols-outlined">report_problem</span>Violations</a>
    </li>
    <li>
      <a href="#userStatsSection" id="userStatsLink"><span class="material-symbols-outlined">bar_chart</span>User Stats</a>
    </li>
    <li>
    <a href="#revenueSection" id="revenueLink"><span class="material-symbols-outlined">payments</span><span>Revenue</span></a>
  </li>
    <li>
        <a href="#"><span class="material-symbols-outlined">account_circle</span>Profile</a>
      </li>
      <li>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
           
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="material-symbols-outlined">logout</span>Logout</a>
      </li>
    </ul>
  </aside>
