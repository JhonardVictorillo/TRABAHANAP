<main class="main-content">
  
    <div class="home-section" id="homeSection">
      <div class="home-hero">
    
        <div class="hero-content">
          <div class="hero-text">
            
          @if(Auth::check())
                <h2>Welcome, {{ Auth::user()->firstname }}, {{ Auth::user()->lastname }}!</h2>
            @else
                <h2>Welcome, Guest!</h2>
            @endif
            <p>
                Explore the dashboard to monitor system activity, manage user accounts, 
                and review reports. Use the sidebar to quickly navigate to your desired section.
            </p>
          </div>
          <div class="hero-illustration">
            
            <img src="admin.png" alt="" />
          </div>
        </div>
      </div>

      
      <div class="calendar-section">
        <h3>Calendar <span>August</span></h3>
        <div class="calendar-grid">
          
          <div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div><div>Sun</div>
      
          <div>29</div><div>30</div><div>31</div><div>1</div><div>2</div><div>3</div><div>4</div>
          <div>5</div><div class="highlight">6</div><div>7</div><div>8</div><div>9</div><div>10</div><div>11</div>
          <div>12</div><div>13</div><div>14</div><div>15</div><div>16</div><div>17</div><div>18</div>
          
        </div>
      </div>
    </div>