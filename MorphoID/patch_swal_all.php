<?php
$files = [
    'C:\laragon\www\MorphoID\resources\css\role_requests.css',
    'C:\laragon\www\MorphoID\resources\css\admin_visit.css',
    'C:\laragon\www\MorphoID\resources\css\dashboard.css',
    'C:\laragon\www\MorphoID\resources\css\profile.css'
];

$swal_css = "

/* ==========================================
   SWEETALERT2 CUSTOM THEME (MORPHO.ID)
   ========================================== */
.swal2-popup {
    background: #0B132B !important;
    border: 1px solid rgba(0, 240, 255, 0.3) !important;
    border-radius: 12px !important;
    color: #FFFFFF !important;
    box-shadow: 0 10px 30px rgba(0, 240, 255, 0.15) !important;
}

.swal2-title {
    color: #00F0FF !important;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
}

.swal2-html-container {
    color: #8AA2B8 !important;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
}

.swal2-confirm {
    background: #00F0FF !important;
    color: #050914 !important;
    border-radius: 8px !important;
    font-weight: 700 !important;
    padding: 0.6rem 1.5rem !important;
    border: 1px solid #00F0FF !important;
    transition: all 0.3s ease !important;
}
.swal2-confirm:hover {
    box-shadow: 0 0 15px rgba(0, 240, 255, 0.4) !important;
}

.swal2-cancel {
    background: rgba(239, 68, 68, 0.1) !important;
    border: 1px solid #EF4444 !important;
    color: #EF4444 !important;
    border-radius: 8px !important;
    font-weight: 700 !important;
    padding: 0.6rem 1.5rem !important;
    transition: all 0.3s ease !important;
}
.swal2-cancel:hover {
    background: #EF4444 !important;
    color: #FFFFFF !important;
    box-shadow: 0 0 15px rgba(239, 68, 68, 0.4) !important;
}

.swal2-icon.swal2-warning {
    border-color: #EAB308 !important;
    color: #EAB308 !important;
}

.swal2-icon.swal2-success {
    border-color: #00F0FF !important;
    color: #00F0FF !important;
}
";

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, 'SWEETALERT2 CUSTOM THEME') === false) {
            file_put_contents($file, $content . $swal_css);
            echo "Patched $file\n";
        }
    }
}
