  @include('customer.header')

  @if(!$user->profile_completed)
    @include('completeProfile.customerCompleteProfile')
  @endif

  @include('customer.maincontent')
  @include('customer.HowItWorks')
  @include('customer.footer')
  
   @include('successMessage')
  
    <script>

      
        document.addEventListener('DOMContentLoaded', function() {
   
    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
      if (dropdownMenu && !profileBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
        dropdownMenu.classList.add('hidden');
      }
    });
    
    // Search bar functionality
    const searchBar = document.querySelector('.search-bar');
    if (searchBar) {
      const searchForm = searchBar.closest('form');
      searchBar.addEventListener('input', function () {
        if (searchBar.value.trim() === '') {
          searchForm.submit(); // Submit form to reload all posts
        }
      });
    }
  });
    </script>
  </body>
</html>




