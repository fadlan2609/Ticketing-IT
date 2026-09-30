<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #F7F7F8; 
            padding: 20px; 
            margin: 0;
        }
        .container { 
            max-width: 600px; 
            margin: 0 auto; 
            background: white; 
            border-radius: 12px; 
            overflow: hidden; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.05); 
        }
        .header { 
            background: #C8102E; 
            padding: 24px 20px; 
            text-align: center; 
            color: white;
        }
        .header img {
            height: 44px;
            margin-bottom: 10px;
        }
        .header h1 { 
            margin: 0; 
            color: white; 
            font-size: 18px; 
            font-weight: bold;
        }
        .header p { 
            margin: 6px 0 0 0; 
            font-size: 13px; 
            color: rgba(255,255,255,0.85); 
        }
        .content { 
            padding: 30px; 
        }
        .content h2 { 
            color: #18181B; 
            font-size: 17px; 
            margin-top: 0; 
            margin-bottom: 16px;
        }
        .content p { 
            color: #52525B; 
            line-height: 1.6; 
            font-size: 14px;
        }
        .btn { 
            display: inline-block; 
            background: #C8102E; 
            color: white !important; 
            padding: 12px 24px; 
            text-decoration: none; 
            border-radius: 8px; 
            font-weight: 600; 
            margin-top: 16px; 
            font-size: 14px;
        }
        .footer { 
            background: #FAFAFA; 
            padding: 20px; 
            text-align: center; 
            color: #71717A; 
            font-size: 11px; 
            border-top: 3px solid #C8102E;
            line-height: 1.6;
        }
        .footer a {
            color: #C8102E;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- HEADER (otomatis dari pengaturan) --}}
        <div class="header">
            @if($company->logo_url)
                <img src="{{ $company->logo_url }}" 
                     alt="{{ $company->company_name }}">
            @endif
            <h1>🔔 {{ $company->company_name }}</h1>
            <p>{{ $company->app_name }}
                @if($company->tagline)
                    — {{ $company->tagline }}
                @endif
            </p>
        </div>

        {{-- CONTENT --}}
        <div class="content">
            <h2>{{ $data['title'] ?? 'Notifikasi' }}</h2>
            <p>Halo <strong>{{ $user->name }}</strong>,</p>
            <p>{{ $data['message'] ?? '' }}</p>
            
            @if(!empty($data['url']))
                <a href="{{ $data['url'] }}" class="btn">Lihat Detail →</a>
            @endif
        </div>

        {{-- FOOTER (otomatis dari pengaturan) --}}
        <div class="footer">
            Email otomatis dari <strong>{{ $company->app_name }}</strong><br>
            {{ $company->company_name }}
            @if($company->full_address)
                <br>{{ $company->full_address }}
            @endif
            @if($company->phone || $company->email)
                <br>
                @if($company->phone) Telp: {{ $company->phone }} @endif
                @if($company->phone && $company->email) · @endif
                @if($company->email)
                    <a href="mailto:{{ $company->email }}">{{ $company->email }}</a>
                @endif
            @endif
            @if($company->website)
                <br><a href="{{ $company->website }}">{{ $company->website }}</a>
            @endif
            <br><br>
            <span style="color: #A1A1AA;">
                Mohon tidak membalas email ini. © {{ date('Y') }}
            </span>
        </div>
    </div>
</body>
</html>