<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Morpho.ID</title>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/index.css', 'resources/css/profile.css'])
</head>
<body>
    <div id="particles-js"></div>

    @include('components.navbar')

    <div class="page-content">
        <div class="profile-container">
            <div class="profile-header">
                <h2>Profile Settings</h2>
                <p>Personalize your account details and avatar.</p>
            </div>

            @if(session('success'))
                <div class="alert-box alert-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error') || $errors->any())
                <div class="alert-box alert-error">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    {{ session('error') ?? $errors->first() }}
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
                @csrf
                
                <div class="avatar-section">
                    <label for="avatar" class="avatar-label" title="Click to change profile picture">
                        <img src="{{ $user->avatar ? $user->avatar : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}" alt="Profile Avatar" class="avatar-preview" id="avatarPreview">
                        <div class="avatar-upload-badge">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        </div>
                    </label>
                    <input type="file" name="avatar" id="avatar" accept="image/*" onchange="previewImage(event)" style="display: none;">
                </div>

                <div class="form-grid">
                    <div class="input-group" style="display: flex; align-items: stretch; gap: 10px;">
                        <div style="flex-grow: 1; position: relative;">
                            <input type="text" name="userid" id="userid" value="{{ $user->userid }}" readonly required style="cursor: default;">
                            <label for="userid">User ID / IC</label>
                        </div>
                        <button type="button" id="btnEditUserid" class="edit-btn" title="Edit User ID">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                        </button>
                    </div>

                    <div class="input-group" style="position: relative;">
                        <input type="text" id="role" value="{{ $role }}" readonly required>
                        <label for="role">ROLE</label>
                        @if(!Auth::guard('admin')->check())
                        <button type="button" id="btnChangeRole" style="position: absolute; right: 0; top: 0.8rem; background: rgba(0, 240, 255, 0.1); border: 1px solid var(--primary); color: var(--primary); font-size: 0.75rem; padding: 0.3rem 0.8rem; border-radius: 20px; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;" onmouseover="this.style.background='var(--primary)'; this.style.color='#000'; this.style.boxShadow='0 0 10px rgba(0, 240, 255, 0.5)'" onmouseout="this.style.background='rgba(0, 240, 255, 0.1)'; this.style.color='var(--primary)'; this.style.boxShadow='none'">Upgrade to Admin</button>
                        @endif
                    </div>

                    <div class="input-group full-width" style="display: flex; align-items: stretch; gap: 10px;">
                        <div style="flex-grow: 1; position: relative;">
                            <input type="text" name="name" id="name" value="{{ $user->name }}" required readonly style="cursor: default;">
                            <label for="name">Display Name</label>
                        </div>
                        <button type="button" id="btnEditName" class="edit-btn" title="Edit Name">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                        </button>
                    </div>

                    @if(isset($user->email))
                    <div class="input-group full-width" style="display: flex; align-items: stretch; gap: 10px;">
                        <div style="flex-grow: 1; position: relative;">
                            <input type="email" name="email" id="email" value="{{ $user->email }}" readonly required style="cursor: default;">
                            <label for="email">Email Address</label>
                        </div>
                        <button type="button" id="btnEditEmail" class="edit-btn" title="Edit Email">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                        </button>
                    </div>
                    @endif

                    @if(!Auth::guard('admin')->check())
                    <div class="input-group full-width" style="display: flex; align-items: stretch; gap: 10px;">
                        <div style="flex-grow: 1; position: relative;">
                            <input type="text" name="institusi" id="institusi" value="{{ $user->institusi ?? '' }}" readonly style="cursor: default;">
                            <label for="institusi">Institution / School</label>
                        </div>
                        <button type="button" id="btnEditInstitusi" class="edit-btn" title="Edit Institution">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                        </button>
                    </div>
                    @endif
                </div>

                <div id="saveActionContainer" style="display: none; margin-top: 2rem;">
                    <button type="button" class="btn-glow" id="btnSaveConfirm">Save Changes</button>
                </div>
            </form>
            
            <div style="margin-top: 3rem; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 2.5rem; text-align: center;">
                <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.95rem;">Danger Zone: Deleting your account is permanent.</p>
                <form id="deleteAccountForm" action="{{ route('profile.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" id="btnDeleteAccount" class="delete-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; vertical-align: middle;"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        Delete Account
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        particlesJS("particles-js", {
            "particles": {
                "number": { "value": 60, "density": { "enable": true, "value_area": 800 } },
                "color": { "value": "#00f0ff" },
                "shape": { "type": "circle" },
                "opacity": { "value": 0.3, "random": false },
                "size": { "value": 3, "random": true },
                "line_linked": { "enable": true, "distance": 150, "color": "#00f0ff", "opacity": 0.2, "width": 1 },
                "move": { "enable": true, "speed": 2, "direction": "none", "random": false, "straight": false, "out_mode": "out", "bounce": false }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": { "enable": true, "mode": "grab" },
                    "onclick": { "enable": true, "mode": "push" },
                    "resize": true
                },
                "modes": {
                    "grab": { "distance": 140, "line_linked": { "opacity": 0.5 } },
                    "push": { "particles_nb": 4 }
                }
            },
            "retina_detect": true
        });

        const btnSave = document.getElementById('saveActionContainer');
        const inputName = document.getElementById('name');
        const btnEditName = document.getElementById('btnEditName');
        const inputAvatar = document.getElementById('avatar');

        let isEdited = false;

        function showSaveButton() {
            if(!isEdited) {
                btnSave.style.display = 'block';
                isEdited = true;
            }
        }

        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('avatarPreview');
                output.src = reader.result;
            }
            if(event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
                showSaveButton();
            }
        }

        if(btnEditName) {
            btnEditName.addEventListener('click', function() {
                inputName.removeAttribute('readonly');
                inputName.style.cursor = 'text';
                inputName.focus();
            });
            inputName.addEventListener('input', function() {
                showSaveButton();
            });
        }

        const btnChangeRole = document.getElementById('btnChangeRole');
        if(btnChangeRole) {
            btnChangeRole.addEventListener('click', function() {
                Swal.fire({
                    title: 'Upgrade to Admin?',
                    text: "A request will be sent to the System Administrator for approval.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#00F0FF',
                    cancelButtonColor: 'rgba(255, 255, 255, 0.1)',
                    confirmButtonText: 'Yes, send request!',
                    cancelButtonText: 'Cancel',
                    background: '#0B0E14',
                    color: '#fff',
                    customClass: {
                        popup: 'swal-custom-popup',
                        confirmButton: 'swal-custom-confirm'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('{{ route("role.request") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                title: data.success ? 'Success!' : 'Oops...',
                                text: data.message,
                                icon: data.success ? 'success' : 'error',
                                background: '#0B0E14',
                                color: '#fff',
                                confirmButtonColor: '#00F0FF'
                            });
                        })
                        .catch(err => {
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred. Please try again.',
                                icon: 'error',
                                background: '#0B0E14',
                                color: '#fff',
                                confirmButtonColor: '#00F0FF'
                            });
                        });
                    }
                });
            });
        }

        const btnEditUserid = document.getElementById('btnEditUserid');
        const inputUserid = document.getElementById('userid');
        if(btnEditUserid) {
            btnEditUserid.addEventListener('click', function() {
                inputUserid.removeAttribute('readonly');
                inputUserid.style.cursor = 'text';
                inputUserid.focus();
            });
            inputUserid.addEventListener('input', showSaveButton);
        }

        const btnEditEmail = document.getElementById('btnEditEmail');
        const inputEmail = document.getElementById('email');
        if(btnEditEmail) {
            btnEditEmail.addEventListener('click', function() {
                inputEmail.removeAttribute('readonly');
                inputEmail.style.cursor = 'text';
                inputEmail.focus();
            });
            inputEmail.addEventListener('input', showSaveButton);
        }

        const btnEditInstitusi = document.getElementById('btnEditInstitusi');
        const inputInstitusi = document.getElementById('institusi');
        if(btnEditInstitusi) {
            btnEditInstitusi.addEventListener('click', function() {
                inputInstitusi.removeAttribute('readonly');
                inputInstitusi.style.cursor = 'text';
                inputInstitusi.focus();
            });
            inputInstitusi.addEventListener('input', showSaveButton);
        }

        const btnSaveConfirm = document.getElementById('btnSaveConfirm');
        if(btnSaveConfirm) {
            btnSaveConfirm.addEventListener('click', function() {
                let originalEmail = "{{ $user->email ?? '' }}";
                let currentEmail = inputEmail ? inputEmail.value : originalEmail;
                
                if (currentEmail !== originalEmail) {
                    Swal.fire({
                        title: 'Email Address Changed',
                        text: "Changing your email requires verification. You will be logged out and a verification link will be sent to the new email. Proceed?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#00F0FF',
                        cancelButtonColor: '#ff4444',
                        confirmButtonText: 'Yes, proceed!',
                        background: '#0B0E14',
                        color: '#fff',
                        customClass: { popup: 'swal-custom-popup', confirmButton: 'swal-custom-confirm' }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.querySelector('.profile-form').submit();
                        }
                    });
                } else {
                    document.querySelector('.profile-form').submit();
                }
            });
        }

        // Prevent form submission on Enter key, force button click
        document.querySelector('.profile-form').addEventListener('submit', function(e) {
            e.preventDefault();
            if(btnSaveConfirm) btnSaveConfirm.click();
        });

        const btnDeleteAccount = document.getElementById('btnDeleteAccount');
        if(btnDeleteAccount) {
            btnDeleteAccount.addEventListener('click', function() {
                Swal.fire({
                    title: 'Delete Account?',
                    text: "This action is permanent and cannot be undone. All your data will be erased.",
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#ff4444',
                    cancelButtonColor: 'rgba(255, 255, 255, 0.1)',
                    confirmButtonText: 'Yes, delete my account',
                    background: '#0B0E14',
                    color: '#fff',
                    customClass: { popup: 'swal-custom-popup', confirmButton: 'swal-custom-confirm' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteAccountForm').submit();
                    }
                });
            });
        }
    </script>
    <style>
        .swal-custom-popup {
            border: 1px solid rgba(0, 240, 255, 0.2) !important;
            box-shadow: 0 0 20px rgba(0, 240, 255, 0.1) !important;
        }
        .swal-custom-confirm {
            color: #000 !important;
            font-weight: bold !important;
        }
    </style>
</body>
</html>

