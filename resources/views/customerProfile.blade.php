<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile</title>
    <link rel="stylesheet" href="{{('css/customerProfile.css')}}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>
<body>
    <main class="main-content">
        <!-- Back Button -->
        <button onclick="goBack()" class="back-button"><i class='bx bx-arrow-back'></i> Back</button>

        <div class="profile-section">
            <div class="profile-info">
                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" class="main-profile-picture">
                <h2>{{ $user->firstname }} {{ $user->lastname }}</h2>
                <a href="#" class="edit-profile"><i class='bx bx-edit'></i>Edit Profile</a>
            </div>

            <div class="profile">
                <div class="profile-header">
                    <h3><i class='bx bx-user'></i><span>Profile</span></h3>
                </div>
                <div class="profile-details">
                    <!-- <div class="service-category">
                        <h4>Service Category</h4>
                        <span>Technician</span>
                        <span>Food & Pastries</span>
                        <span>Arts & Media</span>
                        <span>Party or Events</span>
                    </div> -->
                    <div class="personal-details">
                        <p><strong>First Name:</strong> <span>{{ $user->firstname }}</span></p>
                        <p><strong>Last Name:</strong> <span>{{ $user->lastname }}</span></p>
                        <p><strong>Email:</strong> <span>{{ $user->email }}</span></p>
                        <p><strong>Contact No.:</strong> <span>{{ $user->contact_number }}</span></p>
                        <p><strong>Address:</strong> <span>{{ $user->province }} , {{ $user->city }} ,  {{ $user->zipcode }}</span></p>
                        
                        <div class="social-media">
                            <p><strong>Social Media:</strong></p>
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="recent-works">
            <h3>Recent Freelancer</h3>
            <div class="work-gallery">
                <img src="images/customer.jpg" alt="Work 1" class="work-item">
                <div class="add-work"><span class="material-symbols-outlined">add</span>Add Work</div>
            </div>
        </div>
    </main>

    <script>
        function goBack() {
            window.history.back(); // Go back to the previous page
        }
    </script>
</body>
</html>