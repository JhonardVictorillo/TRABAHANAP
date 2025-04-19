<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME PAGE</title>
    <link rel="stylesheet" href="{{asset('css/home.css')}}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    
    

</head>
<body>
<header class="header">
       
        <div class="home-header">
        <h2 class="logo-header">
            <span class="logo-icon">MG</span>
            Mingla<span class="brand-span">Gawa</span>
        </h2>          
    </div>
       
        <nav class="navbar">
            <a href="#home" class="active">Home</a>
            <a href="#category">Categories</a>
            <a href="#Contact">Contact Us</a>
            <a href="{{route('login')}}"><button class="logbtn">Login</button></a>
            <a href="{{route('register.form')}}"><button class="regisbtn">Register</button></a>
        </nav>
    </header>
    
    <section class="home" id="home">
        <div class="tagline">
            <h1>Discover <span class="highlight">freelance services</span><br>that perfectly align with <br>your business goals.</h1>
            <p>Helping you finding the right service</p>
            <div class="box">
                <div class="search-box">
                    <input type="text" placeholder="Search....">
                    <label for="" class="icon">
                        <i class='bx bx-search'></i>
                    </label>
                </div>
            </div>            
        </div>
        <div class="picture">
            <img src="images/pic1.png" alt="Food Delivery">
        </div>
    </section>

    <section class="category-container" id="category">
        <h2>Categories in MinglaGawa.</h2>
        <div class="categories">
            <div class="category">
                <h3>Art & Media</h3>
                <img src="images/art&media.jpg" alt="Art and Media">
                <p>Explore services like graphic design, photography, and video editing.</p>
                 <a href="#" class="btn">View Services</a> 
            </div>
            <div class="category">
                <h3>Grooming</h3>
                <img src="images/grooming.jpg" alt="Grooming">
                <p>Find pet grooming, haircuts, and other grooming services near you.</p>
                <a href="#" class="btn">View Services</a> 
            </div>
            <div class="category">
                <h3>Technicians</h3>
                <img src="images/technician.jpg" alt="Technicians">
                <p>Get expert help for electrical repairs, appliance installations, and more.</p>
                 <a href="#" class="btn">View Services</a> 
            </div>
            <div class="category">
                <h3>Housekeeping</h3>
                <img src="images/housekeeping.jpg" alt="Housekeeping">
                <p>Hire professionals for cleaning, laundry, and other household tasks.</p>
                <a href="#" class="btn">View Services</a> 
            </div>
            <div class="category">
                <h3>Fashion & Beauty</h3>
                <img src="images/fashion&beauty.jpg" alt="Fashion and Beauty">
                <p>Discover stylists, makeup artists, and tailoring services.</p>
                 <a href="#" class="btn">View Services</a> 
            </div>
            <div class="category">
                <h3>Plumber</h3>
                <img src="images/plumber.jpg" alt="Plumber">
                <p>Find reliable plumbing services for repairs and installations.</p>
                 <a href="#" class="btn">View Services</a> 
            </div>
            <div class="category">
                <h3>Party or Events</h3>
                <img src="images/party-events.jpg" alt="Party or Events">
                <p>Plan your events with top-notch decorators and event planners.</p>
                 <a href="#" class="btn">View Services</a> 
            </div>
            <div class="category">
                <h3>Food & Pastries</h3>
                <img src="images/food&pastries.jpg" alt="Food and Pastries">
                <p>Order custom cakes, catering services, and more.</p>
                 <a href="#" class="btn">View Services</a> 
            </div>
        </div>        
    </section>

            
    
    <footer class="footer" id="footer">
        <div class="container">
            <div class="row">
              
                <div class="footer-col">
                    <h4>About</h4>
                    <p>TrabaHanap is your go-to platform for finding freelance services that meet your business needs. We connect talented freelancers with clients who need their expertise. Whether you're looking for creative services, technical support, or anything in between, we've got you covered.</p>
                </div>
    
                
                <div class="footer-col" id="team">
                    <h4>Team</h4>
                    <ul>
                        <li><a href="#">Jhonard</a></li>
                        <li><a href="#">Cristian</a></li>
                        <li><a href="#">ELjohn</a></li>
                        <li><a href="#">Aljun</a></li>
                        <li><a href="#">Kathlen</a></li>
                    </ul>
                </div>
    
                <div class="footer-col" id="Contact">
                    <h4>Contact us</h4>
                    <ul>
                        <li><a href="#">Email: @trabahanap.com</a></li>
                        <li><a href="#">Phone: +1234567890</a></li>
                        <li><a href="#">Address: @skenajapan</a></li>
                    </ul>
                </div>
    
                
                <div class="footer-col">
                    <h4>Follow us</h4>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

          
    
        <script>
            ///////////////////////////////////////////////for View Services/////////////////////////////
const serviceButtons = document.querySelectorAll('.category .btn'); 
const messagePopup = document.createElement('div'); 

function showPopup(message) {
    messagePopup.classList.add('popup-message');
    messagePopup.innerHTML = `
        <div class="popup-content">
            <p>${message}</p>
            <button class="close-popup">Close</button>
        </div>
    `;
    document.body.appendChild(messagePopup);
    
   
    const closePopup = messagePopup.querySelector('.close-popup');
    closePopup.addEventListener('click', () => {
        messagePopup.style.display = 'none';
    });
}

serviceButtons.forEach(button => {
    button.addEventListener('click', (e) => {
        e.preventDefault();
        const isLoggedIn = localStorage.getItem('isLoggedIn');

        if (!isLoggedIn) {
           
            showPopup("You need to log in or create an account first to view the services.");
        } else {
          
            window.location.href = '/services-page.html'; 
        }
    });
});



const navLinks = document.querySelectorAll('.navbar a');

navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        
        navLinks.forEach(link => link.classList.remove('active'));
        
        link.classList.add('active');

        
        const sectionId = link.getAttribute('href').substring(1); 
        const section = document.getElementById(sectionId);
        if (section) {
            e.preventDefault(); 
            section.scrollIntoView({ behavior: 'smooth' }); 
        }
    });
});
    //login and register modal script///
    
  
    
</script>

</body>
</html>