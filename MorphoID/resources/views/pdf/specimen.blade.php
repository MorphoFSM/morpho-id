<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $specimen->nama_spesimen }} - Report</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header img {
            max-height: 80px;
            margin-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #050914;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #666;
        }
        .content {
            margin: 0 20px;
        }
        .specimen-image {
            text-align: center;
            margin-bottom: 20px;
        }
        .specimen-image img {
            max-width: 100%;
            max-height: 400px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-label {
            font-weight: bold;
            color: #050914;
            text-transform: uppercase;
            font-size: 12px;
            margin-bottom: 5px;
            border-bottom: 1px solid #eee;
            padding-bottom: 3px;
        }
        .info-value {
            font-size: 14px;
            margin-bottom: 15px;
        }
        .badge {
            display: inline-block;
            background-color: #eee;
            color: #333;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-right: 5px;
            margin-bottom: 5px;
            border: 1px solid #ccc;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">

        <h1>MORPHO.ID SPECIMEN REPORT</h1>
        <p>Generated on {{ date('d F Y, h:i A') }}</p>
    </div>

    <div class="content">
        <div class="specimen-image">
            @if(isset($imageData) && $imageData)
                <img src="{{ $imageData }}" alt="{{ $specimen->nama_spesimen }}">
            @elseif($specimen->gambar && $specimen->gambar !== '-')
                <img src="{{ $specimen->gambar }}" alt="{{ $specimen->nama_spesimen }}">
            @else
                <div style="padding: 50px; background: #f9f9f9; border: 1px solid #ddd;">No Image Available</div>
            @endif
        </div>

        <div class="info-section">
            <div class="info-label">Specimen Name</div>
            <div class="info-value" style="font-size: 18px; font-weight: bold;">
                {{ $specimen->nama_spesimen }}
            </div>

            <div class="info-label">Category / Taxonomy</div>
            <div class="info-value">
                @if($specimen->category)
                    @if($specimen->category->parent)
                        {{ $specimen->category->parent->nama_kategori }} &gt; 
                    @endif
                    {{ $specimen->category->nama_kategori }}
                @else
                    None
                @endif
            </div>

            <div class="info-label">Tags / Characteristics</div>
            <div class="info-value">
                @php
                    $tags = is_array($specimen->ciri_ciri) ? $specimen->ciri_ciri : explode(',', $specimen->ciri_ciri ?? '');
                @endphp
                @forelse($tags as $tag)
                    @if(!empty(trim($tag)))
                        <span class="badge">{{ trim($tag) }}</span>
                    @endif
                @empty
                    No tags recorded.
                @endforelse
            </div>

            <div class="info-label">Description</div>
            <div class="info-value" style="white-space: pre-wrap;">
                {{ $specimen->penerangan ?: 'No description provided.' }}
            </div>
            
            <div class="info-label">Record Information</div>
            <div class="info-value" style="font-size: 12px; color: #555;">
                Added on: {{ $specimen->created_at ? $specimen->created_at->format('d M Y') : 'Unknown' }}<br>
                Last updated: {{ $specimen->updated_at ? $specimen->updated_at->format('d M Y') : 'Unknown' }}
            </div>
        </div>
    </div>

    <div class="footer">
        Morpho.ID System - Advanced Specimen Management System (UPSI)
    </div>


    @if(!empty($galleryData))
        <div style="page-break-before: always;"></div>
        <div class="header">
            <h1>{{ $specimen->nama_spesimen }} - Gallery Images</h1>
        </div>
        <div class="content">
            @foreach($galleryData as $gal)
                <div class="specimen-image">
                    <img src="{{ $gal }}" alt="Gallery Image">
                </div>
            @endforeach
        </div>
    @endif
</body>
</html>
