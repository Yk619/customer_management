<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Customer Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            color: white;
            padding: 5rem 0;
            margin-bottom: 3rem;
        }
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #6e8efb;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">CustomerAPI</a>
            <div class="d-flex">
                <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold">Customer Management System</h1>
            <p class="lead">Secure REST API with full authentication</p>
        </div>
    </div>

    <div class="container my-5">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <i class="bi bi-shield-lock feature-icon"></i>
                <h3>Secure Auth</h3>
                <p>OAuth2 with MFA protection</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="bi bi-database feature-icon"></i>
                <h3>CRUD Operations</h3>
                <p>Full customer management</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="bi bi-lightning-charge feature-icon"></i>
                <h3>Fast API</h3>
                <p>Laravel-powered performance</p>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>© {{ date('Y') }} Customer Management System</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>