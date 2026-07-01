<?php
$file = 'C:\laragon\www\MorphoID\resources\css\index.css';
$content = file_get_contents($file);

// Find the start of .hero-section and everything after it until .katalog-card
$pattern = '/\.hero-section\s*\{.*?(?=\.katalog-card\s*\{)/s';

$replacement = ".hero-section {
    text-align: center;
    margin-top: 5rem;
    margin-bottom: 4rem;
    padding: 0 1rem;
}

.hero-section h1 {
    color: var(--primary);
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    letter-spacing: -0.5px;
}

.hero-section p {
    color: #E2E8F0; /* Cerahkan teks supaya lebih jelas */
    font-size: 1.1rem;
    font-weight: 500;
    line-height: 1.6;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5); /* Tambah shadow supaya timbul */
}

/* ==========================================
   KAD KATALOG CONTAINER
   ========================================== */
.katalog-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    padding: 0 2rem 5rem 2rem;
    max-width: 1300px;
    margin: 0 auto;
    width: 100%;
}

";

$newContent = preg_replace($pattern, $replacement, $content);
file_put_contents($file, $newContent);
echo "Patched successfully!";
