<style>
    body {
        background: linear-gradient(135deg, #141e30 0%, #243b55 100%);
        color: #fff;
        font-family: 'Arial', sans-serif;
        min-height: 100vh;
    }
    .navbar {
        background: linear-gradient(45deg, #1e3c72 0%, #2a5298 100%) !important;
        box-shadow: 0 3px 6px rgba(0,0,0,0.2);
    }
    .card {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
        transition: transform 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .table {
        color: #fff;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        backdrop-filter: blur(10px);
    }
    .table thead th {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.2);
    }
    .table td {
        border-color: rgba(255, 255, 255, 0.1);
    }
    .form-control {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #fff;
    }
    .form-control:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.3);
        color: #fff;
    }
    .form-label {
        color: #fff;
    }
    .btn-primary {
        background: linear-gradient(45deg, #1e3c72 0%, #2a5298 100%);
        border: none;
    }
    .btn-danger {
        background: linear-gradient(45deg, #cb2d3e 0%, #ef473a 100%);
        border: none;
    }
    .btn-warning {
        background: linear-gradient(45deg, #FF8008 0%, #FFC837 100%);
        border: none;
        color: #fff;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="/">
            <i class="bi bi-credit-card-2-front me-2"></i>RFID E-TOL
        </a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="/"><i class="bi bi-house-door"></i> Dashboard</a>
            <a class="nav-link" href="/users"><i class="bi bi-people"></i> Users</a>
        </div>
    </div>
</nav>