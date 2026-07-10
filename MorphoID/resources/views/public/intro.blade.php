<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=1280">
    <title>Welcome | Morpho.ID</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/intro.css', 'resources/js/intro.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero-collab-logo {
            max-height: 75px !important;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(2px 4px 6px rgba(0, 0, 0, 0.6)) drop-shadow(0 0 8px rgba(255, 255, 255, 0.15));
            opacity: 0.95;
        }
        @media (max-width: 768px) {
            .hero-collab-logo { max-height: 40px !important; }
            .hero-meta-logo { max-height: 50px !important; }
        }
    </style>
</head>

<body>
    <img src="https://rghwatxwpjdrwcktsxbo.supabase.co/storage/v1/object/public/natey/Bangunan-Suluh-Budiman2-1024x686-1.jpeg"
        alt="bg-suluh-budiman" class="bg-watermark">

    <div class="intro-wrapper">
        <div id="particles-js"></div>

        <nav class="top-nav">
            <div class="logo">Morpho<span>.</span>ID</div>
                        <div class="text-nav">
                <a href="javascript:void(0)" onclick="document.getElementById('perkhidmatan').scrollIntoView({behavior: 'smooth'})" style="cursor: pointer; text-decoration: none; color: inherit;">Service</a>
                <a href="javascript:void(0)" onclick="document.getElementById('wbl-info').scrollIntoView({behavior: 'smooth'})" style="cursor: pointer; text-decoration: none; color: inherit;">WBL Info</a>
                <a href="javascript:void(0)" onclick="document.getElementById('pasukan-kami').scrollIntoView({behavior: 'smooth'})" style="cursor: pointer; text-decoration: none; color: inherit;">FSM-Labs Members</a>
                <a href="javascript:void(0)" onclick="document.getElementById('dev-team').scrollIntoView({behavior: 'smooth'})" style="cursor: pointer; text-decoration: none; color: inherit;">Dev Team</a>
            </div>
            <div class="fstem-brand">
                <span class="fstem-text">FSM</span>
                <span class="fstem-sub">Faculty of Science & Mathematic</span>
                <span class="fstem-sub" style="margin-top: 2px;">Universiti Pendidikan Sultan Idris</span>
            </div>

        </nav>
        <main class="content-box">
            <div class="hero-image-wrapper">
                <!-- The Glowing Portal Base -->
                <div class="cyber-portal">
                    <div class="portal-ring ring-1"></div>
                    <div class="portal-ring ring-2"></div>
                    <div class="portal-ring ring-3"></div>
                </div>

                <!-- The Emerging Logo -->
                <div class="logo-portal"></div>
            </div>

            <!-- COLLABORATION LOGOS (HERO SECTION) -->
            <div class="hero-collab-wrapper scroll-reveal" style="animation-delay: 0.5s;">
                <p class="hero-collab-text">In Collaboration With</p>
                <div class="hero-collab-logos">
                    <img src="{{ asset('images/fsm_logo_transparent.png') }}" alt="FSM Logo" class="hero-collab-logo">
                    <div class="hero-collab-divider">X</div>
                    <img src="{{ asset('images/meta_logo_transparent.png') }}" alt="Meta Logo" class="hero-collab-logo hero-meta-logo">
                </div>
            </div>

            <h1 style="line-height: 1.2; margin-bottom: 15px;">MORPHOLOGICAL IDENTIFICATION <span
                    style="display: block; margin-top: 5px;">DATABASE SYSTEM</span></h1>

            <p>A centralized database specifically design to record morphological and elemental analysis to facilitate
                record management of laboratory specimens.</p>

            <div class="action-group">
                <a href="/login" class="btn btn-primary sonar-pulse">Login</a>
                <a href="/registration" class="btn btn-secondary">Register</a>
            </div>

            <div style="margin-top: 2.5rem; animation: fadeUp 1s ease 0.6s backwards;">
                <p style="font-size: 0.85rem; color: #a0aec0; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px;">Also available on mobile</p>
                <a href="/downloads/MorphoID.apk" download class="btn" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.15); color: #e2e8f0; display: inline-flex; align-items: center; gap: 10px; font-size: 0.95rem; padding: 10px 20px; transition: all 0.3s ease; backdrop-filter: blur(5px);" onmouseover="this.style.background='rgba(164, 198, 57, 0.15)'; this.style.borderColor='#a4c639';" onmouseout="this.style.background='rgba(255, 255, 255, 0.05)'; this.style.borderColor='rgba(255, 255, 255, 0.15)';">
                    <i class="fa-brands fa-android" style="color: #a4c639; font-size: 1.3rem;"></i>
                    Download Morpho.ID App (APK)
                </a>
            </div>

        </main>
    </div>

    <section id="perkhidmatan" class="services-section">
        <div class="section-header scroll-reveal">
            <span class="badge-tag badge-cyan">Services</span>
            <h2>Morpho.ID Based Services</h2>
            <p>A systematic and comprehensive laboratory specimen management platform.</p>
        </div>

        <div class="services-grid">
            <div class="service-card scroll-reveal">
                <div class="icon-box icon-green">🔬</div>
                <h3>Specimen Record Management</h3>
                <p>Register, store, and update specimen data with a secure and fast centralized
                    database.</p>
            </div>

            <div class="service-card scroll-reveal" style="transition-delay: 0.2s;">
                <div class="icon-box icon-blue">🧬</div>
                <h3>Ultra Structure Classification</h3>
                <p>A smart tagging system for categorizing anatomical features, physical
                    properties, and genetic structures in detail.</p>
            </div>

            <div class="service-card scroll-reveal" style="transition-delay: 0.4s;">
                <div class="icon-box icon-cyan">📊</div>
                <h3>Analysis & Comparison</h3>
                <p>Display and compare biological characteristics between various specimens to
                    facilitate research and laboratory reference.</p>
            </div>
        </div>
    </section>

    <hr class="custom-divider scroll-reveal" />

    <!-- WBL INFO SECTION -->
    <section id="wbl-info" class="wbl-section" style="padding: 4rem 2rem; max-width: 1200px; margin: 0 auto; text-align: center;">
        <div class="section-header scroll-reveal">
            <span class="badge-tag badge-cyan">INFO WBL</span>
            <h2>Work-Based Learning (WBL)</h2>
            <p>Work-Based Learning (WBL) programme overview and information.</p>
        </div>
        <div class="scroll-reveal" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 2.5rem; margin-top: 2rem; backdrop-filter: blur(10px); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);">
            <p style="font-size: 1.1rem; line-height: 1.8; color: #e2e8f0; text-align: justify; margin-bottom: 0;">
                The <strong>Work-Based Learning (WBL)</strong> programme is an innovative educational approach that integrates academic learning at the university with real-world industry experience. Through this programme, students are exposed to professional working environments, allowing them to develop practical skills, industry networks, and hands-on competencies that align closely with current industry demands. By bridging the gap between theory and practice, WBL ensures graduates are highly employable and industry-ready.
            </p>
        </div>
    </section>

    <hr class="custom-divider scroll-reveal" style="margin: 4rem auto;" />

    <section id="pasukan-kami" class="team-section">
        <div class="section-header scroll-reveal">
            <span class="badge-tag badge-purple">FSM-LABS MEMBERS</span>
            <h2>FSM-Labs Members</h2>
            <p>Experts behind the innovation of the Morpho.ID system.</p>
        </div>

        <div class="team-grid">
            <div class="team-card scroll-reveal">
                <div class="img-wrapper">
                    <img src="https://rghwatxwpjdrwcktsxbo.supabase.co/storage/v1/object/public/avatars/dr%20munirah.jfif" alt="PensYesrah 1">
                </div>
                <h3>ChM. Dr. Siti Munirah Binti Sidik</h3>
                <span class="role">University Lecturer (Grade DS13)</span>
                <p>Inorganic Chemistry (Materials and Catalysis)</p>
            </div>

            <div class="team-card scroll-reveal">
                <div class="img-wrapper">
                    <img src="https://rghwatxwpjdrwcktsxbo.supabase.co/storage/v1/object/public/avatars/dr%20linda.jfif" alt="Developer 1">
                </div>
                <h3>Dr. Norlinda binti Daud</h3>
                <span class="role">University Lecturer (Grade DS13)</span>
                <p>polymer composites</p>
            </div>

            <div class="team-card scroll-reveal">
                <div class="img-wrapper">
                    <img src="https://rghwatxwpjdrwcktsxbo.supabase.co/storage/v1/object/public/avatars/dr%20izan.jfif" alt="UIUX Designer">
                </div>
                <h3>Dr. Izan Roshawaty binti Mustapa</h3>
                <span class="role">University Lecturer (Grade DS13)</span>
                <p>Material Physics</p>
            </div>

            <div class="team-card scroll-reveal">
                <div class="img-wrapper">
                    <img src="https://rghwatxwpjdrwcktsxbo.supabase.co/storage/v1/object/public/avatars/dr%20haslinda.jfif" alt="UIUX Designer">
                </div>
                <h3>ChM. Dr. Wan Haslinda binti Wan Ahmad</h3>
                <span class="role">University Lecturer (Grade DS13)</span>
                <p>Analytical Chemistry</p>
            </div>

            <div class="team-card scroll-reveal">
                <div class="img-wrapper">
                    <img src="https://rghwatxwpjdrwcktsxbo.supabase.co/storage/v1/object/public/avatars/dr%20alene.jfif" alt="UIUX Designer">
                </div>
                <h3>Dr. Alene binti Tawang</h3>
                <span class="role">University Lecturer (Grade DS13)</span>
                <p>Animal reproductive technologies, endocrinology, cell morphology and anatomy, animal cell culture</p>
            </div>

            <div class="team-card scroll-reveal">
                <div class="img-wrapper">
                    <img src="https://rghwatxwpjdrwcktsxbo.supabase.co/storage/v1/object/public/avatars/dr%20nafizah.jfif" alt="Lecturer Baru">
                </div>
                <h3>ASSOCIATE PROFESSOR DR. NOR NAFIZAH BINTI MOHD NOOR</h3>
                <span class="role">University Lecturer (Grade DS14)</span>
                <p>Botany, Plant Anatomy, Plant Systematic and Phytochemistry</p>
            </div>

            <!-- Dekan -->
            <div class="team-card scroll-reveal">
                <div class="img-wrapper">
                    <img src="https://rghwatxwpjdrwcktsxbo.supabase.co/storage/v1/object/public/avatars/Dean.jfif" alt="Dekan">
                </div>
                <h3>Associate Prof.  Dr.- Ing. Maizatul Hayati binti Mohamad Yatim</h3>
                <span class="role">DEAN</span>
                <p>Human-Computer Interaction (Game Usability)</p>
            </div>

            <!-- Workbased Learning Lecturers -->
            <div class="team-card scroll-reveal">
                <div class="img-wrapper">
                    <img src="https://rghwatxwpjdrwcktsxbo.supabase.co/storage/v1/object/public/avatars/aslina.jpg" alt="Lecturer WBL 1">
                </div>
                <h3>Associate Prof. Dr. Aslina bt. Saad</h3>
                <span class="role">WBL Specialist Lecturer</span>
                <p>Fakulti Sains dan Matematik</p>
            </div>

            <div class="team-card scroll-reveal">
                <div class="img-wrapper">
                    <img src="https://rghwatxwpjdrwcktsxbo.supabase.co/storage/v1/object/public/avatars/rashidi.jpg" alt="Lecturer WBL 2">
                </div>
                <h3>Encik Rasyidi bin Johan</h3>
                <span class="role">WBL Specialist Lecturer</span>
                <p>Web Framework Development</p>
            </div>

            <div class="team-card scroll-reveal">
                <div class="img-wrapper">
                    <img src="https://rghwatxwpjdrwcktsxbo.supabase.co/storage/v1/object/public/avatars/nrohisham.png" alt="Lecturer WBL 3">
                </div>
                <h3>Dr. Norhisham bin Mohamad Nordin</h3>
                <span class="role">WBL Specialist Lecturer</span>
                <p>Online Business and Online Learning</p>
            </div>
        </div>

        <hr class="custom-divider scroll-reveal" style="margin: 6rem auto;" />

        <div id="dev-team" class="section-header scroll-reveal">
            <span class="badge-tag badge-pink">Development Team</span>
            <h2>Development Team</h2>
            <p>The core engineers and architects powering Morpho.ID.</p>
        </div>

        <div class="team-grid">
            <div class="team-card scroll-reveal">
                <div class="img-wrapper">
                    <img src="https://rghwatxwpjdrwcktsxbo.supabase.co/storage/v1/object/public/avatars/Gambar.jpg" alt="Anas">
                </div>
                <h3>ANAS MALIQI BIN ROSLY</h3>
                <span class="role">PRINCIPAL FULL-STACK ENGINEER & CO-LEAD</span>
                <p>The technical co-mastermind driving the intricate backend logic and orchestrating flawless system integrations alongside the Project Lead.</p>
            </div>

            <div class="team-card scroll-reveal">
                <div class="img-wrapper">
                    <img src="https://rghwatxwpjdrwcktsxbo.supabase.co/storage/v1/object/public/avatars/Lutfi%20-%20D20241114118.JPEG" alt="Lutfi">
                </div>
                <h3>MUHAMMAD LUTFI BIN ZULKIFLI</h3>
                <span class="role">PRINCIPAL SOFTWARE ENGINEER & PROJECT LEAD</span>
                <p>The visionary leader overseeing the entire project lifecycle and engineering the highly scalable core foundation of Morpho.ID.</p>
            </div>

            <div class="team-card scroll-reveal">
                <div class="img-wrapper">
                    <img src="https://rghwatxwpjdrwcktsxbo.supabase.co/storage/v1/object/public/natey/D20241110986.jpg" alt="Khairul">
                </div>
                <h3>KHAIRUL IKHWAN BIN MAT YUNUS</h3>
                <span class="role">FRONTEND ARCHITECT & SYSTEM TESTER</span>
                <p>Transforms design concepts into reality while aggressively auditing the codebase to maintain absolute system stability.</p>
            </div>
        </div>
    </section>

    <!-- ==========================================
         FOOTER SECTION
         ========================================== -->
    <footer class="intro-footer">
        <div class="footer-container">
            <div class="footer-col brand-col">
                <div class="footer-logo-placeholder">
                    <span class="fsm-box">FSM</span><span class="upsi-box">UPSI</span>
                </div>
                <div class="footer-address">
                    Faculty of Science and Mathematics<br>
                    Kampus Sultan Azlan Shah (KSAS),<br>
                    Universiti Pendidikan Sultan Idris (UPSI),<br>
                    35900 Tanjung Malim, Perak<br>
                    Malaysia<br><br>
                    605 450 7201 / +605-450 6000
                </div>
            </div>

            <div class="footer-col links-col">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="https://directory.upsi.edu.my/" target="_blank" rel="noopener">Directory (Expert UPSI)</a></li>
                    <li><a href="https://login.upsi.edu.my/" target="_blank" rel="noopener">Login (Portal)</a></li>
                    <li><a href="#">Sitemap</a></li>
                    <li><a href="https://bendahari.upsi.edu.my/" target="_blank" rel="noopener">Bursar Department</a></li>
                    <li><a href="https://rmic.upsi.edu.my/" target="_blank" rel="noopener">RMIC</a></li>
                    <li><a href="https://bsm.upsi.edu.my/ms/" target="_blank" rel="noopener">BSM</a></li>
                </ul>
            </div>

            <div class="footer-col connect-col">
                <h4>Connect With US</h4>
                <div class="social-icons">
                    <a href="https://www.facebook.com/facscimateUPSI/" target="_blank" rel="noopener" class="social-icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://www.tiktok.com/@fsmupsimedia" target="_blank" rel="noopener" class="social-icon"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="https://www.instagram.com/fsm_upsi/" target="_blank" rel="noopener" class="social-icon"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://www.youtube.com/@fsmupsimedia5456" target="_blank" rel="noopener" class="social-icon"><i class="fa-brands fa-youtube"></i></a>
                </div>

                <h4>Working Hours</h4>
                <p>Monday to Friday<br>8.00am - 5.00pm</p>

                <h4 style="margin-top: 1.5rem;">Closed</h4>
                <p>Saturday to Sunday<br>Closed on public holiday</p>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-bottom-links">
                <a href="https://fskik.upsi.edu.my/privacy-policy/" target="_blank" rel="noopener">Privacy Policy</a> | <a href="https://fskik.upsi.edu.my/security-policy/" target="_blank" rel="noopener">Security Policy</a> | <a href="https://fskik.upsi.edu.my/disclaimer/" target="_blank" rel="noopener">Disclaimer</a>
            </div>
            <div class="copyright">
                copyright@Faculty Computing and Meta - Technology
            </div>
        </div>
    </footer>

    <!-- Particles & Vanilla Tilt Scripts for Neural Bio-Mesh Theme -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
    <script>
        // Inisialisasi Vanilla Tilt pada All kad (Kesan Magnetik 3D Parallax)
        VanillaTilt.init(document.querySelectorAll(".service-card, .team-card"), {
            max: 10,
            speed: 400,
            glare: true,
            "max-glare": 0.2,
        });

        // Inisialisasi Particles JS (Jaringan Neural / Bio-Mesh -> Quantum Biogenesis)
        particlesJS("particles-js", {
            "particles": {
                "number": {
                    "value": 100,
                    "density": {
                        "enable": true,
                        "value_area": 800
                    }
                },
                "color": {
                    "value": ["#00F0FF", "#9D4EDD"]
                },
                "shape": {
                    "type": "circle"
                },
                "opacity": {
                    "value": 0.8,
                    "random": true
                },
                "size": {
                    "value": 4,
                    "random": true
                },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#00F0FF",
                    "opacity": 0.3,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 2,
                    "direction": "none",
                    "random": true,
                    "straight": false,
                    "out_mode": "out",
                    "bounce": false,
                }
            },
            "interactivity": {
                "detect_on": "window",
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "repulse"
                    },
                    "onclick": {
                        "enable": true,
                        "mode": "push"
                    },
                    "resize": true
                },
                "modes": {
                    "repulse": {
                        "distance": 100,
                        "duration": 0.4
                    },
                    "push": {
                        "particles_nb": 4
                    }
                }
            },
            "retina_detect": true
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
