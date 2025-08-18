@if(session('success'))
    <div class="alert alert-success" id="success-alert">
        <i class='bx bx-check-circle'></i> 
        {{ session('success') }}
        <button type="button" class="close-btn" onclick="closeSuccessAlert()">&times;</button>
    </div>

    <script>
        // Function to handle success message hiding
        function hideSuccessAlert() {
            const alert = document.getElementById('success-alert');
            if (alert) {
                // Add fade-out effect
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '1';
                
                setTimeout(() => {
                    alert.style.opacity = '0';
                    // Remove after fade completes
                    setTimeout(() => {
                        if (alert && alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 500);
                }, 5000); // Extended to 5 seconds for better visibility
            }
        }
        
        // Function to manually close the alert
        function closeSuccessAlert() {
            const alert = document.getElementById('success-alert');
            if (alert) {
                alert.style.opacity = '0';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 500);
            }
        }

        // Initialize - run when script loads
        (function() {
            // Run immediately
            hideSuccessAlert();
            
            // Attach event listeners for navigation in all dashboard types
            document.addEventListener('click', function(e) {
                // Universal approach - any nav link click should hide alerts
                const isNavLink = 
                    // Admin dashboard links
                    e.target.closest('.sidebar-links a') || 
                    // Common dashboard link IDs
                    e.target.id?.includes('Link') ||
                    // Any tab buttons 
                    e.target.classList.contains('tab-btn') ||
                    // Any sidebar toggle buttons
                    e.target.classList.contains('sidebar-toggle') ||
                    // Any dashboard card that might trigger section changes
                    e.target.closest('.dashboard-card');
                    
                if (isNavLink) {
                    hideSuccessAlert();
                }
            });
            
            // Handle AJAX navigation (for dashboards using AJAX)
            const originalFetch = window.fetch;
            window.fetch = function() {
                const fetchPromise = originalFetch.apply(this, arguments);
                fetchPromise.then(() => {
                    // After any AJAX call completes, check if we need to hide alerts
                    setTimeout(hideSuccessAlert, 100);
                });
                return fetchPromise;
            };
        })();
    </script>

    <style>
        .alert.alert-success {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            padding: 15px 25px;
            border-left: 4px solid #10b981;
            background-color: #ecfdf5;
            color: #047857;
            border-radius: 4px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            opacity: 1;
            transition: opacity 0.5s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 400px;
        }
        
        .alert.alert-success i {
            margin-right: 8px;
            font-size: 1.2em;
            vertical-align: middle;
        }
        
        .alert.alert-success .close-btn {
            background: transparent;
            border: none;
            color: #047857;
            font-size: 1.2em;
            cursor: pointer;
            margin-left: 15px;
            padding: 0;
            line-height: 1;
        }
        
        /* Ensure it works on mobile too */
        @media (max-width: 576px) {
            .alert.alert-success {
                max-width: 90%;
                top: 10px;
                right: 5%;
                left: 5%;
                margin: 0 auto;
            }
        }
    </style>
@endif

{{-- Also handle error messages the same way --}}
@if(session('error'))
    <div class="alert alert-danger" id="error-alert">
        <i class='bx bx-error-circle'></i> 
        {{ session('error') }}
        <button type="button" class="close-btn" onclick="closeErrorAlert()">&times;</button>
    </div>

    <script>
        // Error message hiding function
        function hideErrorAlert() {
            const alert = document.getElementById('error-alert');
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '1';
                
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        if (alert && alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 500);
                }, 5000);
            }
        }
        
        // Manual close function
        function closeErrorAlert() {
            const alert = document.getElementById('error-alert');
            if (alert) {
                alert.style.opacity = '0';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 500);
            }
        }
        
        // Initialize error alert hiding
        hideErrorAlert();
    </script>

    <style>
        .alert.alert-danger {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            padding: 15px 25px;
            border-left: 4px solid #ef4444;
            background-color: #fef2f2;
            color: #b91c1c;
            border-radius: 4px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            opacity: 1;
            transition: opacity 0.5s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 400px;
        }
        
        .alert.alert-danger i {
            margin-right: 8px;
            font-size: 1.2em;
            vertical-align: middle;
        }
        
        .alert.alert-danger .close-btn {
            background: transparent;
            border: none;
            color: #b91c1c;
            font-size: 1.2em;
            cursor: pointer;
            margin-left: 15px;
            padding: 0;
            line-height: 1;
        }
        
        @media (max-width: 576px) {
            .alert.alert-danger {
                max-width: 90%;
                top: 10px;
                right: 5%;
                left: 5%;
                margin: 0 auto;
            }
        }
    </style>
@endif