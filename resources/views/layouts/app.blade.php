<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cooperative Bank ERP')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
    <style>
        /* High-contrast badge overrides for accessibility (WCAG AA) */
        .badge-warning { background-color: #d39e00 !important; color: #000 !important; }
        .badge-info    { background-color: #117a8b !important; color: #fff !important; }
        .badge-success { background-color: #1e7e34 !important; color: #fff !important; }
        .badge-danger  { background-color: #bd2130 !important; color: #fff !important; }
        .badge-secondary { background-color: #545b62 !important; color: #fff !important; }
        /* Print styles */
        @media print {
            .main-sidebar, .main-header, .main-footer, .card-tools, .btn, .no-print { display: none !important; }
            .content-wrapper { margin-left: 0 !important; padding: 0 !important; }
            .card { border: none !important; box-shadow: none !important; }
            body { font-size: 12pt; }
            table { font-size: 10pt; }
            .badge { border: 1px solid #000 !important; }
        }
        /* Responsive: ensure tables scroll on small screens */
        @media (max-width: 768px) {
            .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .card-tools .btn { margin-bottom: 4px; }
            .form-inline .form-control { width: 100% !important; margin-bottom: 4px; }
        }
        /* Required field marker */
        .text-danger { color: #dc3545 !important; }
        /* Form help text */
        .form-text { font-size: 0.8rem; color: #6c757d; }
    </style>
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a></li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" data-toggle="dropdown">
                    <i class="fas fa-user"></i> {{ auth()->user()->name }}
                    <span class="badge badge-info">{{ auth()->user()->role?->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-id-card"></i> My Profile</a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Logout</button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>
    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="/" class="brand-link">
            <span class="brand-text font-weight-light"><i class="fas fa-university"></i> CoopBank</span>
        </a>
        <div class="sidebar">
            @include('layouts.sidebar')
        </div>
    </aside>
    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6"><h1 class="m-0">@yield('page-title')</h1></div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>{{ session('error') }}</div>
                @endif
                @yield('content')
            </div>
        </section>
    </div>
    <footer class="main-footer"><strong>Cooperative Bank ERP</strong> &copy; {{ date('Y') }}</footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function() {
    // ── Searchable Dropdowns ─────────────────────────────────
    $('select.select2').select2({ theme: 'bootstrap4', allowClear: true, placeholder: $(this).data('placeholder') || '-- Select --' });

    // ── Confirmation Modals ──────────────────────────────────
    $(document).on('submit', 'form[data-confirm]', function(e) {
        e.preventDefault();
        var form = this;
        Swal.fire({
            title: $(form).data('confirm') || 'Are you sure?',
            text: $(form).data('confirm-text') || '',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: $(form).data('confirm-yes') || 'Yes, proceed'
        }).then(function(result) { if (result.isConfirmed) form.submit(); });
    });

    // ── Client-side Validation ───────────────────────────────
    $('form[data-validate]').each(function() {
        $(this).find('input[required], select[required], textarea[required]').each(function() {
            if (!$(this).attr('aria-required')) $(this).attr('aria-required', 'true');
        });
        $(this).on('submit', function(e) {
            var valid = true;
            $(this).find('.is-invalid-client').removeClass('is-invalid-client');
            $(this).find('.client-error').remove();
            $(this).find('input[required], select[required], textarea[required]').each(function() {
                if (!$(this).val() || $(this).val() === '') {
                    valid = false;
                    $(this).addClass('is-invalid-client').css('border-color', '#dc3545');
                    var label = $(this).closest('.form-group').find('label').first().text().replace('*','').trim();
                    $(this).after('<small class="client-error text-danger">' + label + ' is required</small>');
                }
            });
            if (!valid) { e.preventDefault(); $('html,body').animate({scrollTop: $('.is-invalid-client').first().offset().top - 100}, 300); }
        });
        // Clear error on input
        $(this).on('input change', 'input, select, textarea', function() {
            $(this).removeClass('is-invalid-client').css('border-color', '');
            $(this).siblings('.client-error').remove();
        });
    });

    // ── Custom file input label ──────────────────────────────
    $('.custom-file-input').on('change', function() {
        $(this).next('.custom-file-label').html($(this).val().split('\\').pop());
    });
});
</script>
@stack('scripts')
</body>
</html>
