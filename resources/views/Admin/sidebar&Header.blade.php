<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="{{asset('css/admin.css')}}">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head> 
<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>trabahanap.</h2>
        </div>
        <ul class="sidebar-links">
            <h4><span>Main Menu</span></h4>
            <li>
                <a href=""  id="dashboard-link"><span class="material-symbols-outlined">dashboard</span>Dashboard</a>
            </li>
            <li>
                <a href=""  id="users-link"><span class="material-symbols-outlined">group</span>Users</a>
            </li>
            <li>
                <a href="" id="categories-link"><span class="material-symbols-outlined">category</span>Categories</a>
            </li>
            <li>
            <a href="" id="posts-link"><span class="material-symbols-outlined">post_add</span>Posts</a>
           </li>

           
            <h4><span>Account</span></h4>
            <li>
                <a href="#" ><span class="material-symbols-outlined">account_circle</span>Profile</a>
            </li>
            <li>
                <a href="#" ><span class="material-symbols-outlined">settings</span>Settings</a>
            </li>
            <li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="#"   id="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="material-symbols-outlined">logout</span>Logout
                </a>
            </li>
        </ul>
        <div class="user-account">
            <div class="user-profile">
                <div class="user-detail">
                    <h3> Hi,  {{ Auth::user()->firstname }}</h3>
                    <span>{{ Auth::user()->role }}</span>
                </div>
            </div>
        </div>
    </aside> -->


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
        <a href="#"><span class="material-symbols-outlined">flag</span>All Reports</a>
      </li>
    
      <li>
        <a href="#"><span class="material-symbols-outlined">account_circle</span>Profile</a>
      </li>
      <!-- <li>
        <a href="#"><span class="material-symbols-outlined">settings</span>Settings</a>
      </li> -->
      <li>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
           
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="material-symbols-outlined">logout</span>Logout</a>
      </li>
    </ul>
  </aside>
