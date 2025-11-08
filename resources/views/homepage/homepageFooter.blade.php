<footer class="footer" id="footer">
    <div class="container">
        <!-- Main Footer Content -->
        <div class="footer-main">
            <div class="footer-grid">
                <!-- About Section -->
                <div class="footer-col footer-about">
                    <div class="footer-brand">
                        <h3>MinglaGawa</h3>
                        <p class="brand-tagline">Your Gateway to Quality Freelance Services</p>
                    </div>
                    <p class="footer-description">
                        Connect with top-rated freelance services for all your business needs. 
                        From web development to graphic design, find skilled professionals ready to bring your projects to life.
                    </p>
                </div>

                <!-- Quick Links -->
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#categories">Browse Services</a></li>
                        <li><a href="#how-it-works">How It Works</a></li>
                        <li><a href="/freelancer/register">Become a Freelancer</a></li>
                        <li><a href="/login">Login</a></li>
                    </ul>
                </div>

                <!-- Team -->
                <div class="footer-col">
                    <h4>Our Team</h4>
                    <ul class="footer-links">
                        <li><a href="#">Jhonard</a></li>
                        <li><a href="#">Cristian</a></li>
                        <li><a href="#">ELjohn</a></li>
                        <li><a href="#">Aljun</a></li>
                        <li><a href="#">Kathlen</a></li>
                    </ul>
                </div>

                <!-- Contact & Social -->
                <div class="footer-col">
                    <h4>Get In Touch</h4>
                    <div class="contact-info">
                        <div class="contact-item">
                            <span class="contact-label">Email:</span>
                            <a href="mailto:support@minglagawa.com">support@minglagawa.com</a>
                        </div>
                        <div class="contact-item">
                            <span class="contact-label">Phone:</span>
                            <a href="tel:+1234567890">+1234567890</a>
                        </div>
                        <div class="contact-item">
                            <span class="contact-label">Address:</span>
                            <span>Upper Pakigne, Minglanilla, Cebu</span>
                        </div>
                    </div>
                    
                    <div class="social-section">
                        <h5>Follow Us</h5>
                        <div class="social-links">
                            <a href="#" class="social-link" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-link" title="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-link" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-link" title="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <div class="copyright">
                    <p>&copy; 2024 MinglaGawa. All rights reserved.</p>
                </div>
                <div class="footer-legal">
                   <a href="#" id="showPrivacyModal">Privacy Policy</a>
                   <a href="#" id="showTermsModal">Terms of Service</a>
                   <a href="#">Support</a>
                </div>
            </div>
        </div>
    </div>
</footer>
<style>
    
/* Enhanced Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0; top: 0;
    width: 100%; height: 100%;
    overflow: auto;
    background: rgba(37, 99, 235, 0.10); /* subtle blue overlay */
    transition: background 0.3s;
}

.modal-content {
    background: #fff;
    margin: 5% auto;
    padding: 2.5rem 2rem 2rem 2rem;
    border-radius: 18px;
    width: 95%;
    max-width: 520px;
    position: relative;
    box-shadow: 0 8px 32px rgba(37,99,235,0.10), 0 1.5px 8px rgba(0,0,0,0.08);
    border: 2px solid #2563eb22;
    animation: modalFadeIn 0.3s;
}

@keyframes modalFadeIn {
    from { transform: translateY(-40px) scale(0.98); opacity: 0; }
    to   { transform: translateY(0) scale(1); opacity: 1; }
}

