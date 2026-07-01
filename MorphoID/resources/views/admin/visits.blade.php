<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Logs | Morpho.ID Admin</title>

    @vite(['resources/css/specimenmanage.css'])
    <style>
        /* 1. BAHAGIAN FILTER & HEADER */
        .log-header-container { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color); flex-wrap: wrap; gap: 1rem; }
        .filter-form { display: flex; gap: 0.8rem; align-items: center; }
        .custom-filter { padding: 0.5rem 1rem; border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; font-size: 0.85rem; font-weight: 700; color: var(--text-main); background-color: var(--card-bg); outline: none; cursor: pointer; transition: all 0.3s ease; }
        .custom-filter:focus, .custom-filter:hover { border-color: var(--primary); box-shadow: 0 0 15px rgba(0, 240, 255, 0.2); }
        .btn-reset-filter { display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 8px; background-color: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); text-decoration: none; font-weight: bold; transition: 0.2s; }
        .btn-reset-filter:hover { background-color: #ef4444; color: #ffffff; box-shadow: 0 0 10px rgba(239, 68, 68, 0.5); }

        /* 2. GAYes TEXT & ALIGNMENT JADUAL */
        .table-header { display: flex; justify-content: space-between; align-items: center; }
        .text-right { text-align: right; }
        .date-text { font-weight: 800; color: var(--text-main); display: block; }
        .time-text { font-size: 0.75rem; font-weight: 700; color: var(--text-muted); margin-top: 0.3rem; display: inline-block; background: var(--bg-main); padding: 0.1rem 0.5rem; border-radius: 4px; letter-spacing: 0.5px; }
        .col-userid {
            font-size: 0.9rem;
            color: var(--primary);
            font-weight: 800;
            font-family: 'Plus Jakarta Sans', monospace;
            letter-spacing: 0.5px;
        }
        .col-email { font-weight: 600; color: var(--text-muted); }

        /* ==========================================
           FIX ALIGNMENT 3 EKOR (TARIKH, ID, E-MEL)
           ========================================== */
        /* 1. Paksa All tajuk dan isi jadual rata kiri secara default */
        .data-table th, .data-table td {
            text-align: left;
            vertical-align: middle;
            padding-left: 0.5rem; /* Jarak sikit dari tepi supaYes tak rapat sangat */
        }

        /* 2. KECUALI Account Type (Kekal Tengah) */
        .data-table th.col-jenis-akaun,
        .data-table td.col-jenis-akaun {
            text-align: center !important;
        }

        /* 3. KECUALI Status (Kekal Kanan) */
        .data-table th.text-right,
        .data-table td.text-right {
            text-align: right !important;
        }
        th.col-jenis-akaun {
            text-align: center !important;
        }
        td.col-jenis-akaun {
            text-align: center !important;
            vertical-align: middle !important;
        }
        .badge-role {
            display: inline-block;
            margin: 0 auto;
        }
        th.col-tarikh, td.col-tarikh,
        th.col-userid, td.col-userid,
        th.col-email, td.col-email {
            text-align: left !important;
            padding-left: 1rem !important; /* Paksa mula dari titik Yesng sama */
        }

        /* Tarikh & Masa je kena block supaYes dia duduk atas bawah */
        .date-text, .time-text {
            display: block;
            margin-left: 0 !important;
        }

        /* ID dengan Email jangan block! Biar dia normal table-cell */
        .col-userid, .col-email {
            margin-left: 0 !important;
        }
        .admin-container {
            width: 95% !important;
            max-width: 1400px !important;
            margin: 0 auto !important;
            padding: 2rem !important;
        }

        /* 2. PAKSA TABLE KEMBANG 100% */
        .data-table {
            width: 100% !important;
            border-collapse: collapse;
            table-layout: fixed; /* Ini kunci supaYes dia tak lari */
        }

        /* 3. SET LEBAR COLUMN SUPAYes TEGAK */
        .col-tarikh { width: 15%; }
        .col-userid { width: 20%; }
        .col-email { width: 30%; }
        .col-jenis-akaun { width: 20%; text-align: center !important; }
        .text-right { width: 15%; text-align: right !important; }

        /* 4. PADDING SAMA RATA */
        .data-table th, .data-table td {
            padding: 1.2rem 1rem !important;
            vertical-align: middle;
        }
/* 1. MENGEMASKAN FILTER DENGAN RUPA PREMIUM */
.custom-filter {
    padding: 0.6rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: 10px; /* Buat dia membulat sikit */
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-main);
    background-color: var(--card-bg);
    outline: none;
    cursor: pointer;
    transition: all 0.3s ease;
    /* Hilangkan rupa 'default' browser */
    appearance: none;
    -webkit-appearance: none;
    /* Add arrow icon untuk select */
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2300F0FF' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 16px;
    padding-right: 35px;
}

