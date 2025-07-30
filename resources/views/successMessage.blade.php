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

        </script>