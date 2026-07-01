<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Activity Logs | Morpho.ID Admin</title>

    @vite(['resources/css/dashboard.css', 'resources/css/admin_visits.css', 'resources/js/dashboard.js'])
    <style>
        @media print {
            body { background: white !important; color: black !important; padding: 0 !important; margin: 0 !important; }
            #particles-js, .navbar, .top-nav, .back-btn, .print-btn, .pagination-wrapper, .watermark { display: none !important; }
            .dashboard-container { margin: 0 !important; padding: 0 !important; }
            h2, p, th, td, div, span, strong, small { color: black !important; }
            .data-table { border: 1px solid #ccc; width: 100%; border-collapse: collapse; }
            .data-table th, .data-table td { border: 1px solid #ccc !important; padding: 8px !important; }
            .log-container { background: none !important; box-shadow: none !important; border: none !important; padding: 0 !important; }
        }

        /* Data Table Styling */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th {
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color, rgba(0, 240, 255, 0.2));
            color: var(--text-muted, #a1a1aa);
            font-size: 0.85rem;
            text-transform: uppercase;
            text-align: left;
        }
        .data-table td {
            padding: 1.2rem 0;
            border-bottom: 1px solid var(--border-color, rgba(0, 240, 255, 0.2));
            font-size: 0.95rem;
            vertical-align: middle;
        }
        .data-table tr:last-child td {
            border-bottom: none;
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

        <div class="header-section" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem;">
            <div>
                <h2 style="color: var(--text-main); font-size: 1.8rem; font-weight: 800; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 1px;">SYSTEM ACTIVITY LOGS</h2>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 0;">Monitor system changes, creations, and deletions by administrators.</p>
            </div>
            <button onclick="window.print()" class="btn transition hover-shadow print-btn" style="display: flex; align-items: center; gap: 8px; border: 1px solid rgba(0, 240, 255, 0.3); color: var(--primary); background: rgba(0, 240, 255, 0.05); border-radius: 8px; padding: 0.6rem 1.2rem; font-weight: 700; cursor: pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Print Logs
            </button>
        </div>

        <div class="table-container" style="background: var(--card-bg); border-radius: 14px; padding: 25px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0, 240, 255, 0.05); position: relative; z-index: 2; backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); margin-bottom: 2rem;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 20%; text-align: left;">DATE & TIME</th>
                        <th style="width: 20%; text-align: left;">ADMINISTRATOR</th>
                        <th style="width: 15%; text-align: left;">MODULE</th>
                        <th style="width: 15%; text-align: left;">ACTION</th>
                        <th style="width: 30%; text-align: left;">DETAILS</th>
                    </tr>
                </thead>
                <tbody id="logTableBody">
                    @forelse($logs as $log)
                    <tr>
                        <td style="color: var(--text-muted);">
                            <strong>{{ $log->created_at->format('d M Y') }}</strong><br>
                            <small>{{ $log->created_at->format('h:i A') }}</small>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: var(--text-main);">{{ $log->admin_name }}</div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $log->admin_email }}</div>
                        </td>
                        <td style="color: var(--text-main);">{{ $log->module }}</td>
                        <td>
                            @php
                                $color = '#94a3b8'; // gray default
                                if($log->action == 'Created') $color = '#10B981'; // green
                                if($log->action == 'Updated') $color = '#F59E0B'; // yellow
                                if($log->action == 'Deleted') $color = '#EF4444'; // red
                            @endphp
                            <span style="background: {{ $color }}20; color: {{ $color }}; padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; border: 1px solid {{ $color }}40;">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td style="color: var(--text-muted); line-height: 1.4;">{{ $log->details }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                            No activity logs found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination Wrapper -->
            <div class="pagination-wrapper" style="margin-top: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <div class="pagination-info" style="color: var(--text-muted); font-size: 0.9rem;">
                    Showing {{ $logs->count() }} records (Total: {{ $logs->total() }})
                </div>
                <div class="pagination-links" style="display: flex; gap: 5px;">
                    {{ $logs->links('pagination::bootstrap-4') }}
                </div>
            </div>
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