/* 2. EFEK HOVER & FOCUS */
.custom-filter:hover {
    border-color: var(--primary);
    background-color: var(--card-bg);
}

.custom-filter:focus {
    border-color: var(--primary);
    box-shadow: 0 0 15px rgba(0, 240, 255, 0.2);
}

/* 3. SETTING KHAS UNTUK DATE INPUT */
input[type="date"].custom-filter {
    padding-right: 15px; /* Date tak perlukan arrow tambahan */
}
/* --- GAYes FILTER MODERN Morpho.ID --- */
.filter-container {
    display: flex;
    gap: 12px;
    align-items: center;
    background: var(--card-bg);
    backdrop-filter: blur(8px);
    padding: 8px;
    border-radius: 14px;
    box-shadow: 0 4px 15px rgba(0, 240, 255, 0.1); 
    border: 1px solid var(--border-color);
}

/* 1. RESET HABIS-HABISAN */
select.custom-filter {
    /* Buang terus All style asal browser */
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;

    /* Bagi ruang & cantikkan */
    padding: 8px 35px 8px 12px;
    border: 1px solid var(--border-color);
    border-radius: 10px;
    background-color: var(--card-bg);
    color: var(--text-main);
    cursor: pointer;
    font-family: inherit;
    font-weight: 600;

    /* Custom Arrow (Ikon Child Panah) */
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2300F0FF' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
}

/* 2. STYLE UNTUK INPUT DATE (Sebab dia degil sikit) */
input[type="date"].custom-filter {
    -webkit-appearance: none;
    padding: 8px 12px;
    border: 1px solid var(--border-color);
    border-radius: 10px;
}

