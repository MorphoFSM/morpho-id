<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1280">
    <title>Role Requests | Morpho.ID Admin</title>

    @vite(['resources/css/dashboard.css', 'resources/css/role_requests.css', 'resources/js/dashboard.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    @include('components.watermark')
    <div id="particles-js"></div>
    @include('components.navbar')

    <main class="dashboard-container">
        <a href="/admin" class="back-btn" style="
            display: inline-flex; 
            align-items: center; 
            gap: 8px; 
            color: var(--primary, #00F0FF); 
            text-decoration: none; 
            margin-bottom: 2rem; 
            padding: 8px 18px; 
            border: 1px solid rgba(0, 240, 255, 0.3); 
            border-radius: 10px; 
            background: rgba(0, 240, 255, 0.05); 
            font-weight: 700; 
            font-size: 0.9rem; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 240, 255, 0.05);
            backdrop-filter: blur(8px);
        " onmouseover="this.style.background='rgba(0, 240, 255, 0.15)'; this.style.borderColor='var(--primary, #00F0FF)'; this.style.boxShadow='0 0 20px rgba(0, 240, 255, 0.3)'; this.style.transform='translateX(-3px)';" onmouseout="this.style.background='rgba(0, 240, 255, 0.05)'; this.style.borderColor='rgba(0, 240, 255, 0.3)'; this.style.boxShadow='0 4px 15px rgba(0, 240, 255, 0.05)'; this.style.transform='translateX(0)';">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Dashboard
        </a>

        <div class="requests-container">
            <div class="requests-header">
                <h2>Role Change Requests</h2>
                <p>Manage pending requests from users asking for System Administrator privileges.</p>
            </div>

            @if(session('success'))
                <div class="alert-box alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert-box alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @if($requests->count() > 0)
                @foreach($requests as $request)
                <div class="request-card">
                    <div class="request-info">
                        <img src="{{ $request->user->avatar ? $request->user->avatar : 'https://ui-avatars.com/api/?name='.urlencode($request->user->name).'&background=random' }}" alt="Avatar" class="request-avatar">
                        <div class="request-details">
                            <h4>{{ $request->user->name }}</h4>
                            <p>User ID: {{ $request->user->userid }} | Email: {{ $request->user->email ?? 'N/A' }}</p>
                            <p style="margin-top: 0.3rem; font-size: 0.8rem;">Requested on: {{ $request->created_at->format('d M Y, h:i A') }}</p>
                            <span class="badge-tag badge-pink" style="font-size: 0.7rem; padding: 2px 6px; margin-top: 5px; display: inline-block;">User requesting upgrade</span>
                        </div>
                    </div>
                    <div class="request-actions">
                        <form action="{{ route('role_requests.approve', $request->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-approve" onclick="event.preventDefault(); Swal.fire({title: 'Confirmation', text: 'Approve this request and make user an Admin?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Yes'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">Approve</button>
                        </form>
                        <form action="{{ route('role_requests.reject', $request->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-reject" onclick="event.preventDefault(); Swal.fire({title: 'Confirmation', text: 'Reject this request?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Yes'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">Reject</button>
                        </form>
                    </div>
                </div>
                @endforeach
            @endif

            @if(isset($pendingAdmins) && $pendingAdmins->count() > 0)
                @foreach($pendingAdmins as $pAdmin)
                <div class="request-card" style="border-left: 4px solid #9D4EDD;">
                    <div class="request-info">
                        <img src="{{ $pAdmin->avatar ? $pAdmin->avatar : 'https://ui-avatars.com/api/?name='.urlencode($pAdmin->name).'&background=random' }}" alt="Avatar" class="request-avatar">
                        <div class="request-details">
                            <h4>{{ $pAdmin->name }}</h4>
                            <p>User ID: {{ $pAdmin->userid }} | Email: {{ $pAdmin->email ?? 'N/A' }} | Institution: {{ $pAdmin->institusi }}</p>
                            <p style="margin-top: 0.3rem; font-size: 0.8rem;">Registered on: {{ $pAdmin->created_at ? $pAdmin->created_at->format('d M Y, h:i A') : 'Unknown Date' }}</p>
                            <span class="badge-tag" style="background: rgba(157, 78, 221, 0.2); color: #9D4EDD; font-size: 0.7rem; padding: 2px 6px; margin-top: 5px; display: inline-block;">New Admin Registration</span>
                        </div>
                    </div>
                    <div class="request-actions">
                        <form action="{{ route('admin.role_requests.approve_pending', $pAdmin->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-approve" onclick="event.preventDefault(); Swal.fire({title: 'Confirmation', text: 'Approve this new Administrator registration? An email will be sent to them to verify their account.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Yes, Approve'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">Approve</button>
                        </form>
                        <form action="{{ route('admin.role_requests.reject_pending', $pAdmin->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-reject" onclick="event.preventDefault(); Swal.fire({title: 'Confirmation', text: 'Reject and delete this registration? A rejection email will be sent.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Yes, Reject'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">Reject</button>
                        </form>
                    </div>
                </div>
                @endforeach
            @endif

            @if($requests->count() == 0 && (!isset($pendingAdmins) || $pendingAdmins->count() == 0))
                <div class="empty-state">
                    <h3>No Pending Requests</h3>
                    <p>There are currently no users requesting a role change or new admin registrations.</p>
                </div>
            @endif
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        particlesJS("particles-js", {
            "particles": {
                "number": { "value": 80, "density": { "enable": true, "value_area": 800 } },
                "color": { "value": ["#00F0FF", "#9D4EDD"] }, 
                "shape": { "type": "circle" },
                "opacity": { "value": 0.8, "random": true },
                "size": { "value": 4, "random": true },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#00F0FF", 
                    "opacity": 0.3,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 1.5,
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
                    "onhover": { "enable": true, "mode": "repulse" }, 
                    "onclick": { "enable": true, "mode": "push" }, 
                    "resize": true
                },
                "modes": {
                    "repulse": { "distance": 100, "duration": 0.4 },
                    "push": { "particles_nb": 4 }
                }
            },
            "retina_detect": true
        });
    </script>
</body>
</html>