.modal-content h2 {
    color: #2563eb;
    margin-bottom: 1rem;
    font-size: 2rem;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.modal-content ul {
    margin: 1.2rem 0 1.2rem 1.2rem;
    padding-left: 1.2rem;
}

.modal-content li {
    margin-bottom: 0.7em;
    color: #333;
    font-size: 1.05rem;
    line-height: 1.6;
}

.modal-content p {
    color: #444;
    font-size: 1rem;
    margin-bottom: 1rem;
}

.modal-content strong {
    color: #2563eb;
}

.close {
    position: absolute;
    top: 1.2rem; right: 1.2rem;
    font-size: 1.7rem;
    color: #2563eb;
    background: #e0e7ff;
    border-radius: 50%;
    width: 2.2rem;
    height: 2.2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
    border: none;
    outline: none;
    z-index: 10;
}
.close:hover, .close:focus {
    background: #2563eb;
    color: #fff;
}

@media (max-width: 600px) {
    .modal-content {
        padding: 1.2rem 0.7rem 1.2rem 0.7rem;
        max-width: 98vw;
    }
    .modal-content h2 {
        font-size: 1.3rem;
    }
}

.btn-spinner {
    margin-left: 8px;
    font-size: 1.2em;
    vertical-align: middle;
}
.signup-btn.disabled,
.signin-btn.disabled {
    opacity: 0.7;
    cursor: not-allowed;
    pointer-events: none;
}
</style>
<!-- Privacy Policy Modal -->
<div id="privacyModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000; overflow-y: auto; padding: 20px;">
    <div class="modal-content" style="background-color: white; max-width: 700px; margin: 40px auto; border-radius: 12px; position: relative; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
        <span class="close" id="closePrivacyModal" style="position: absolute; right: 24px; top: 24px; font-size: 24px; color: #9ca3af; cursor: pointer; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">&times;</span>
        <h2 style="color: #111827; font-size: 24px; padding: 24px 24px 16px; margin: 0; border-bottom: 1px solid #e5e7eb;">Privacy Policy</h2>
        <div class="modal-body" style="padding: 24px; color: #4b5563; line-height: 1.6;">
            <p style="margin-bottom: 16px;">
                MinglaGawa values your privacy. This policy explains how we collect, use, and protect your information:
            </p>
            <ul style="list-style-type: disc; margin-left: 20px; margin-bottom: 16px;">
                 <li><strong>Information Collection:</strong> We collect personal information such as your name, email, contact number, and payment details when you register or use our services.</li>
                <li><strong>Use of Information:</strong> Your information is used to provide and improve our services, process payments, and communicate with you.</li>
                <li><strong>Sharing:</strong> We do not sell your personal information. We may share it with trusted third parties only as necessary to operate the platform (e.g., payment processors).</li>
                <li><strong>Security:</strong> We implement security measures to protect your data from unauthorized access.</li>
                <li><strong>Cookies:</strong> MinglaGawa uses cookies to enhance your experience. You can disable cookies in your browser settings.</li>
                <li><strong>Access & Correction:</strong> You may access and update your personal information in your account settings.</li>
                <li><strong>Changes:</strong> We may update this policy. We will notify you of significant changes via email or platform notice.</li>
            </ul>
            <p style="margin-bottom: 16px;">
                For privacy concerns, contact us at <a href="mailto:privacy@minglagawa.com" style="color: #2563eb; text-decoration: none;">privacy@minglagawa.com</a>
            </p>
        </div>
    </div>
</div>

<!-- Terms of Service Modal -->
<div id="termsModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000; overflow-y: auto; padding: 20px;">
    <div class="modal-content" style="background-color: white; max-width: 700px; margin: 40px auto; border-radius: 12px; position: relative; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
        <span class="close" id="closeTermsModal" style="position: absolute; right: 24px; top: 24px; font-size: 24px; color: #9ca3af; cursor: pointer; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">&times;</span>
        <h2 style="color: #111827; font-size: 24px; padding: 24px 24px 16px; margin: 0; border-bottom: 1px solid #e5e7eb;">Terms of Service</h2>
        <div class="modal-body" style="padding: 24px; color: #4b5563; line-height: 1.6;">
            <p style="margin-bottom: 16px;">
                Welcome to MinglaGawa! By using our platform, you agree to the following terms:
            </p>
            <ul style="list-style-type: disc; margin-left: 20px; margin-bottom: 16px;">
                <li><strong>Eligibility:</strong> You must be at least 18 years old to use our services.</li>
                <li><strong>Account Responsibility:</strong> You are responsible for maintaining the confidentiality of your account and password.</li>
                <li><strong>Service Use:</strong> You agree to use MinglaGawa only for lawful purposes and not to engage in any fraudulent or harmful activity.</li>
                <li><strong>Payments:</strong> All payments and transactions must be made through the platform's approved methods.</li>
                <li><strong>Platform Commission:</strong> MinglaGawa charges a commission fee on each completed transaction. The commission is automatically deducted from the freelancer's earnings before payout.</li>
                <li><strong>Content:</strong> You are responsible for any content you post. Do not post anything illegal, offensive, or infringing.</li>
                <li><strong>Termination:</strong> We reserve the right to suspend or terminate your account for violations of these terms.</li>
                <li><strong>Changes:</strong> MinglaGawa may update these terms at any time. Continued use of the platform means you accept the new terms.</li>
            </ul>
            <p style="margin-bottom: 16px;">
                For questions, contact us at <a href="mailto:support@minglagawa.com" style="color: #2563eb; text-decoration: none;">support@minglagawa.com</a>
            </p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Modal elements
    const privacyModal = document.getElementById('privacyModal');
    const termsModal = document.getElementById('termsModal');
    const closePrivacy = document.getElementById('closePrivacyModal');
    const closeTerms = document.getElementById('closeTermsModal');
    
    // Show modals
    document.getElementById('showPrivacyModal').addEventListener('click', function(e) {
        e.preventDefault();
        privacyModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    });
    
    document.getElementById('showTermsModal').addEventListener('click', function(e) {
        e.preventDefault();
        termsModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    });
    
    // Close buttons
    closePrivacy.addEventListener('click', function() {
        privacyModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });
    
    closeTerms.addEventListener('click', function() {
        termsModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });
    
    // Close on outside click
    window.addEventListener('click', function(event) {
        if (event.target == privacyModal) {
            privacyModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        if (event.target == termsModal) {
            termsModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
    
    // Close on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            privacyModal.style.display = 'none';
            termsModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
});
</script>