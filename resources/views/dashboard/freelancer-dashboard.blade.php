
@include('freelancer.Header')

 
  @if(!$user->profile_completed)
     @php
        $categories = \App\Models\Category::all();
    @endphp
        @include('completeProfile.freelancerCompleteProfile')
    @endif
@include('freelancer.dashboardSection')
 @include('freelancer.appointmentSection')
 @include('freelancer.rescheduleSection')
 @include('freelancer.revenueSection')
  @include('freelancer.postSection')
  
   @include('successMessage')


    @if(!$user->profile_completed)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show the modal when the page loads for users with incomplete profiles
        document.getElementById('completeAccountModal').style.display = 'flex';
    });
</script>
@endif


   <script>
    document.addEventListener('DOMContentLoaded', function () {
    // FullCalendar Initialization
    const calendarEl = document.getElementById('calendar');
    let calendar;
    if (calendarEl) {
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'en',
            height: 600,
            selectable: true,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            events: '/freelancer/appointments', // Route returning JSON
            eventClick: function (info) {
                const eventId = info.event.id;
                console.log('Clicked Event ID:', eventId);

                fetch(`/freelancer/appointments/${eventId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Data fetched:', data);
                        openAppointmentModal(data, eventId);
                    })
                    .catch(error => {
                        console.error('Error fetching details:', error);
                    });
            }
        });

        calendar.render();
    }

   
    const links = document.querySelectorAll('.sidebar-links li a');
    const sections = document.querySelectorAll('main > div, .details-section, #appointmentCalendar, #rescheduleSection, #revenueSection');
    const logoutBtn = document.getElementById('logout-button');

    // Section Navigation Handler
    links.forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault();

            // Remove 'active' class from all links
            links.forEach(link => link.classList.remove('active'));
            this.classList.add('active');

            // Hide all sections
            sections.forEach(section => section.style.display = 'none');

            // Show the target section
            const targetSection = document.querySelector(this.getAttribute('href'));
            if (targetSection) {
                targetSection.style.display = 'block';
                localStorage.setItem('activeSection', this.getAttribute('href'));

                // Calendar specific render fix (if applicable)
               
                if (this.getAttribute('href') === '#appointmentCalendar') {
                   
                    setTimeout(() => {
                        calendar.render();
                        calendar.updateSize();
                    }, 0);
                }
            }
        });
    });

    // Restore active section on page load
    const activeSectionId = localStorage.getItem('activeSection') || '#profileSection';
    const activeLink = document.querySelector(`.sidebar-links li a[href="${activeSectionId}"]`);

    links.forEach(link => link.classList.remove('active'));
    sections.forEach(section => section.style.display = 'none');

    const sectionToShow = document.querySelector(activeSectionId);
    if (sectionToShow) sectionToShow.style.display = 'block';

    if (activeLink) {
        activeLink.classList.add('active');
    } else {
        document.getElementById('profileLink').classList.add('active');
    }

    // Clear localStorage on logout
    logoutBtn.addEventListener('click', () => {
        localStorage.removeItem('activeSection');
    });

    // Cards click handler to toggle tables
    const clientsCard = document.getElementById('clientsCard');
    const appointmentsCard = document.getElementById('appointmentsCard');
    const reviewsCard = document.getElementById('reviewsCard');
    const clientsTable = document.getElementById('clientsTable');
    const appointmentsTable = document.getElementById('appointmentsTable');
    const reviewsTable = document.getElementById('reviewsTable');

    if (clientsCard) {
        clientsCard.addEventListener('click', () => {
            clientsTable.style.display = 'block';
            appointmentsTable.style.display = 'none';
            reviewsTable.style.display = 'none';
        });
    }

    if (appointmentsCard) {
        appointmentsCard.addEventListener('click', () => {
            clientsTable.style.display = 'none';
            appointmentsTable.style.display = 'block';
            reviewsTable.style.display = 'none';
        });
    }

    if (reviewsCard) {
        reviewsCard.addEventListener('click', () => {
            clientsTable.style.display = 'none';
            appointmentsTable.style.display = 'none';
            reviewsTable.style.display = 'block';
        });
    }

    
});

// Format time utility function
function formatTime(date) {
    let hours = date.getHours();
    let minutes = date.getMinutes();
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12 || 12; // 12-hour format
    minutes = minutes < 10 ? '0' + minutes : minutes;
    return `${hours}:${minutes} ${ampm}`;
}


document.addEventListener('DOMContentLoaded', function() {
  // Create mobile menu toggle button
  const topNav = document.querySelector('.top-nav');
  if (topNav) {
    const toggleBtn = document.createElement('button');
    toggleBtn.className = 'mobile-menu-toggle';
    toggleBtn.innerHTML = '<span class="material-symbols-outlined">menu</span>';
    toggleBtn.setAttribute('aria-label', 'Toggle menu');
    
    // Insert toggle button as first child of top-nav
    topNav.insertBefore(toggleBtn, topNav.firstChild);
  }
  
  // Create overlay element for mobile
  const overlay = document.createElement('div');
  overlay.className = 'sidebar-overlay';
  document.body.appendChild(overlay);
  
  // Add click event to toggle button
  document.addEventListener('click', function(event) {
    if (event.target.closest('.mobile-menu-toggle')) {
      const sidebar = document.querySelector('.sidebar');
      sidebar.classList.toggle('active');
      document.body.classList.toggle('sidebar-active');
    }
  });
  
  // Close sidebar when clicking overlay
  overlay.addEventListener('click', function() {
    document.querySelector('.sidebar').classList.remove('active');
    document.body.classList.remove('sidebar-active');
  });
  
  // Handle window resize
  window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
      document.querySelector('.sidebar').classList.remove('active');
      document.body.classList.remove('sidebar-active');
    }
  });
  
  // Close sidebar when clicking links on mobile
  const sidebarLinks = document.querySelectorAll('.sidebar-links a');
  sidebarLinks.forEach(link => {
    link.addEventListener('click', function() {
      if (window.innerWidth <= 768) {
        document.querySelector('.sidebar').classList.remove('active');
        document.body.classList.remove('sidebar-active');
      }
    });
  });
});

</script>
  <!-- FullCalendar JS -->
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>

    </body>
</html>
     
    