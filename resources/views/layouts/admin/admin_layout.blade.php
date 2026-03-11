<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    \<!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <title>Document</title>
</head>
<body class="bg-light">
    <div class="d-flex" id="admin-wrapper">
        <!-- ================= SIDEBAR (UX/UI Redesign) ================= -->
        <nav class="sidebar bg-success text-white p-4 shadow-sm"
            style="width:250px; min-height:100vh; background-image:none; transition: all 0.3s ease;">

            <!-- Logo Section -->
            <div class="text-center mb-4">
                <img src="#"
                    alt="Rice Expert Logo"
                    class="img-fluid mb-2"
                    style="max-width:120px; border-radius:100px;">
                <h6 class="fw-bold mb-0">Rice Expert</h6>
                <small class="text-muted">ប្រព័ន្ធវិនិច្ឆ័យជំងឺស្រូវ</small>
            </div>

            <!-- Navigation -->
            <ul class="nav flex-column gap-2">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-2
                        {% if request.endpoint.startswith('admin.dashboard') %}active-link{% endif %}"
                    href="#">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>

                <!-- Management Section -->
                <li class="nav-item mt-3 mb-1 section-title text-uppercase text-muted small px-3">
                    Management
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-2
                        {% if request.blueprint == 'tbl_users' %}active-link{% endif %}"
                    href="#">
                        <i class="bi bi-people me-2"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-2
                        {% if request.blueprint == 'tbl_roles' %}active-link{% endif %}"
                    href="#">
                        <i class="bi bi-shield-lock me-2"></i> Roles
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-2
                        {% if request.blueprint == 'tbl_permissions' %}active-link{% endif %}"
                    href="#">
                        <i class="bi bi-key me-2"></i> Permissions
                    </a>
                </li>

                <!-- Disease Section -->
                <li class="nav-item mt-3 mb-1 section-title text-uppercase text-muted small px-3">
                    Disease Management
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-2
                        {% if request.blueprint == 'tbl_diseases' %}active-link{% endif %}"
                    href="#">
                        <i class="bi bi-bug me-2"></i> Diseases
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-2
                        {% if request.blueprint == 'tbl_symptoms' %}active-link{% endif %}"
                    href="#">
                        <i class="bi bi-activity me-2"></i> Symptoms
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-2
                        {% if request.blueprint == 'rule_bp' %}active-link{% endif %}"
                    href="#">
                        <i class="bi bi-diagram-3 me-2"></i> Rules
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-2
                        {% if request.blueprint == 'rule_condition_bp' %}active-link{% endif %}"
                    href="#">
                        <i class="bi bi-diagram-3 me-2"></i> Rule Conditions
                    </a>
                </li>

                <!-- Prevention & Treatment -->
                <li class="nav-item mt-3 mb-1 section-title text-uppercase text-muted small px-3">
                    Health
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-2
                        {% if request.blueprint == 'prevention_bp' %}active-link{% endif %}"
                    href="#">
                        <i class="bi bi-shield-check me-2"></i> Prevention
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-2
                        {% if request.blueprint == 'treatment_bp' %}active-link{% endif %}"
                    href="#">
                        <i class="bi bi-heart-pulse me-2"></i> Treatment
                    </a>
                </li>

                <!-- Diagnosis -->
                <li class="nav-item mt-3">
                    <a class="nav-link d-flex align-items-center px-3 py-2
                        {% if request.endpoint.startswith('admin.diagnosis_input') %}active-link{% endif %}"
                    href="#">
                        <i class="bi bi-clipboard-data me-2"></i> Diagnosis Test
                    </a>
                </li>

                <!-- Reports -->
                <li class="nav-item mt-3 mb-1 section-title text-uppercase text-muted small px-3">
                    Reports
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-2
                        {% if request.blueprint == 'audit_bp' %}active-link{% endif %}"
                    href="#">
                        <i class="bi bi-journal-text me-2"></i> History System
                    </a>
                </li>

                <!-- System -->
                <li class="nav-item mt-3 mb-1 section-title text-uppercase text-muted small px-3">
                    System
                </li>
                <li class="nav-item">
                    <a href="#"
                    class="btn btn-sm btn-outline-light d-flex align-items-center px-3 py-2 mb-1">
                        <i class="bi bi-gear me-2"></i> Settings
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center px-3 py-2
                        {% if request.endpoint.startswith('admin.about') %}active-link{% endif %}"
                    href="#}">
                        <i class="bi bi-info-circle me-2"></i> About
                    </a>
                </li>

                <li class="nav-item mt-4">
                    <a class="nav-link text-danger d-flex align-items-center px-3 py-2"
                    href="#">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</body>
</html>