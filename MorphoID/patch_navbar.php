<?php
$file = 'C:\laragon\www\MorphoID\resources\css\index.css';
$content = file_get_contents($file);

// Find the start of NAVBAR & MENU NAVIGASI (PREMIUM) and replace until .navbar-admin
$pattern = '/\/\* ==========================================\s*NAVBAR & MENU NAVIGASI \(PREMIUM\)\s*========================================== \*\/(.*?)\.navbar-admin/s';

$replacement = "/* ==========================================
   NAVBAR & MENU NAVIGASI (PREMIUM)
   ========================================== */
.navbar-wrapper {
    position: relative;
    z-index: 1000;
}

.navbar {
    position: relative;
    background: rgba(5, 9, 20, 0.6);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    padding: 1rem 4rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(0, 240, 255, 0.15);
}

.navbar-admin";

$newContent = preg_replace($pattern, $replacement, $content);
file_put_contents($file, $newContent);
echo "Navbar patched successfully!";
