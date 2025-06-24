  @include('customer.header')

  @if(!$user->profile_completed)
    @include('completeProfile.customerCompleteProfile')
@endif

@include('customer.maincontent')
@include('customer.footer')
  
   <!-- Success message -->
      @if(session('success'))
        <div class="alert alert-success">
        <i class='bx bx-check-circle'></i> 
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


         const profileBtn = document.getElementById('profileBtn');
          const dropdownMenu = document.getElementById('dropdownMenu');

          profileBtn.addEventListener('click', () => {
            dropdownMenu.classList.toggle('hidden');
          });

          document.addEventListener('click', (e) => {
            if (!profileBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
              dropdownMenu.classList.add('hidden');
            }
          });

      

       const searchBar = document.querySelector('.search-bar');
        const searchForm = searchBar.closest('form');

        searchBar.addEventListener('input', function () {
            if (searchBar.value.trim() === '') {
                searchForm.submit(); // Submit form to reload all posts
            }
        });
    </script>
  </body>
</html>




