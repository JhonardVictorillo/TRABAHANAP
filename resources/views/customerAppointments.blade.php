<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments</title>
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
    
</head>
<body>
    <header class="header">
        <h1>My Appointments</h1>
        <a href="{{ route('customer.dashboard') }}">
        <button class="back-btn" >Back to Dashboard</button> 
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
                        <td style="color: #2860c9; font-weight: 600;"  >{{ $appointment->freelancer->firstname ?? 'Freelancer not found' }} {{ $appointment->freelancer->lastname ?? '' }}</td>
                        <td>

                            {{-- Display the categories --}}
                            @if($appointment->freelancer->categories->isNotEmpty())
                              <li style="color: #3c763d; font-weight: 600;"> {{ $appointment->freelancer->categories->pluck('name')->join(', ') }}</li>
                            @endif
                        </td>
                        <td>
                             @if ($appointment->post && !empty($appointment->post->sub_services))
                                @php
                                    // Decode sub_services if it's a JSON string
                                    $subServices = is_string($appointment->post->sub_services) ? json_decode($appointment->post->sub_services, true) : $appointment->post->sub_services;
                                @endphp

                                @if (is_array($subServices))
                                    <ul >
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
                        @if($appointment->status === 'completed' && !$appointment->rating) <!-- Check if appointment is completed and no rating exists -->
                                <form action="{{ route('customer.appointments.rate', $appointment->id) }}" method="POST">
                                    @csrf
                                    <label for="rating">Rate the service:</label>
                                    <select name="rating" id="rating">
                                        <option value="1">1 Star</option>
                                        <option value="2">2 Stars</option>
                                        <option value="3">3 Stars</option>
                                        <option value="4">4 Stars</option>
                                        <option value="5">5 Stars</option>
                                    </select>
                                    <button type="submit" class="rate-button">Submit Rating</button>
                                </form>
                            @elseif($appointment->rating)
                                <p>Rated: {{ $appointment->rating }} Star(s)</p>
                            @endif


                                        {{-- Cancel button only shows if status is 'pending' --}}
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
           document.addEventListener('DOMContentLoaded', function () {
              const alert = document.querySelector('.alert-success');
              if (alert) {
                  setTimeout(() => {
                      alert.remove();
                  }, 3000); // 3 seconds
              }
          });
    </script>
</body>
</html>
