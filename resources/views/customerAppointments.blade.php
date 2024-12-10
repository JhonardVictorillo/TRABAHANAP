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
        <a href="{{ route('customer.dashboard') }}">Back to Dashboard</a>
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
                        <td>{{ $appointment->freelancer->firstname ?? 'Freelancer not found' }} {{ $appointment->freelancer->lastname ?? '' }}</td>
                        <td>

                            {{-- Display the categories --}}
                            @if($appointment->freelancer->categories->isNotEmpty())
                              <li> {{ $appointment->freelancer->categories->pluck('name')->join(', ') }}</li>
                            @endif
                        </td>
                        <td>
                        @if($appointment->post && !empty($appointment->post->sub_services))
                                <ul>
                                    @foreach ($appointment->post->sub_services as $subService)
                                        <li>{{ $subService }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No sub-services available.</p>
                            @endif
                        </td>
                        <td>{{ ucfirst($appointment->status) }}</td>
                        <td>
                            @if($appointment->status !== 'Canceled')
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
