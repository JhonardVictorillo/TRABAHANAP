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

  document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
      const submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn) {
        const btnText = submitBtn.querySelector('.btn-text');
        const btnSpinner = submitBtn.querySelector('.btn-spinner');
        submitBtn.disabled = true;
        submitBtn.classList.add('disabled');
        if (btnText) btnText.style.display = 'none';
        if (btnSpinner) btnSpinner.style.display = 'inline-block';
      }
    });
  });
});

function showSpinnerOnButton(button) {
  const btnText = button.querySelector('.btn-text');
  const btnSpinner = button.querySelector('.btn-spinner');
  button.disabled = true;
  button.classList.add('disabled');
  if (btnText) btnText.style.display = 'none';
  if (btnSpinner) btnSpinner.style.display = 'inline-block';
}

function restoreButton(button, text) {
  const btnText = button.querySelector('.btn-text');
  const btnSpinner = button.querySelector('.btn-spinner');
  button.disabled = false;
  button.classList.remove('disabled');
  if (btnText) btnText.style.display = 'inline-block';
  if (btnSpinner) btnSpinner.style.display = 'none';
  if (btnText && text) btnText.textContent = text;
}
    </script>
  </body>
</html>




