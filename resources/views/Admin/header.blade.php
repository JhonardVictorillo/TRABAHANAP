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
      <img src="mth.jpg" alt="User Avatar" class="avatar" />
      <span class="user-name"> {{ Auth::user()->firstname }}</span>
    </div>
  </header>
  