/* 3. EFEK CANTIK MASA KLIK */
.custom-filter:focus {
    border-color: var(--primary) !important;
    box-shadow: 0 0 0 4px rgba(0, 240, 255, 0.1) !important;
}

        /* 4. STATUS LED BERKELIP */
        .status-success { color: #9D4EDD; font-weight: 800; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.4rem; }
        .status-success::before { content: ''; display: block; width: 6px; height: 6px; border-radius: 50%; background-color: #10b981; box-shadow: 0 0 5px rgba(16, 185, 129, 0.5); }
        .status-failed { color: #DC2626; font-weight: 800; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.4rem; }
        .status-failed::before { content: ''; display: block; width: 6px; height: 6px; border-radius: 50%; background-color: #EF4444; box-shadow: 0 0 5px rgba(239, 68, 68, 0.5); }

        .status-loggedin { color: #3b82f6; font-weight: 800; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.4rem; }
        .status-loggedin::before { content: ''; display: block; width: 6px; height: 6px; border-radius: 50%; background-color: #60a5fa; box-shadow: 0 0 5px rgba(96, 165, 250, 0.5); }
        
        .status-registered { color: #9D4EDD; font-weight: 800; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.4rem; }
        .status-registered::before { content: ''; display: block; width: 6px; height: 6px; border-radius: 50%; background-color: #c084fc; box-shadow: 0 0 5px rgba(192, 132, 252, 0.5); }

        .status-newadmin { color: #00F0FF; font-weight: 800; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.4rem; text-shadow: 0 0 5px rgba(0,240,255,0.3); }
        .status-newadmin::before { content: ''; display: block; width: 6px; height: 6px; border-radius: 50%; background-color: #00F0FF; box-shadow: 0 0 8px rgba(0, 240, 255, 0.8); }
        
        .status-approved { color: #10b981; font-weight: 800; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.4rem; }
        .status-approved::before { content: ''; display: block; width: 6px; height: 6px; border-radius: 50%; background-color: #34d399; box-shadow: 0 0 5px rgba(52, 211, 153, 0.5); }
        
        .status-rejected { color: #f59e0b; font-weight: 800; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.4rem; }
        .status-rejected::before { content: ''; display: block; width: 6px; height: 6px; border-radius: 50%; background-color: #fbbf24; box-shadow: 0 0 5px rgba(251, 191, 36, 0.5); }

        .status-updated { color: #ec4899; font-weight: 800; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.4rem; }
        .status-updated::before { content: ''; display: block; width: 6px; height: 6px; border-radius: 50%; background-color: #f472b6; box-shadow: 0 0 5px rgba(244, 114, 182, 0.5); }

        .status-deleted { color: #6b7280; font-weight: 800; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.4rem; }
        .status-deleted::before { content: ''; display: block; width: 6px; height: 6px; border-radius: 50%; background-color: #9ca3af; box-shadow: 0 0 5px rgba(156, 163, 175, 0.5); }
        .pagination-wrapper { margin-top: 2rem; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-color); padding-top: 1.5rem; }
        .pagination-text { font-size: 0.85rem; font-weight: 600; color: var(--text-muted); }
        .pagination-actions { display: flex; gap: 0.4rem; }
        
        /* Laravel Default Pagination Overrides */
        .pagination { display: flex; list-style: none; padding: 0; margin: 0; gap: 0.4rem; flex-wrap: wrap; }
        .page-item .page-link { 
            display: inline-flex; 
            align-items: center; 
            justify-content: center;
            padding: 0.4rem 0.8rem; 
            border: 1px solid var(--border-color); 
            background: var(--card-bg); 
            border-radius: 8px; 
            cursor: pointer; 
            font-weight: 700; 
            font-size: 0.85rem; 
            color: var(--text-main); 
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        }
        .page-item:not(.active):not(.disabled) .page-link:hover { 
            border-color: var(--primary); 
            color: var(--primary); 
            transform: translateY(-2px); 
            box-shadow: 0 4px 10px rgba(0, 240, 255, 0.2); 
        }
        .page-item.active .page-link { 
            background: var(--primary); 
            color: #050914; 
            border-color: var(--primary); 
            box-shadow: 0 4px 10px rgba(0, 240, 255, 0.4); 
            pointer-events: none;
        }
        .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .global-bg-watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400%;
            max-width: 100%;
            height: auto;
            object-fit: contain;
            opacity: 0.05;
            z-index: 0;
            pointer-events: none;
        }

        /* 6. CHART FILTERS */
        .btn-chart-filter { padding: 0.4rem 1rem; border: 1px solid var(--border-color); background: transparent; border-radius: 8px; cursor: pointer; font-size: 0.85rem; font-weight: 700; color: var(--text-muted); transition: 0.3s; font-family: inherit; }
        .btn-chart-filter:hover { border-color: var(--primary); color: var(--text-main); }
        .btn-chart-filter.active { background: var(--primary); color: #050914; border-color: var(--primary); box-shadow: 0 0 10px rgba(0, 240, 255, 0.3); }

        /* 7. CUSTOM CHECKBOX */
        input[type="checkbox"].log-checkbox, input[type="checkbox"]#selectAll {
            appearance: none;
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid var(--border-color);
            border-radius: 4px;
            background-color: var(--bg-main);
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
            display: inline-block;
            vertical-align: middle;
        }

        input[type="checkbox"].log-checkbox:checked, input[type="checkbox"]#selectAll:checked {
            background-color: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 0 10px rgba(0, 240, 255, 0.4);
        }

        input[type="checkbox"].log-checkbox:checked::after, input[type="checkbox"]#selectAll:checked::after {
            content: '';
            position: absolute;
            left: 5px;
            top: 1px;
            width: 4px;
            height: 9px;
            border: solid #000;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        
        input[type="checkbox"].log-checkbox:hover, input[type="checkbox"]#selectAll:hover {
            border-color: var(--primary);
            box-shadow: 0 0 8px rgba(0, 240, 255, 0.2);
        }

        /* Custom Pagination Styling */
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 8px;
            align-items: center;
        }
        .pagination .page-item .page-link {
            background: rgba(11, 19, 43, 0.6);
            border: 1px solid rgba(0, 240, 255, 0.2);
            color: #fff;
            padding: 6px 12px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 0.9rem;
        }
        .pagination .page-item:not(.disabled) .page-link:hover {
            background: rgba(0, 240, 255, 0.2);
            border-color: #00F0FF;
            color: #00F0FF;
        }
        .pagination .page-item.active .page-link {
            background: #00F0FF;
            color: #000;
            border-color: #00F0FF;
            font-weight: bold;
        }
        .pagination .page-item.disabled .page-link {
            color: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.1);
            cursor: not-allowed;
            background: rgba(11, 19, 43, 0.3);
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div id="particles-js"></div>
    @include('components.watermark')
    @include('components.navbar')

    <main class="admin-container">
        <div class="page-header">
            <div>
                <h1>VISITOR RECORD ACCESS</h1>
                <p>Monitor login history of visitors and registered users in the system.</p>
                <p>Monitor User and Admin login History</p>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="chart-container" style="background: var(--card-bg); border-radius: 14px; padding: 20px; border: 1px solid var(--border-color); margin-bottom: 2rem; box-shadow: 0 4px 15px rgba(0, 240, 255, 0.05); position: relative; z-index: 2;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
                <h3 style="color: var(--text-main); font-size: 1.2rem; font-weight: 800; margin: 0; font-family: 'Plus Jakarta Sans', sans-serif;">Visitor Login Trends</h3>
                <div class="chart-filters" style="display: flex; gap: 8px;">
                    <button class="btn-chart-filter active" data-filter="day">Day</button>
                    <button class="btn-chart-filter" data-filter="week">Week</button>
                    <button class="btn-chart-filter" data-filter="year">Year</button>
                    <button class="btn-chart-filter" data-filter="all">All Time</button>
                </div>
            </div>
            <div style="position: relative; height: 320px; width: 100%;">
                <canvas id="visitorChart"></canvas>
            </div>
        </div>

        <div class="table-container" style="background: var(--card-bg); border-radius: 14px; padding: 25px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0, 240, 255, 0.05); position: relative; z-index: 2; backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); margin-bottom: 2rem;">
        <div class="log-header-container">
            <h2 class="fw-bold mb-0" style="color: var(--text-main);">List Of Visitor</h2>

            <form action="{{ url()->current() }}" method="GET" class="filter-container">
                <input type="text" name="search" class="custom-filter" placeholder="Search ID, Name, Email..." value="{{ request('search') }}" style="min-width: 200px;">
                <input type="date" name="date" class="custom-filter" value="{{ request('date') }}" onchange="this.form.submit()">

                <select name="role" class="custom-filter" onchange="this.form.submit()">
                    <option value="All">ALL</option>
                    <option value="Administrator" {{ request('role') == 'Administrator' ? 'selected' : '' }}>Administrator</option>
                    <option value="User" {{ request('role') == 'User' ? 'selected' : '' }}>User</option>
                </select>

                <select name="per_page" class="custom-filter" onchange="this.form.submit()">
                    <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>Show 10</option>
                    <option value="20" {{ request('per_page') == '20' ? 'selected' : '' }}>Show 20</option>
                    <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>Show 100</option>
                    <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Show All</option>
                </select>

                @if(request('role') || request('date') || request('search') || request('per_page'))
                    <a href="{{ url()->current() }}" class="btn-reset-filter" title="Reset" style="color: var(--text-main); text-decoration: none; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 10px; margin-left: 5px;">âœ•</a>
                @endif
                <button type="submit" style="display:none;"></button>
            </form>
        </div>

        <form action="{{ route('admin.visits.bulkDelete') }}" method="POST" id="bulkDeleteForm">
            @csrf
            <div style="margin-bottom: 1rem; display: flex; justify-content: flex-end; gap: 12px; align-items: center;">
                <a href="{{ route('admin.visits.export') }}" class="btn-manage" style="background: #10B981; color: white; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 0.9rem; font-family: 'Plus Jakarta Sans', sans-serif; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    Export Excel
                </a>
                <button type="submit" class="btn-manage" style="background: #DC2626; color: white; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 0.9rem;" onclick="event.preventDefault(); Swal.fire({title: 'Confirmation', text: 'Are you sure you want to delete the selected logs?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Yes'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">
                    Delete Selected
                </button>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%; text-align: center;"><input type="checkbox" id="selectAll" onclick="document.querySelectorAll('.log-checkbox').forEach(cb => cb.checked = this.checked)"></th>
                        <th class="col-tarikh" style="width: 15%;">DATE & TIME</th>
                        <th class="col-userid" style="width: 15%;">USER ID</th>
                        <th style="width: 20%;">NAME</th>
                        <th class="col-email" style="width: 20%;">E-MAIL ADDRESS</th>
                        <th class="col-jenis-akaun" style="width: 15%;">ACCOUNT ROLE</th>
                        <th class="text-right" style="width: 10%;">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td style="text-align: center;"><input type="checkbox" name="ids[]" value="{{ $log->id }}" class="log-checkbox"></td>
                        <td class="col-tarikh">
                            <div class="date-text">{{ $log->created_at->format('d F Y') }}</div>
                            <div class="time-text">{{ $log->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="col-userid">{{ $log->userid }}</td>
                        <td>{{ $log->name ?? '-' }}</td>
                        <td class="col-email">{{ $log->email }}</td>

                        <td class="col-jenis-akaun">
                            <span class="tag badge-role {{ $log->role == 'Administrator' ? 'tag-Parent' : 'tag-sub' }}">
                                {{ $log->role == 'Administrator' ? 'System Admin' : 'Regular User' }}
                            </span>
                        </td>

                        <td class="text-right">
                              @php
                                  $statusLower = strtolower($log->status);
                                  $isSuccess = ($statusLower == 'success' || $statusLower == 'berjaya');
                                  $displayStatus = $log->note ? $log->note : ($isSuccess ? 'Success' : 'Failed');
                                  
                                  $statusClass = 'status-failed';
                                  if ($isSuccess) {
                                      if (str_contains($displayStatus, 'Logged In')) {
                                          $statusClass = 'status-loggedin';
                                      } elseif (str_contains($displayStatus, 'Account Registered')) {
                                          $statusClass = 'status-registered';
                                      } elseif (str_contains($displayStatus, 'Role Upgraded to Admin')) {
                                          $statusClass = 'status-newadmin';
                                      } elseif (str_contains($displayStatus, 'Rejected as Admin')) {
                                          $statusClass = 'status-rejected';
                                      } elseif (str_contains($displayStatus, 'Account Updated')) {
                                          $statusClass = 'status-updated';
                                      } elseif (str_contains($displayStatus, 'Account Deleted')) {
                                          $statusClass = 'status-deleted';
                                      } else {
                                          $statusClass = 'status-success';
                                      }
                                  }
                              @endphp
                              <span class="{{ $statusClass }}">
                                  {{ $displayStatus }}
                              </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </form>

        <div class="pagination-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem;">
            <span class="pagination-text">Showing {{ request('per_page') == 'all' ? $logs->count() : $logs->count() }} records (Total: {{ $visitor_count }})</span>
            @if(request('per_page') != 'all')
                <div class="pagination-links" style="display: flex; gap: 5px;">
                    {{ $logs->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
    </main>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('visitorChart').getContext('2d');
            let visitorChart;

            const fetchChartData = async (filter) => {
                try {
                    const response = await fetch(`{{ route('admin.visits.chart') }}?filter=${filter}`);
                    const data = await response.json();
                    renderChart(data.labels, data.data);
                } catch (error) {
                    console.error('Error fetching chart data:', error);
                }
            };

            const renderChart = (labels, data) => {
                if (visitorChart) {
                    visitorChart.destroy();
                }

                const gradient = ctx.createLinearGradient(0, 0, 0, 320);
                gradient.addColorStop(0, 'rgba(0, 240, 255, 0.6)');
                gradient.addColorStop(1, 'rgba(0, 240, 255, 0.0)');

                visitorChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Logins',
                            data: data,
                            borderColor: '#00F0FF',
                            backgroundColor: gradient,
                            borderWidth: 2,
                            pointBackgroundColor: '#9D4EDD',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 1.5,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(5, 9, 20, 0.95)',
                                titleColor: '#00F0FF',
                                titleFont: { size: 13, family: 'Plus Jakarta Sans' },
                                bodyColor: '#ffffff',
                                bodyFont: { size: 14, weight: 'bold', family: 'Plus Jakarta Sans' },
                                borderColor: 'rgba(0, 240, 255, 0.3)',
                                borderWidth: 1,
                                padding: 12,
                                displayColors: false
                            }
                        },
                        scales: {
                            x: {
                                grid: { color: 'rgba(255, 255, 255, 0.05)', drawBorder: false },
                                ticks: { color: '#64748b', font: { size: 11, family: 'Plus Jakarta Sans' } }
                            },
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(255, 255, 255, 0.05)', drawBorder: false },
                                ticks: { color: '#64748b', font: { size: 11, family: 'Plus Jakarta Sans' }, stepSize: 1 }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index',
                        },
                    }
                });
            };

            // Initial load
            fetchChartData('day');

            // Button click handlers
            document.querySelectorAll('.btn-chart-filter').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.btn-chart-filter').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    fetchChartData(this.dataset.filter);
                });
            });
        });
    </script>
</body>
</html>






