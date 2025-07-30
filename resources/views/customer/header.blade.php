<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Customer Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel ="stylesheet" href="{{asset ('css/customerNotif.css')}}" />
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"rel="stylesheet"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Pacifico&family=Inter:wght@400;500;600&family=Poppins:wght@400;500;600&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css"
      rel="stylesheet"
    />
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: "#2563eb", // Updated from #118f39 to Royal Blue
              secondary: "#64748B",
              accent: "#3b82f6", 
            },
            borderRadius: {
              none: "0px",
              sm: "4px",
              DEFAULT: "8px",
              md: "12px",
              lg: "16px",
              xl: "20px",
              "2xl": "24px",
              "3xl": "32px",
              full: "9999px",
              button: "8px",
            },
            fontFamily: {
              inter: ["Inter", "sans-serif"],
              poppins: ["Poppins", "sans-serif"],
            },
          },
        },
      };
    </script>
    <style>
      :where([class^="ri-"])::before { content: "\f3c2"; }
      .search-input:focus {
      outline: none;
      box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
      }
      .service-card:hover {
      transform: translateY(-2px);
      transition: all 0.2s ease-in-out;
      }

      /* New color overrides */
  .text-primary, .hover\:text-primary:hover {
    color: #2563eb !important;
  }
  .bg-primary {
    background-color: #2563eb !important;
  }
  .hover\:bg-primary:hover {
    background-color: #1d4ed8 !important;
  }
  .border-primary {
    border-color: #2563eb !important;
  }
  
  /* Custom color for logo */
  .text-\[\#118f39\] {
    color: #2563eb;
  }
  .text-\[\#4CAF50\] {
    color: #3b82f6;
  }
    </style>
  </head>
     
  <body class="bg-[#F8F9FA] font-inter">
   @include('customer.customerHeader')


    