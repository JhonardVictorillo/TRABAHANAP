<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments</title>
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header class="header">
        <h1>My Appointments</h1>
        <a href="{{ route('customer.dashboard') }}">
            <button class="back-btn">Back to Dashboard</button>
        </a>
    </header>

    <main>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($appointments->isEmpty())
            <p>No appointments found.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Freelancer</th>
                        <th>Category</th>
                        <th>Services</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($appointments as $appointment)
                    <tr>
                        <td style="color: #2860c9; font-weight: 600;">
                            {{ $appointment->freelancer->firstname ?? 'Freelancer not found' }} {{ $appointment->freelancer->lastname ?? '' }}
                        </td>
                        <td>
                            @if($appointment->freelancer->categories->isNotEmpty())
                                <li style="color: #3c763d; font-weight: 600;">
                                    {{ $appointment->freelancer->categories->pluck('name')->join(', ') }}
                                </li>
                            @endif
                        </td>
                        <td>
                            @if ($appointment->post && !empty($appointment->post->sub_services))
                                @php
                                    $subServices = is_string($appointment->post->sub_services) ? json_decode($appointment->post->sub_services, true) : $appointment->post->sub_services;
                                @endphp
                                @if (is_array($subServices))
                                    <ul>
                                        @foreach ($subServices as $subService)
                                            <li style="color: #3c763d; font-weight: 600;">{{ $subService }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No sub-services available.</p>
                                @endif
                            @else
                                <p>No sub-services available.</p>
                            @endif
                        </td>
                        <td>{{ ucfirst($appointment->status) }}</td>
                        <td>
                            @if($appointment->status === 'completed')
                                @if(!$appointment->rating) 
                                    <!-- If not rated yet, show the review form -->
                                    <form class="review-form" data-appointment-id="{{ $appointment->id }}">
                                        @csrf
                                        <label for="rating">Rate the service:</label>
                                        <select name="rating" class="rating">
                                            <option value="1">⭐</option>
                                            <option value="2">⭐⭐</option>
                                            <option value="3">⭐⭐⭐</option>
                                            <option value="4">⭐⭐⭐⭐</option>
                                            <option value="5">⭐⭐⭐⭐⭐</option>
                                        </select>
                                        <label for="review">Your Review:</label>
                                        <textarea name="review" class="review" placeholder="Write your review here..."></textarea>
                                        <button type="button" class="submit-review" data-appointment-id="{{ $appointment->id }}">Submit Feedback</button>
                                    </form>
                                @else
                                    <!-- Display existing rating and review -->
                                    <p>Rated: {{ $appointment->rating }} Star(s)</p>
                                    <p class="review-text">{{ $appointment->review }}</p>
                                    <button class="edit-review-btn" data-appointment-id="{{ $appointment->id }}">Edit Review</button>

                                    <!-- Hidden edit form -->
                                    <form class="edit-review-form" data-appointment-id="{{ $appointment->id }}" style="display: none;">
                                        <label for="rating">Update Rating:</label>
                                        <select name="rating" class="rating">
                                            <option value="1" {{ $appointment->rating == 1 ? 'selected' : '' }}>⭐</option>
                                            <option value="2" {{ $appointment->rating == 2 ? 'selected' : '' }}>⭐⭐</option>
                                            <option value="3" {{ $appointment->rating == 3 ? 'selected' : '' }}>⭐⭐⭐</option>
                                            <option value="4" {{ $appointment->rating == 4 ? 'selected' : '' }}>⭐⭐⭐⭐</option>
                                            <option value="5" {{ $appointment->rating == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐</option>
                                        </select>
                                        <label for="review">Update Review:</label>
                                        <textarea name="review" class="review">{{ $appointment->review }}</textarea>
                                        <button type="button" class="submit-edit-review" data-appointment-id="{{ $appointment->id }}">Save Changes</button>
                                    </form>
                                @endif
                            @endif

                            @if($appointment->status === 'pending')
                                <form action="{{ route('customer.appointments.cancel', $appointment->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="cancel-button">Cancel</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        @endif
    </main>

    <script>
        $(document).ready(function () {
            // Handle new review submission
            $(".submit-review").click(function () {
                let appointmentId = $(this).data("appointment-id");
                let form = $(this).closest("form");
                let rating = form.find(".rating").val();
                let review = form.find(".review").val();

                $.ajax({
                    url: `/customer/appointments/rate/${appointmentId}`,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        rating: rating,
                        review: review
                    },
                    success: function (response) {
                        alert(response.success);
                        location.reload(); // Refresh to update the UI
                    },
                    error: function (xhr) {
                        alert(xhr.responseJSON.error);
                    }
                });
            });

            // Show Edit Review Form
            $(".edit-review-btn").click(function () {
                let appointmentId = $(this).data("appointment-id");
                $(`.edit-review-form[data-appointment-id="${appointmentId}"]`).toggle();
            });

            // Handle review update
            $(".submit-edit-review").click(function () {
                let form = $(this).closest(".edit-review-form");
                let appointmentId = form.data("appointment-id");
                let rating = form.find(".rating").val();
                let review = form.find(".review").val();

                $.ajax({
                    url: `/customer/appointments/rate/${appointmentId}`,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        rating: rating,
                        review: review
                    },
                    success: function (response) {
                        alert(response.success);
                        location.reload(); // Refresh to reflect changes
                    },
                    error: function (xhr) {
                        alert(xhr.responseJSON.error);
                    }
                });
            });
        });
    </script>
</body>
</html>
