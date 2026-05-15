<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jaced Furniture - Order Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --jaced-cream: #EDE8E3;
            --jaced-card: #FAF6F1;
            --jaced-brown-dark: #272E1D;
            --jaced-brown: #5A4D47;
            --jaced-caramel: #C99A6B;
            --jaced-sage: #5F7568;
            --jaced-input: #DDD6CE;
            --jaced-muted: #8A8278;
        }

        body {
            font-family: 'Lexend', sans-serif !important;
            background-color: var(--jaced-cream) !important;
            color: var(--jaced-brown-dark) !important;
        }

        .sidebar { 
            width: 260px; 
            min-height: 100vh; 
            background: white; 
            position: fixed;
            padding: 40px 24px;
        }

        .main-content { 
            margin-left: 260px; 
            padding: 48px 24px !important; 
            max-width: 100% !important; 
        }

        /* Custom Jaced Classes */
        .jaced-card {
            background-color: var(--jaced-card);
            border-radius: 12px;
            border: none;
            padding: 24px;
        }

        .btn-jaced-primary {
            background-color: var(--jaced-brown-dark) !important;
            color: white !important;
            border-radius: 8px;
            padding: 10px 20px;
            border: none;
            font-weight: 500;
        }

        .input-jaced {
            background-color: var(--jaced-input) !important;
            border: none !important;
            border-radius: 8px !important;
            color: var(--jaced-brown-dark) !important;
        }
        
        .text-jaced-muted { color: var(--jaced-muted) !important; }
        .text-jaced-sage { color: var(--jaced-sage) !important; }
    </style>
</head>
<body>
    @include('components.sidebar')
    <div class="main-content">
        @include('components.topbar')
        <main class="mt-4">
            @yield('content')
        </main>
    </div>
</body>
</html>