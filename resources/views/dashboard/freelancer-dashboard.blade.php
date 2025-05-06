
@include('freelancer.Header')


@include('freelancer.dashboardSection')
 @include('freelancer.appointmentSection')
  @include('freelancer.postSection')
 

           

       <!-- Success message -->
       @if(session('success'))
        <div class="alert alert-success">
        <i class='bx bx-check-circle'></i> <!-- Success icon -->
        {{ session('success') }}
        </div>
        @endif
    


   <script>
    // succes message time duration
    document.addEventListener('DOMContentLoaded', function () {
              const alert = document.querySelector('.alert-success');
              if (alert) {
                  setTimeout(() => {
                      alert.remove();
                  }, 3000); // 3 seconds
              }
          });

    
  const links = document.querySelectorAll('.sidebar-links li a');
const sections = document.querySelectorAll('main > div,.details-section, #appointmentCalendar');
const logoutBtn = document.getElementById('logout-button');
// Section Navigation Handler
links.forEach(link => {
  link.addEventListener('click', function (event) {
    event.preventDefault();

    links.forEach(link => link.classList.remove('active'));
    this.classList.add('active');

    sections.forEach(section => section.style.display = 'none');

    const targetSection = document.querySelector(this.getAttribute('href'));
    if (targetSection) {
      targetSection.style.display = 'block';
      localStorage.setItem('activeSection', this.getAttribute('href'));

      // Calendar specific render fix here (OPTIONAL)
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
window.addEventListener('DOMContentLoaded', () => {
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
});

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

clientsCard.addEventListener('click', () => {
  clientsTable.style.display = 'block';
  appointmentsTable.style.display = 'none';
  reviewsTable.style.display = 'none';
});

appointmentsCard.addEventListener('click', () => {
  clientsTable.style.display = 'none';
  appointmentsTable.style.display = 'block';
  reviewsTable.style.display = 'none';
});

reviewsCard.addEventListener('click', () => {
  clientsTable.style.display = 'none';
  appointmentsTable.style.display = 'none';
  reviewsTable.style.display = 'block';
});


let calendar;
document.addEventListener('DOMContentLoaded', function() {
  

    // FullCalendar Initialization
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
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
        eventClick: function(info) {
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
               
                openAppointmentModal(data,eventId);
                })
                .catch(error => {
                console.error('Error fetching details:', error);
                });
            }
                });

    calendar.render();
});


// // Profile Modal Handling
// const profilePic = document.getElementById('profilePic');
// const profileModal = document.getElementById('profileModal');
// const closeModal = document.getElementById('closeModal');
// const closeModalBtn = document.getElementById('closeModalBtn');

// profilePic.addEventListener('click', () => {
//   profileModal.style.display = 'flex';
// });

// [closeModal, closeModalBtn].forEach(btn => {
//   btn.addEventListener('click', () => {
//     profileModal.style.display = 'none';
//   });
// });

// profileModal.addEventListener('click', (event) => {
//   if (event.target === profileModal) {
//     profileModal.style.display = 'none';
//   }
// });



function formatTime(date) {
  let hours = date.getHours();
  let minutes = date.getMinutes();
  const ampm = hours >= 12 ? 'PM' : 'AM';
  hours = hours % 12 || 12; // 12-hour format
  minutes = minutes < 10 ? '0' + minutes : minutes;
  return `${hours}:${minutes} ${ampm}`;
}


</script>
  <!-- FullCalendar JS -->
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>

    </body>
</html>
     
    