
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

               if (this.getAttribute('href') === '#appointmentCalendar') {
                  setTimeout(() => {
                      if (typeof window.calendar !== 'undefined' && window.calendar && typeof window.calendar.render === 'function') {
                          window.calendar.render();
                          window.calendar.updateSize();
                      } else if (typeof initializeCalendar === 'function') {
                          initializeCalendar();
                      }
                  }, 100);
              }
              
            }
        });
    });

    // Restore active section on page load
    const activeSectionId = localStorage.getItem('activeSection') || '#dashboardSection';
    const activeLink = document.querySelector(`.sidebar-links li a[href="${activeSectionId}"]`);

    links.forEach(link => link.classList.remove('active'));
    sections.forEach(section => section.style.display = 'none');

  const sectionToShow = document.querySelector(activeSectionId);
if (sectionToShow) {
    sectionToShow.style.display = 'block';

    // If appointment section, initialize calendar after showing it
    if (activeSectionId === '#appointmentCalendar') {
        setTimeout(() => {
            if (typeof window.calendar !== 'undefined' && window.calendar && typeof window.calendar.render === 'function') {
                window.calendar.render();
                window.calendar.updateSize();
            } else if (typeof initializeCalendar === 'function') {
                initializeCalendar();
            }
        }, 100);
    }
}

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

// spinner button functionality
document.addEventListener('DOMContentLoaded', function () {
  // For all forms in modals and main sections
  document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
      const submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn) {
        // Find spinner and text
        const btnText = submitBtn.querySelector('.btn-text');
        const btnSpinner = submitBtn.querySelector('.btn-spinner');
        // Disable button and show spinner
        submitBtn.disabled = true;
        submitBtn.classList.add('disabled');
        if (btnText) btnText.style.display = 'none';
        if (btnSpinner) btnSpinner.style.display = 'inline-block';
      }
    });
  });
});

function showSpinnerOnButton(button) {
  const btnText = button.querySelector('.btn-text');
  const btnSpinner = button.querySelector('.btn-spinner');
  button.disabled = true;
  button.classList.add('disabled');
  if (btnText) btnText.style.display = 'none';
  if (btnSpinner) btnSpinner.style.display = 'inline-block';
}

function restoreButton(button, text) {
  const btnText = button.querySelector('.btn-text');
  const btnSpinner = button.querySelector('.btn-spinner');
  button.disabled = false;
  button.classList.remove('disabled');
  if (btnText) btnText.style.display = 'inline-block';
  if (btnSpinner) btnSpinner.style.display = 'none';
  if (btnText && text) btnText.textContent = text;
}

</script>
  <!-- FullCalendar JS -->
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>

    </body>
</html>
     
    