<header class="top-nav">
   

    <div class="user-area">
    <div class="notification">
    <!-- Notification Icon -->
     <button id="admin-notification-icon" class="notification-btn">
                <div class="notif-bell">
                    <span class="material-symbols-outlined">notifications</span>
                    <span id="notification-counter" class="notif-count{{ auth()->user()->unreadNotifications->count() == 0 ? ' hidden' : '' }}">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                </div>
            </button>
            
            <!-- Notification Dropdown -->
          <div id="admin-notification-dropdown" class="notif-dropdown hidden">
    <div class="notif-header">
        <h3>Notifications</h3>
        <button id="refresh-notifications" title="Refresh notifications">
            <span class="material-symbols-outlined">refresh</span>
        </button>
    </div>
    <ul id="admin-notification-list" class="notif-list">
        @forelse(auth()->user()->notifications as $notification)
            <li class="notif-item{{ $notification->read_at ? ' notif-read' : '' }}" data-id="{{ $notification->id }}">
                <div class="notif-content">
                    @php
                        $notificationType = class_basename($notification->type);
                        $iconName = 'notifications'; // Default icon
                        $iconClass = 'default';
                        
                        // Set icon based on notification type
                        if (str_contains($notification->type, 'CategoryRequest')) {
                            $iconName = 'category';
                            $iconClass = isset($notification->data['status']) && $notification->data['status'] == 'approved' ? 'success' : 'danger';
                        } elseif (str_contains($notification->type, 'Post')) {
                            $iconName = 'post_add';
                            $iconClass = 'info';
                        } elseif (str_contains($notification->type, 'User')) {
                            $iconName = 'person';
                            $iconClass = 'primary';
                        }

                        $message = $notification->data['message'] ?? 'New notification';
                    @endphp
                    
                    <div class="notif-icon {{ $iconClass }}">
                        <span class="material-symbols-outlined">{{ $iconName }}</span>
                    </div>
                    
                    <div class="notif-details">
                        <div class="notif-text-container">
                            <p class="notif-preview {{ strlen($message) > 60 ? 'has-more' : '' }}">
                                {{ \Illuminate\Support\Str::limit($message, 60) }}
                            </p>
                            
                            @if(strlen($message) > 60)
                                <div class="notif-full-content" style="display: none;">
                                    <p>{{ $message }}</p>
                                    
                                    @if(isset($notification->data['additional_info']))
                                        <p class="notif-additional-info">{{ $notification->data['additional_info'] }}</p>
                                    @endif
                                </div>
                                
                                <button class="toggle-content-btn" aria-expanded="false">
                                    <span class="expand-text">Read more</span>
                                    <span class="collapse-text" style="display: none;">Show less</span>
                                </button>
                            @endif
                        </div>
                        
                        <small class="notif-time">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                
                @if(!$notification->read_at)
                    <button class="notif-mark-read" data-id="{{ $notification->id }}">
                        Mark as Read
                    </button>
                @else
                    <span class="read-status">Read</span>
                @endif
            </li>
        @empty
            <li class="notif-empty">No notifications found.</li>
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
document.addEventListener('DOMContentLoaded', function() {
    const notificationIcon = document.getElementById('admin-notification-icon');
    const notificationDropdown = document.getElementById('admin-notification-dropdown');
    const markAllReadBtn = document.getElementById('admin-mark-all-read');
    const notificationCounter = document.getElementById('notification-counter');
    const refreshBtn = document.getElementById('refresh-notifications');
    
    // Toggle dropdown when clicking the notification icon
    notificationIcon?.addEventListener('click', function(e) {
        e.stopPropagation();
        notificationDropdown.classList.toggle('hidden');
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (notificationDropdown && !notificationIcon?.contains(event.target) && !notificationDropdown.contains(event.target)) {
            notificationDropdown.classList.add('hidden');
        }
    });
    
    // Make sure we have the CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    }
    
    // Mark single notification as read
    document.querySelectorAll('.notif-mark-read').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const notificationId = this.getAttribute('data-id');
            const listItem = this.closest('li');
            
            console.log('Marking notification as read:', notificationId);
            
            // Use the simpler fetch structure without credentials option
            fetch(`/admin/notifications/${notificationId}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json().catch(e => {
                    console.error('Error parsing JSON:', e);
                    throw new Error('Invalid JSON response');
                });
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Update UI
                    listItem.classList.add('notif-read');
                    this.remove();
                    
                    // Add read indicator
                    const readStatus = document.createElement('span');
                    readStatus.className = 'read-status';
                    readStatus.textContent = 'Read';
                    listItem.querySelector('.notif-content').after(readStatus);
                    
                    // Update counter
                    if (data.unreadCount > 0) {
                        notificationCounter.textContent = data.unreadCount;
                        notificationCounter.classList.remove('hidden');
                    } else {
                        notificationCounter.classList.add('hidden');
                        const footer = document.querySelector('.notif-footer');
                        if (footer) footer.remove();
                    }
                }
            })
            .catch(error => {
                console.error('Error marking notification as read:', error);
            });
        });
    });
    
    // Mark all notifications as read
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function() {
            console.log('Marking all notifications as read');
            
            // Use the simpler fetch structure
            fetch('/admin/notifications/mark-all-as-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json().catch(e => {
                    console.error('Error parsing JSON:', e);
                    throw new Error('Invalid JSON response');
                });
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Update UI for all unread notifications
                    document.querySelectorAll('.notif-item:not(.notif-read)').forEach(item => {
                        item.classList.add('notif-read');
                        const markReadBtn = item.querySelector('.notif-mark-read');
                        if (markReadBtn) markReadBtn.remove();
                        
                        // Add read indicator
                        const readStatus = document.createElement('span');
                        readStatus.className = 'read-status';
                        readStatus.textContent = 'Read';
                        item.querySelector('.notif-content').after(readStatus);
                    });
                    
                    // Hide counter and mark all button
                    notificationCounter.classList.add('hidden');
                    const footer = document.querySelector('.notif-footer');
                    if (footer) footer.remove();
                }
            })
            .catch(error => {
                console.error('Error marking all notifications as read:', error);
            });
        });
    }
    
    // Refresh notifications
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            console.log('Refreshing notifications');
            
            fetch('/admin/notifications', {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json().catch(e => {
                    console.error('Error parsing JSON:', e);
                    throw new Error('Invalid JSON response');
                });
            })
            .then(data => {
                console.log('Fetched notifications:', data);
                
                // Update notification list
                const notificationList = document.getElementById('admin-notification-list');
                
                if (!data.notifications || data.notifications.length === 0) {
                    notificationList.innerHTML = '<li class="notif-empty">No notifications found.</li>';
                    return;
                }
                
                notificationList.innerHTML = ''; // Clear current list
                
                data.notifications.forEach(notification => {
                    // Create new notification item
                    const listItem = document.createElement('li');
                    listItem.className = `notif-item${notification.read_at ? ' notif-read' : ''}`;
                    listItem.setAttribute('data-id', notification.id);
                    
                    // Add notification content
                    let iconName = 'notifications';
                    let iconClass = 'default';
                    
                    // Set icon based on notification type
                    if (notification.type.includes('CategoryRequest')) {
                        iconName = 'category';
                        iconClass = notification.data.status === 'approved' ? 'success' : 'danger';
                    } else if (notification.type.includes('Post')) {
                        iconName = 'post_add';
                        iconClass = 'info';
                    } else if (notification.type.includes('User')) {
                        iconName = 'person';
                        iconClass = 'primary';
                    }
                    
                    const message = notification.data && notification.data.message ? notification.data.message : 'New notification';
                    const hasMore = message.length > 60;
                    
                    listItem.innerHTML = `
                        <div class="notif-content">
                            <div class="notif-icon ${iconClass}">
                                <span class="material-symbols-outlined">${iconName}</span>
                            </div>
                            <div class="notif-details">
                                <div class="notif-text-container">
                                    <p class="notif-preview ${hasMore ? 'has-more' : ''}">
                                        ${hasMore ? message.substring(0, 60) + '...' : message}
                                    </p>
                                    ${hasMore ? `
                                    <div class="notif-full-content" style="display: none;">
                                        <p>${message}</p>
                                        ${notification.data && notification.data.additional_info ? `<p class="notif-additional-info">${notification.data.additional_info}</p>` : ''}
                                    </div>
                                    <button class="toggle-content-btn" aria-expanded="false">
                                        <span class="expand-text">Read more</span>
                                        <span class="collapse-text" style="display: none;">Show less</span>
                                    </button>` : ''}
                                </div>
                                <small class="notif-time">${timeAgo(notification.created_at)}</small>
                            </div>
                        </div>
                        ${!notification.read_at ? 
                            `<button class="notif-mark-read" data-id="${notification.id}">Mark as Read</button>` : 
                            '<span class="read-status">Read</span>'}
                    `;
                    
                    notificationList.appendChild(listItem);
                });
                
                // Update counter
                if (data.unreadCount > 0) {
                    notificationCounter.textContent = data.unreadCount;
                    notificationCounter.classList.remove('hidden');
                    
                    // Add mark all as read button if not present
                    if (!document.querySelector('.notif-footer')) {
                        const footer = document.createElement('div');
                        footer.className = 'notif-footer';
                        footer.innerHTML = '<button id="admin-mark-all-read" class="notif-mark-all">Mark All as Read</button>';
                        notificationDropdown.appendChild(footer);
                        
                        // Re-attach event listener to new button
                        document.getElementById('admin-mark-all-read').addEventListener('click', function() {
                            fetch('/admin/notifications/mark-all-as-read', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => {
                                return response.json().catch(e => {
                                    throw new Error('Invalid JSON response');
                                });
                            })
                            .then(data => {
                                if (data.success) {
                                    // Update UI for all unread notifications
                                    document.querySelectorAll('.notif-item:not(.notif-read)').forEach(item => {
                                        item.classList.add('notif-read');
                                        const markReadBtn = item.querySelector('.notif-mark-read');
                                        if (markReadBtn) markReadBtn.remove();
                                        
                                        // Add read indicator
                                        const readStatus = document.createElement('span');
                                        readStatus.className = 'read-status';
                                        readStatus.textContent = 'Read';
                                        item.querySelector('.notif-content').after(readStatus);
                                    });
                                    
                                    // Hide counter and mark all button
                                    notificationCounter.classList.add('hidden');
                                    const footer = document.querySelector('.notif-footer');
                                    if (footer) footer.remove();
                                }
                            })
                            .catch(error => {
                                console.error('Error marking all notifications as read:', error);
                            });
                        });
                    }
                } else {
                    notificationCounter.classList.add('hidden');
                    const footer = document.querySelector('.notif-footer');
                    if (footer) footer.remove();
                }
                
                // Re-attach event listeners to new elements
                addNotificationEventListeners();
            })
            .catch(error => {
                console.error('Error refreshing notifications:', error);
            });
        });
    }
    
    // Set up expandable notifications
    setupExpandableNotifications();
    
    // Helper function to format time ago
    function timeAgo(dateString) {
        const date = new Date(dateString);
        const seconds = Math.floor((new Date() - date) / 1000);
        
        let interval = Math.floor(seconds / 31536000);
        if (interval >= 1) return interval + (interval === 1 ? ' year ago' : ' years ago');
        
        interval = Math.floor(seconds / 2592000);
        if (interval >= 1) return interval + (interval === 1 ? ' month ago' : ' months ago');
        
        interval = Math.floor(seconds / 86400);
        if (interval >= 1) return interval + (interval === 1 ? ' day ago' : ' days ago');
        
        interval = Math.floor(seconds / 3600);
        if (interval >= 1) return interval + (interval === 1 ? ' hour ago' : ' hours ago');
        
        interval = Math.floor(seconds / 60);
        if (interval >= 1) return interval + (interval === 1 ? ' minute ago' : ' minutes ago');
        
        return Math.floor(seconds) + ' seconds ago';
    }
    
    // Function to set up expandable notifications
    function setupExpandableNotifications() {
        document.querySelectorAll('.toggle-content-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                
                const container = this.closest('.notif-text-container');
                const preview = container.querySelector('.notif-preview');
                const fullContent = container.querySelector('.notif-full-content');
                const expandText = this.querySelector('.expand-text');
                const collapseText = this.querySelector('.collapse-text');
                
                // Toggle expanded state
                if (this.getAttribute('aria-expanded') === 'false') {
                    preview.style.display = 'none';
                    fullContent.style.display = 'block';
                    expandText.style.display = 'none';
                    collapseText.style.display = 'inline';
                    this.setAttribute('aria-expanded', 'true');
                } else {
                    preview.style.display = 'block';
                    fullContent.style.display = 'none';
                    expandText.style.display = 'inline';
                    collapseText.style.display = 'none';
                    this.setAttribute('aria-expanded', 'false');
                }
            });
        });
    }
    
    // Function to add event listeners after dynamic content updates
    function addNotificationEventListeners() {
        // Attach mark as read handlers
        document.querySelectorAll('.notif-mark-read').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const notificationId = this.getAttribute('data-id');
                const listItem = this.closest('li');
                
                console.log('Adding listener for notification:', notificationId);
                
                fetch(`/admin/notifications/${notificationId}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    return response.json().catch(e => {
                        throw new Error('Invalid JSON response');
                    });
                })
                .then(data => {
                    if (data.success) {
                        // Update UI
                        listItem.classList.add('notif-read');
                        this.remove();
                        
                        // Add read indicator
                        const readStatus = document.createElement('span');
                        readStatus.className = 'read-status';
                        readStatus.textContent = 'Read';
                        listItem.querySelector('.notif-content').after(readStatus);
                        
                        // Update counter
                        if (data.unreadCount > 0) {
                            notificationCounter.textContent = data.unreadCount;
                            notificationCounter.classList.remove('hidden');
                        } else {
                            notificationCounter.classList.add('hidden');
                            const footer = document.querySelector('.notif-footer');
                            if (footer) footer.remove();
                        }
                    }
                })
                .catch(error => {
                    console.error('Error marking notification as read:', error);
                });
            });
        });
        
        // Setup expandable notifications
        setupExpandableNotifications();
    }
});
// Handle search input
const searchInput = document.querySelector('input[name="search"]');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        if (this.value === '') {
            this.form.submit();
        }
    });
}

</script>