
@include('customer.header')
@include('customer.maincontent')
@include('customer.footer')
  
 
      <!-- Success message -->
      @if(session('success'))
        <div class="alert alert-success">
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
              // ***********drop down functionality***********************************
      document.querySelector('.profile').addEventListener('click', () => {
          const dropdown = document.querySelector('.dropdown-menu');
          dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });
        document.querySelectorAll('.favorite-icon').forEach(icon => {
          icon.addEventListener('click', () => {
            icon.classList.toggle('liked');
            const heart = icon.querySelector('i');
        
            if (icon.classList.contains('liked')) {
              heart.classList.remove('far'); 
              heart.classList.add('fas');   
            } else {
              heart.classList.remove('fas');
              heart.classList.add('far');
            }
          });
        });
  

        document.addEventListener("DOMContentLoaded", function() {
          const profilepageLink = document.getElementById('profile-link');
          const profilepageSection = document.getElementById('profile-section');



        })

        document.addEventListener('DOMContentLoaded', function () {
        const notificationIcon = document.getElementById('notification-icon');
        const notificationSection = document.getElementById('notification-section');

        notificationIcon.addEventListener('click', function () {
            if (notificationSection.style.display === 'none' || notificationSection.style.display === '') {
                notificationSection.style.display = 'block';
            } else {
                notificationSection.style.display = 'none';
            }
        });


        
        const searchBar = document.querySelector('.search-bar');
        const searchForm = searchBar.closest('form');

        searchBar.addEventListener('input', function () {
            if (searchBar.value.trim() === '') {
                searchForm.submit(); // Submit form to reload all posts
            }
        });
      
    }); 

     


    </script>
</body>
</html>