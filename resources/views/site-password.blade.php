<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Protected Site</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .password-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
        }
        .password-icon {
            width: 80px;
            height: 80px;
            background: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="password-card">
        <div class="password-icon">
            🔒
        </div>
        
        <h2 class="text-center mb-3">Protected Site</h2>
        
        <p class="text-muted text-center mb-4">{{ $message }}</p>
        
        <form method="POST" action="{{ route('site-password.check') }}">
            @csrf
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       required 
                       autofocus>
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary w-100">
                Enter Site
            </button>
        </form>
        
        <div class="text-center mt-3">
            <small class="text-muted">
                <a href="{{ route('login') }}" class="text-decoration-none">Admin Login</a>
            </small>
        </div>
    </div>
</body>
</html>
