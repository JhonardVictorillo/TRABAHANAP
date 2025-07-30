<section id="rescheduleSection" class="details-section py-6">
    <div class="container mx-auto space-y-6">
        <!-- New Calendar View for easier date selection -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Availability Calendar</h3>
            <p class="text-sm text-gray-600 mb-4">Click on a date to add or edit availability</p>
            
            <!-- Month Navigation -->
            <div class="flex justify-between items-center mb-4">
                <button id="prevMonth" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <h4 id="currentMonthDisplay" class="text-md font-medium">June 2023</h4>
                <button id="nextMonth" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
            
            <!-- Calendar Grid -->
         <div class="calendar-container overflow-hidden">
                <!-- Days of week header -->
                 <div class="calendar-header grid grid-cols-7 gap-1 mb-1 text-center">
                    <div class="text-sm font-medium text-gray-600">S</div>
                    <div class="text-sm font-medium text-gray-600">M</div>
                    <div class="text-sm font-medium text-gray-600">T</div>
                    <div class="text-sm font-medium text-gray-600">W</div>
                    <div class="text-sm font-medium text-gray-600">T</div>
                    <div class="text-sm font-medium text-gray-600">F</div>
                    <div class="text-sm font-medium text-gray-600">S</div>
                </div>
                
                <!-- Calendar days - will be populated by JavaScript -->
                   <div id="calendarDays" class="grid grid-cols-7 gap-1"></div>
                </div>
            
            <!-- Legend -->
            <div class="flex items-center gap-4 mt-4 text-sm">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-100 border border-green-400 rounded mr-2"></div>
                    <span>Available</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-100 border border-blue-400 rounded mr-2"></div>
                    <span>Today</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-gray-100 border border-gray-300 rounded mr-2"></div>
                    <span>Unavailable</span>
                </div>
            </div>
        </div>
        
        <div class="flex flex-wrap lg:flex-nowrap gap-6">
            <!-- Add Availability Form -->
            <div class="w-full lg:w-1/2 bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-6 text-gray-800">Add Availability</h3>
                <form method="POST" action="{{ route('freelancer.setAvailability') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Select Date:</label>
                        <input type="date" name="date" id="date" class="w-full mt-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" required>
                    </div>
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time:</label>
                        <input type="time" name="start_time" id="start_time" class="w-full mt-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" required>
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700">End Time:</label>
                        <input type="time" name="end_time" id="end_time" class="w-full mt-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" required>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                        Save Availability
                    </button>
                </form>
            </div>

            <!-- Existing Availability List -->
            <div class="w-full lg:w-1/2 bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-6 text-gray-800">Your Availability</h3>
                <div class="availability-grid space-y-4 max-h-96 overflow-y-auto">
                    @forelse ($availabilities as $availability)
                        <div class="availability-card bg-gray-100 rounded-lg shadow-sm p-4 flex justify-between items-center transition hover:bg-gray-200">
                            <div>
                                <h5 class="text-sm font-bold text-gray-800">{{ $availability->date }}</h5>
                                <p class="text-gray-600">
                                    {{ substr($availability->start_time, 0, 5) }} - {{ substr($availability->end_time, 0, 5) }}
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <button class="edit-availability-btn flex items-center px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition"
                                data-availability-id="{{ $availability->id }}"
                                data-date="{{ $availability->date }}"
                                data-start-time="{{ \Carbon\Carbon::createFromFormat('H:i:s', $availability->raw_start_time)->format('H:i') }}"
                                data-end-time="{{ \Carbon\Carbon::createFromFormat('H:i:s', $availability->raw_end_time)->format('H:i') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </button>
                                <button class="delete-availability-btn flex items-center px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition"
                                    data-availability-id="{{ $availability->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-600 mt-2">No availability set yet.</p>
                            <p class="text-gray-500 text-sm">Click on a date in the calendar to add your availability</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Availability Modal -->
    <div id="editAvailabilityFormContainer" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-md font-semibold">Edit Availability</h4>
                <button id="closeEditModal" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="editAvailabilityForm" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editAvailabilityId">
                <div>
                    <label for="edit_date" class="block text-sm font-medium text-gray-700">Select Date:</label>
                    <input type="date" name="date" id="edit_date" class="w-full mt-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" required>
                </div>
                <div>
                    <label for="edit_start_time" class="block text-sm font-medium text-gray-700">Start Time:</label>
                    <input type="time" name="start_time" id="edit_start_time" class="w-full mt-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" required>
                </div>
                <div>
                    <label for="edit_end_time" class="block text-sm font-medium text-gray-700">End Time:</label>
                    <input type="time" name="end_time" id="edit_end_time" class="w-full mt-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary" required>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" id="closeEditModalBtn" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                        Update Availability
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
      document.addEventListener('DOMContentLoaded', function () {
        // Keep all your existing JavaScript
        // Handle Edit Button Click
        document.addEventListener('click', function (event) {
            if (event.target.classList.contains('edit-availability-btn') || event.target.closest('.edit-availability-btn')) {
                const button = event.target.classList.contains('edit-availability-btn') ? 
                    event.target : event.target.closest('.edit-availability-btn');
                
                const availabilityId = button.getAttribute('data-availability-id');
                const date = button.getAttribute('data-date');
                const startTime = button.getAttribute('data-start-time');
                const endTime = button.getAttribute('data-end-time');

                // Debugging
                console.log('Start Time (before setting):', startTime);
                
                // Populate the modal form with existing data
                const editModal = document.getElementById('editAvailabilityFormContainer');
                const editIdInput = document.getElementById('editAvailabilityId');
                const editDateInput = document.getElementById('edit_date');
                const editStartTimeInput = document.getElementById('edit_start_time');
                const editEndTimeInput = document.getElementById('edit_end_time');

                editIdInput.value = availabilityId;
                editDateInput.value = date;
                console.log('Start Time:', startTime); 
                editStartTimeInput.value = startTime || ''; // Handle null or empty values
                editEndTimeInput.value = endTime || ''; // Handle null or empty values

                // Debugging
                console.log('Start Time (after setting):', editStartTimeInput.value);
                
                // Show the modal
                editModal.classList.remove('hidden');
                editModal.scrollIntoView({ behavior: 'smooth' });
            }
        });

        // Handle Form Submission via AJAX
        const editForm = document.getElementById('editAvailabilityForm');
        editForm.addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent default form submission

            const availabilityId = document.getElementById('editAvailabilityId').value;
            const date = document.getElementById('edit_date').value;
            const startTime = document.getElementById('edit_start_time').value;
            const endTime = document.getElementById('edit_end_time').value;

            console.log('Submitting form with data:', { availabilityId, date, startTime, endTime }); // Debugging

            fetch(`/freelancer/availabilities/${availabilityId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    date: date,
                    start_time: startTime,
                    end_time: endTime,
                }),
            })
                .then(response => {
                    console.log('Response:', response); // Debugging
                    return response.json();
                })
                .then(data => {
                    console.log('Response Data:', data); // Debugging
                    if (data.success) {
                        alert(data.message); // Show success message
                        location.reload(); // Reload the page to update the availability list
                    } else {
                        alert(data.message || 'Failed to update availability.');
                        console.error('Errors:', data.errors || data.error);
                    }
                })
                .catch(error => console.error('Error updating availability:', error));
        });

        // Handle Delete Button Click
        document.addEventListener('click', function (event) {
            if (event.target.classList.contains('delete-availability-btn') || event.target.closest('.delete-availability-btn')) {
                const button = event.target.classList.contains('delete-availability-btn') ? 
                    event.target : event.target.closest('.delete-availability-btn');
                    
                const availabilityId = button.getAttribute('data-availability-id');
                if (confirm('Are you sure you want to delete this availability?')) {
                    fetch(`/freelancer/availabilities/${availabilityId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Availability deleted successfully!');
                                location.reload(); // Reload the page to update the availability list
                            } else {
                                alert('Failed to delete availability.');
                            }
                        })
                        .catch(error => console.error('Error deleting availability:', error));
                }
            }
        });

        // Close Modal Handlers
        const closeModalButton = document.getElementById('closeEditModal');
        const closeModalButtonAlt = document.getElementById('closeEditModalBtn');
        
        if (closeModalButton) {
            closeModalButton.addEventListener('click', function () {
                const editModal = document.getElementById('editAvailabilityFormContainer');
                editModal.classList.add('hidden'); // Hide the modal
            });
        }
        
        if (closeModalButtonAlt) {
            closeModalButtonAlt.addEventListener('click', function () {
                const editModal = document.getElementById('editAvailabilityFormContainer');
                editModal.classList.add('hidden'); // Hide the modal
            });
        }
        
        // NEW CALENDAR FUNCTIONALITY
        // Initialize calendar variables
        let currentDate = new Date();
        let currentYear = currentDate.getFullYear();
        let currentMonth = currentDate.getMonth();
        
        // Store availability data
        const availabilityData = {};
        
        // Extract availability data from PHP to JavaScript 
        @foreach($availabilities as $availability)
            availabilityData['{{ $availability->date }}'] = {
                id: {{ $availability->id }},
                startTime: '{{ substr($availability->start_time, 0, 5) }}',
                endTime: '{{ substr($availability->end_time, 0, 5) }}'
            };
        @endforeach
        
        // Navigate to previous month
        document.getElementById('prevMonth').addEventListener('click', function() {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar();
        });
        
        // Navigate to next month
        document.getElementById('nextMonth').addEventListener('click', function() {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar();
        });
        
        // Render the calendar
        function renderCalendar() {
            const calendarDays = document.getElementById('calendarDays');
            calendarDays.innerHTML = '';
            
            // Update month and year display
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                               'July', 'August', 'September', 'October', 'November', 'December'];
            document.getElementById('currentMonthDisplay').textContent = monthNames[currentMonth] + ' ' + currentYear;
            
            // Get first day of month and total days
            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            
            // Add empty cells for days before the first day of the month
            for (let i = 0; i < firstDay; i++) {
                const emptyCell = document.createElement('div');
                emptyCell.className = 'calendar-day empty';
                calendarDays.appendChild(emptyCell);
            }
            
            // Get today for highlighting
            const today = new Date();
            const todayDate = today.getDate();
            const todayMonth = today.getMonth();
            const todayYear = today.getFullYear();
            
            // Add days of the month
            for (let day = 1; day <= daysInMonth; day++) {
                const dateCell = document.createElement('div');
                dateCell.className = 'calendar-day';
                
                // Format date as YYYY-MM-DD
                const formattedDate = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                
                // Check if date is today
                const isToday = day === todayDate && currentMonth === todayMonth && currentYear === todayYear;
                if (isToday) {
                    dateCell.classList.add('today');
                }
                
                // Check if date is available
                if (availabilityData[formattedDate]) {
                    dateCell.classList.add('available');
                    
                    // Create content for the cell
                    dateCell.innerHTML = `
                        <div class="day-number">${day}</div>
                        <div class="day-info">${availabilityData[formattedDate].startTime} - ${availabilityData[formattedDate].endTime}</div>
                    `;
                } else {
                    // Just show the day number for unavailable days
                    dateCell.innerHTML = `<div class="day-number">${day}</div>`;
                }
                
                // Disable past dates
                const cellDate = new Date(currentYear, currentMonth, day);
                if (cellDate < new Date(todayYear, todayMonth, todayDate)) {
                    dateCell.classList.add('disabled');
                } else {
                    // Add click listener for future dates
                    dateCell.addEventListener('click', function() {
                        // If availability exists for this date, open the edit modal
                        if (availabilityData[formattedDate]) {
                            // Populate the edit form
                            document.getElementById('editAvailabilityId').value = availabilityData[formattedDate].id;
                            document.getElementById('edit_date').value = formattedDate;
                            document.getElementById('edit_start_time').value = availabilityData[formattedDate].startTime;
                            document.getElementById('edit_end_time').value = availabilityData[formattedDate].endTime;
                            
                            // Show the edit modal
                            document.getElementById('editAvailabilityFormContainer').classList.remove('hidden');
                        } else {
                            // For new availability, populate the add form
                            document.getElementById('date').value = formattedDate;
                            document.getElementById('start_time').value = '09:00';
                            document.getElementById('end_time').value = '17:00';
                            
                            // Scroll to the form
                            document.querySelector('form[action="{{ route('freelancer.setAvailability') }}"]')
                                .scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    });
                }
                
                calendarDays.appendChild(dateCell);
            }
        }
        
        // Initialize calendar
        renderCalendar();
    });

    // Function to adjust calendar for mobile screens
function adjustCalendarResponsiveness() {
  const calendarContainer = document.querySelector('.calendar-container');
  const isMobile = window.innerWidth <= 768;
  
  // Check if we're on mobile
  if (isMobile) {
    // Add touch-optimized class
    calendarContainer.classList.add('mobile-optimized');
    
    // Fix height of day cells
    document.querySelectorAll('.calendar-day').forEach(day => {
      if (!day.classList.contains('empty')) {
        // Ensure content fits properly
        const dayInfo = day.querySelector('.day-info');
        if (dayInfo && dayInfo.scrollHeight > dayInfo.clientHeight) {
          dayInfo.style.fontSize = '8px';
        }
      }
    });
  } else {
    // Remove mobile optimizations on larger screens
    calendarContainer.classList.remove('mobile-optimized');
    
    // Reset font size
    document.querySelectorAll('.day-info').forEach(info => {
      info.style.fontSize = '';
    });
  }
}

// Call this function on load and resize
window.addEventListener('load', adjustCalendarResponsiveness);
window.addEventListener('resize', adjustCalendarResponsiveness);

</script>