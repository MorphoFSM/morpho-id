<?php
$content = <<<'EOD'
<style>
/* ==========================================
   GLOBAL NAVBAR STYLES
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
.navbar-admin {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1.5rem;
    padding: 1rem;
    background: rgba(5, 9, 20, 0.4);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(0, 240, 255, 0.1);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5); 
}
.navbar::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    height: 1px;
    background: linear-gradient(90deg, transparent, #00F0FF, transparent);
    opacity: 0.5;
}
@keyframes fluidText {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.navbar .logo {
    font-weight: 800;
    font-size: 1.8rem;
    letter-spacing: -0.5px;
    text-decoration: none;
    background: linear-gradient(270deg, #00F0FF, #9D4EDD, #E0AAFF, #00F0FF);
    background-size: 300% 300%;
    -webkit-background-clip: text;
    color: transparent;
    animation: fluidText 5s ease infinite;
    filter: drop-shadow(0 0 10px rgba(0, 240, 255, 0.3));
}
.navbar .logo span {
    color: #fff;
    -webkit-text-fill-color: #fff;
}
.navbar-menu {
    display: flex;
    align-items: center;
    gap: 2rem;
}
.navbar-menu .nav-item {
    color: rgba(255, 255, 255, 0.7) !important;
    text-decoration: none !important;
    font-weight: 600 !important;
    font-size: 0.95rem !important;
    position: relative;
    padding: 0.5rem 0;
    transition: all 0.3s ease;
    background: none !important;
    border: none !important;
}
.navbar-menu .nav-item::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 2px;
    background: #00F0FF;
    transition: width 0.3s ease;
    box-shadow: 0 0 10px #00F0FF;
    border-radius: 2px;
}
.navbar-menu .nav-item:hover {
    color: #ffffff !important;
    text-shadow: 0 0 10px rgba(255, 255, 255, 0.5) !important;
}
.navbar-menu .nav-item:hover::after, .navbar-menu .nav-item.active::after {
    width: 100%;
}
.navbar-menu .nav-item.active {
    color: #ffffff !important;
    text-shadow: 0 0 15px rgba(255, 255, 255, 0.4) !important;
}
.nav-divider {
    width: 1px;
    height: 24px;
    background: linear-gradient(to bottom, transparent, rgba(255, 255, 255, 0.3), transparent);
    margin: 0 0.5rem;
}
.btn-manage {
    background: linear-gradient(135deg, rgba(0, 240, 255, 0.1), rgba(0, 180, 255, 0.05)) !important;
    color: #00F0FF !important;
    border: 1px solid rgba(0, 240, 255, 0.3) !important;
    padding: 0.6rem 1.4rem !important;
    border-radius: 50px !important;
    font-weight: 600 !important;
    font-size: 0.85rem !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    text-decoration: none !important;
    backdrop-filter: blur(8px) !important;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 0.6rem !important;
    box-shadow: 0 0 15px rgba(0, 240, 255, 0.05) !important;
    width: auto !important;
}
.btn-manage:hover {
    background: #00F0FF !important;
    color: #050914 !important;
    border-color: #00F0FF !important;
    transform: translateY(-3px) scale(1.02) !important;
    box-shadow: 0 8px 25px rgba(0, 240, 255, 0.4) !important;
}
.btn-manage svg {
    transition: transform 0.3s ease;
}
.btn-manage:hover svg {
    transform: rotate(15deg) scale(1.1);
}
.btn-logout {
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
    background: rgba(239, 68, 68, 0.1) !important;
    color: #ef4444 !important;
    border: 1px solid rgba(239, 68, 68, 0.3) !important;
    padding: 0.55rem 1.3rem !important;
    border-radius: 50px !important;
    font-weight: 600 !important;
    font-size: 0.9rem !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    box-shadow: none !important;
    margin: 0 !important;
}
.btn-logout:hover {
    background: #ef4444 !important;
    color: white !important;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4) !important;
    transform: translateY(-2px) !important;
}
.btn-logout svg {
    transition: transform 0.3s ease !important;
}
.btn-logout:hover svg {
    transform: translateX(3px) !important;
}
@media (max-width: 768px) {
    .navbar {
        flex-direction: column;
        padding: 1rem;
        gap: 1rem;
    }
    .navbar-admin {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>
<div class="navbar-wrapper">
    <nav class="navbar">
        <a href="/index" class="logo">Morpho<span>.</span>ID</a>

        <div class="navbar-menu">
            <a href="/index" class="nav-item">Home</a>
            <a href="/explore" class="nav-item">Explore</a>
            <a href="{{ route('profile') }}" class="nav-item">Profile</a>

            <div class="nav-divider"></div>
            
            @if(Auth::guard('admin')->check() || Auth::check())
                <form action="/logout" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-manage" style="color: #00F0FF !important; border-color: #00F0FF !important;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                    Login
                </a>
            @endif
        </div>
    </nav>

    @if(Auth::guard('admin')->check())
    <div class="navbar-admin">
        <a href="/admin" class="btn-manage">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
            Specimen Management
        </a>
        <a href="/admin/visits" class="btn-manage">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            Visit Management
        </a>
        <a href="{{ route('admin.role_requests') }}" class="btn-manage" style="position: relative;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><polyline points="16 11 18 13 22 9"></polyline></svg>
            Role Requests
            @php
                $pendingRequestsCount = \App\Models\RoleRequest::where('status', 'pending')->count();
            @endphp
            @if($pendingRequestsCount > 0)
                <span style="position: absolute; top: -8px; right: -8px; background: #FF00E5; color: white; font-size: 0.7rem; font-weight: bold; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; border-radius: 50%; box-shadow: 0 0 8px rgba(255, 0, 229, 0.6);">{{ $pendingRequestsCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.audit') }}" class="btn-manage">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            Activity Logs
        </a>
    </div>
    @endif
</div>

@include('components.chatbot')
<style>
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
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
      Swal.fire('Success', '{{ session('success') }}', 'success');
    @endif
    @if(session('error'))
      Swal.fire('Error', '{{ session('error') }}', 'error');
    @endif
  });
</script>
EOD;

file_put_contents('C:\laragon\www\MorphoID\resources\views\components\navbar.blade.php', $content);
echo "Cleaned up navbar.blade.php!";
?>
