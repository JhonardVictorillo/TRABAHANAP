
@include('freelancer.Header')


@include('freelancer.dashboardSection')
 @include('freelancer.appointmentSection')
  @include('freelancer.postSection')
 

           

          <!-- Success message -->
    <!-- @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif -->

       
    


   <script>
    let calendar;
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


// Profile Modal Handling
const profilePic = document.getElementById('profilePic');
const profileModal = document.getElementById('profileModal');
const closeModal = document.getElementById('closeModal');
const closeModalBtn = document.getElementById('closeModalBtn');

profilePic.addEventListener('click', () => {
  profileModal.style.display = 'flex';
});

[closeModal, closeModalBtn].forEach(btn => {
  btn.addEventListener('click', () => {
    profileModal.style.display = 'none';
  });
});

profileModal.addEventListener('click', (event) => {
  if (event.target === profileModal) {
    profileModal.style.display = 'none';
  }
});



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
     
    <!-- <script>
       
       document.addEventListener("DOMContentLoaded", function () {
    // Manage visibility of sections
    const sections = {
        dashboard: document.getElementById("dashboard-overview"),
        posts: document.getElementById("posts-section"),
        appointments: document.getElementById("appointments-section"),
        messages: document.getElementById("messages-section"),
        profile: document.getElementById("profile-section")
    };

    const links = {
        dashboard: document.getElementById("dashboard-link"),
        posts: document.getElementById("post-link"),
        appointments: document.getElementById("appointment-link"),
        messages: document.getElementById("message-link"),
        profile: document.getElementById("profile-link")
    };

    // Function to show a section
    function showSection(sectionKey) {
        Object.values(sections).forEach((section) => (section.style.display = "none"));
        if (sections[sectionKey]) {
            sections[sectionKey].style.display = "block";
        }
    }

    // Initially hide all sections
    Object.values(sections).forEach((section) => (section.style.display = "none"));

    // Get the last active section from local storage or default to dashboard
    const lastActiveSection = localStorage.getItem("activeSection") || "dashboard";
    showSection(lastActiveSection);

    // Add click event listeners for section links
    Object.keys(links).forEach((key) => {
        links[key].addEventListener("click", function (event) {
            event.preventDefault();
            localStorage.setItem("activeSection", key); // Save the active section
            showSection(key);
        });
    });

    // Listen for form submissions to save the active section
    const forms = document.querySelectorAll("form");
    forms.forEach((form) => {
        form.addEventListener("submit", function () {
            const currentSection = Object.keys(sections).find(
                (key) => sections[key].style.display === "block"
            );
            if (currentSection) {
                localStorage.setItem("activeSection", currentSection); // Save the active section before submission
            }
        });
    });

    // Function to handle logout
    const logoutButton = document.getElementById("logout-button"); // Replace with your actual logout button's ID
    if (logoutButton) {
        logoutButton.addEventListener("click", function () {
            localStorage.removeItem("activeSection"); // Clear the active section from local storage
            // Optionally, redirect to the login page or home page
            window.location.href = "/login"; // Update the URL to match your application's login page
        });
    }
// });


            
        

     
  //************************* */ post parts javascript*********************
     
  
            const createPostButton = document.querySelector(".new-post-btn");
            const createPostForm = document.getElementById("create-post-form");

            // Toggle the visibility of the create post form
            createPostButton.addEventListener("click", function() {
                if (createPostForm.style.display === "none" || createPostForm.style.display === "") {
                    createPostForm.style.display = "block"; // Show the form
                } else {
                    createPostForm.style.display = "none"; // Hide the form
                }
            });

            // Add another sub-service input
            document.getElementById('add-sub-service').addEventListener('click', function () {
            const container = document.getElementById('sub-services-container');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'sub_services[]'; // Ensure this is the correct name
            input.placeholder = 'Sub-Service';
            input.required = true; // Ensure the field is required
            container.appendChild(input);
        });
            

        document.getElementById("create-post-form").addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent default submission for debugging
            const formData = new FormData(this);
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }
            this.submit(); // Re-enable submission after debugging
        });
            // Reset the form fields on submission
            createPostForm.addEventListener('submit', function(event) {
                // Reset sub-services container to one input
                resetFormFields();
                // Optionally, you can hide the form after submission
                createPostForm.style.display = "none"; // Hide the form after submission
            });


        // Function to reset the form fields
        function resetFormFields() {
            const subServicesContainer = document.getElementById('sub-services-container');
            subServicesContainer.innerHTML = '<input type="text" name="sub_services[]" placeholder="Sub-Service" required>';
            
            const recentWorksContainer = document.getElementById('recent-works-container');
            recentWorksContainer.innerHTML = '<input type="file" name="recent_works[]" accept="image/*" required>';
        }


        // succes message time duration
      
            const alert = document.querySelector('.alert-success');
            if (alert) {
                setTimeout(() => {
                    alert.remove();
                }, 3000); // 3 seconds
            }
    

    ///////////// appointment view details

    const viewButtons = document.querySelectorAll('.view-details');
    const modal = document.getElementById('appointmentDetailsModal');
    const closeModal = document.getElementById('closeModal');

    viewButtons.forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('modalCustomer').textContent = this.dataset.customer;
            document.getElementById('modalDate').textContent = this.dataset.date;
            document.getElementById('modalTime').textContent = this.dataset.time;
            document.getElementById('modalContact').textContent = this.dataset.contact;
            document.getElementById('modalNotes').textContent = this.dataset.notes;
            document.getElementById('modalStatus').textContent = this.dataset.status;
            modal.style.display = 'flex';
        });
    });

    closeModal.addEventListener('click', function () {
        modal.style.display = 'none';
    });

        // Function to close the form and reset fields
        function closeForm() {
            const createPostForm = document.getElementById("create-post-form");
            createPostForm.style.display = 'none'; // Hide the form
            resetFormFields(); // Reset the form fields
        }
  
       //////// edit post modal javascripts ///////////////////////
       
       
          document.addEventListener('click', function (event) {
    if (event.target.matches('.edit-post')) {
        const postId = event.target.dataset.postId;
        console.log('Edit Post button clicked, Post ID:', postId);
        
        fetch(`/posts/${postId}/edit`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to fetch post data');
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
                    alert('Error fetching post data: ' + data.error);
                    return;
                }
        const post = data.post;

         // Update the form action URL dynamically
         const editForm = document.getElementById('edit-post-form');
                editForm.action = `/posts/${postId}`;

        document.getElementById('edit-post-id').value = post.id;
        document.getElementById('edit-description').value = post.description;

        const subServicesContainer = document.getElementById('edit-sub-services-container');
        subServicesContainer.innerHTML = '';

        (data.sub_services || []).forEach(service => {
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'sub_services[]';
            input.value = service;
            subServicesContainer.appendChild(input);
        });

        openEditPostModal();
    })
    .catch(error => {
        console.error('Error fetching post data:', error);
        let customAlert = 'An error occurred while fetching the post data.';
        alert(customAlert);
    });

            function openEditPostModal() {
                console.log("Opening Edit Post Modal");
                document.getElementById('edit-post-modal').style.display = 'block';
                document.getElementById('modal-backdrop').style.display = 'block';
            }

            function closeEditPostModal() {
                document.getElementById('edit-post-modal').style.display = 'none';
                document.getElementById('modal-backdrop').style.display = 'none';
            }
             // Attach event listeners to Cancel and Close buttons
    document.querySelector('.close-btn').addEventListener('click', closeEditPostModal);
    document.querySelector('#edit-post-modal button[type="button"]').addEventListener('click', closeEditPostModal);
        }
    })

    /////******************post notification script*********************************///////////////////
    const toggleBtn = document.getElementById("toggle-notifications");
    const notificationContainer = document.getElementById("notification-container");
    const dismissBtn = document.getElementById("dismiss-button");

    if (toggleBtn && notificationContainer) {
        toggleBtn.addEventListener("click", function (event) {
            event.stopPropagation(); // Prevent the click from closing it immediately
            notificationContainer.classList.toggle("show"); // Use CSS class instead of inline style
        });

        dismissBtn?.addEventListener("click", function () {
            notificationContainer.classList.remove("show");
        });

        // Close dropdown when clicking outside
        document.addEventListener("click", function (event) {
            if (!toggleBtn.contains(event.target) && !notificationContainer.contains(event.target)) {
                notificationContainer.classList.remove("show");
            }
        });
    } else {
        console.error("Notification elements not found.");
    }
 /////////////////////***********//notification count refresh dynamically**********************///
            // Dismiss all notifications
    document.getElementById('dismiss-button')?.addEventListener('click', function () {
        fetch('/freelancer/notifications/read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update notification count
                let notificationCount = document.getElementById('notification-count');
                if (notificationCount) {
                    notificationCount.textContent = data.unread_count > 0 ? data.unread_count : '';
                }

                // Mark all notifications as read in UI
                document.querySelectorAll('.notification-message.unread').forEach(notification => {
                    notification.classList.remove('unread');
                    notification.classList.add('read');
                });
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Mark individual notification as read
    document.addEventListener('click', function (event) {
    if (event.target.classList.contains('mark-as-read')) {
        let notificationMessage = event.target.closest('.notification-message');
        let notificationId = notificationMessage.getAttribute('data-id');

        fetch(`/freelancer/notifications/read/${notificationId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                notificationMessage.classList.remove('unread');
                notificationMessage.classList.add('read');

                // Update notification count
                let notificationCount = document.getElementById('notification-count');
                if (notificationCount) {
                    let currentCount = parseInt(notificationCount.textContent);
                    if (currentCount > 1) {
                        notificationCount.textContent = currentCount - 1;
                    } else {
                        notificationCount.remove();
                    }
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }
});
 //****************************Post Deletion scrip AJAX*************************8/////
 $(document).ready(function () {
    $(".delete-post").click(function () {
        let postId = $(this).data("post-id");
        let button = $(this).closest(".freelancer-card");

        if (confirm("Are you sure you want to delete this post?")) {
            button.remove();
            $.ajax({
                url: `/posts/${postId}`, // Corrected route
                type: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") // Send CSRF token in headers
                },
                success: function (response) {
                    console.log(response.success); // Log success for debugging
                },
                error: function (xhr) {
                    alert(xhr.responseJSON.error);
                    location.reload();
                }
            });
        }
    });
});


    })
    
    </script> -->













