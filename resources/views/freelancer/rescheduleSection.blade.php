<section id="rescheduleSection" class="details-section py-6">
    <div class="container mx-auto flex flex-wrap lg:flex-nowrap gap-6">
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
                <button type="submit" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Save Availability
                </button>
            </form>
        </div>

        <!-- Existing Availability List -->
        <div class="w-full lg:w-1/2 bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold mb-6 text-gray-800">Your Availability</h3>
            <div class="availability-grid space-y-4 max-h-96 overflow-y-auto">
                @forelse ($availabilities as $availability)
                    <div class="availability-card bg-gray-100 rounded-lg shadow-md p-4 flex justify-between items-center">
                        <div>
                            <h5 class="text-sm font-bold text-gray-800">{{ $availability->date }}</h5>
                            <p class="text-gray-600">
                                {{ substr($availability->start_time, 0, 5) }} - {{ substr($availability->end_time, 0, 5) }}
                            </p>
                        </div>
                        <div class="flex space-x-2">
                            <button class="edit-availability-btn px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
                            data-availability-id="{{ $availability->id }}"
                            data-date="{{ $availability->date }}"
                            data-start-time="{{ \Carbon\Carbon::createFromFormat('H:i:s', $availability->raw_start_time)->format('H:i') }}"
                            data-end-time="{{ \Carbon\Carbon::createFromFormat('H:i:s', $availability->raw_end_time)->format('H:i') }}">
                                                        Edit
                            </button>
                            <button class="delete-availability-btn px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600"
                                data-availability-id="{{ $availability->id }}">
                                Delete
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600">No availability set yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Edit Availability Modal -->
    <div id="editAvailabilityFormContainer" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <h4 class="text-md font-semibold mb-4">Edit Availability</h4>
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
                    <button type="button" id="closeEditModal" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                        Update Availability
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handle Edit Button Click
        document.addEventListener('click', function (event) {
            if (event.target.classList.contains('edit-availability-btn')) {
                const availabilityId = event.target.getAttribute('data-availability-id');
                const date = event.target.getAttribute('data-date');
                const startTime = event.target.getAttribute('data-start-time');
                const endTime = event.target.getAttribute('data-end-time');

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
            if (event.target.classList.contains('delete-availability-btn')) {
                const availabilityId = event.target.getAttribute('data-availability-id');
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

        // Close Modal Handler
        const closeModalButton = document.getElementById('closeEditModal');
        if (closeModalButton) {
            closeModalButton.addEventListener('click', function () {
                const editModal = document.getElementById('editAvailabilityFormContainer');
                editModal.classList.add('hidden'); // Hide the modal
            });
        }
    });
</script>