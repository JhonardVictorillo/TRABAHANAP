<header class="top-nav">
   

    <div class="user-area">
    <div class="notification">
    <!-- Notification Icon -->
    <button id="admin-notification-icon" class="notification-btn">
        <div class="notif-bell">
            <span class="material-symbols-outlined">notifications</span>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="notif-count">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>
            @endif
        </div>
    </button>
    <!-- Notification Dropdown -->
    <div id="admin-notification-dropdown" class="notif-dropdown hidden">
        <div class="notif-header">
            <h3>Notifications</h3>
        </div>
        <ul id="admin-notification-list" class="notif-list">
            @forelse(auth()->user()->notifications as $notification)
                <li class="notif-item{{ $notification->read_at ? ' notif-read' : '' }}">
                    <div>
                        <p class="notif-msg{{ $notification->read_at ? ' notif-msg-read' : '' }}">
                            {{ $notification->data['message'] ?? 'You have a new notification.' }}
                        </p>
                        <p class="notif-time">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    @if(!$notification->read_at)
                        <button
                            class="notif-mark-read"
                            data-id="{{ $notification->id }}"
                        >
                            Mark as Read
                        </button>
                    @endif
                </li>
            @empty
                <li class="notif-item notif-empty">No notifications.</li>
            @endforelse
        </ul>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <div class="notif-footer">
                <button id="admin-mark-all-read" class="notif-mark-all">
                    Mark All as Read
                </button>
            </div>
        @endif
    </div>
</div>
      <div class="message">
        <span class="material-symbols-outlined">email</span>
        <span class="count">5</span>
      </div>
      @if(Auth::check())
    <img  src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/defaultprofile.jpg') }}"  alt="User Avatar" class="avatar" />
    <span class="user-name">{{ Auth::user()->firstname }}</span>
    @else
    <img src="{{ asset('images/defaultprofile.jpg') }}" alt="Default Avatar" class="avatar" />
    <span class="user-name">Guest</span>
    @endif
  </header>
  

  <script>
document.addEventListener('DOMContentLoaded', function () {
    const icon = document.getElementById('admin-notification-icon');
    const dropdown = document.getElementById('admin-notification-dropdown');
    icon.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdown.classList.toggle('hidden');
    });
    document.addEventListener('click', function() {
        dropdown.classList.add('hidden');
    });
});

document.querySelector('input[name="search"]').addEventListener('input', function() {
    if (this.value === '') {
        this.form.submit();
    }
});


</script>