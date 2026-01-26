<div class="main-header">
    <div class="main-header-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="success">
            <a href="index.html" class="logo">
                <img src="{{ asset('assets/img/kaiadmin/logo_light.svg') }}" alt="navbar brand" class="navbar-brand"
                    height="20" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    
    <!-- Navbar Header -->
    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
        <div class="container-fluid">
            <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                {{-- <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="submit" class="btn btn-search pe-1">
                            <i class="fa fa-search search-icon"></i>
                        </button>
                    </div>
                    <input type="text" placeholder="Search ..." class="form-control" />
                </div> --}}
            </nav>

            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none" style="display: none !important;">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                        aria-expanded="false" aria-haspopup="true">
                        <i class="fa fa-search"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-search animated fadeIn">
                        <form class="navbar-left navbar-form nav-search">
                            <div class="input-group">
                                <input type="text" placeholder="Search ..." class="form-control" />
                            </div>
                        </form>
                    </ul>
                </li>

                {{-- Notification Icon untuk Admin --}}
                @if (auth()->check() && auth()->user()->role === 'admin')
                <li class="nav-item topbar-icon dropdown hidden-caret me-3">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                        aria-expanded="false" aria-haspopup="true">
                        <i class="fa fa-bell"></i>
                        <span class="notification-badge" id="notification-count" style="display: none;">0</span>
                    </a>
                    <ul class="dropdown-menu dropdown-notification animated fadeIn" id="notificationDropdown">
                        <li>
                            <div class="notification-scroll-area">
                                <div id="notification-list">
                                    <div class="text-center py-3">
                                        <span class="text-muted">loading...</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                            <div style="display: flex; gap: 8px; padding: 8px 15px;">
                                <a class="btn btn-sm btn-light" style="flex: 1; text-align: center;" href="javascript:void(0);" onclick="markAllNotificationsAsRead()">Tandai terbaca</a>
                                <a class="btn btn-sm btn-danger" style="flex: 1; text-align: center;" href="javascript:void(0);" onclick="deleteAllNotifications()">Hapus Semua</a>
                            </div>
                        </li>
                    </ul>
                </li>

                <style>
                    #notificationDropdown {
                        width: 450px;
                        max-height: 550px;
                        right: 0;
                        left: auto;
                    }

                    .notification-scroll-area {
                        max-height: 450px;
                        overflow-y: auto;
                    }

                    .notification-badge {
                        position: absolute;
                        top: -5px;
                        right: -8px;
                        background: #e3422d;
                        color: white;
                        border-radius: 50%;
                        width: 20px;
                        height: 20px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 0.75rem;
                        font-weight: bold;
                    }

                    .notification-item {
                        padding: 12px 15px;
                        border-bottom: 1px solid #e7e7e7;
                        cursor: pointer;
                        transition: background-color 0.2s;
                    }

                    .notification-item:hover {
                        background-color: #f5f5f5;
                    }

                    .notification-item.unread {
                        background-color: #f0f8ff;
                    }

                    .notification-item-title {
                        font-weight: 600;
                        color: #1a1a1a;
                        margin-bottom: 4px;
                    }

                    .notification-item-message {
                        font-size: 0.85rem;
                        color: #666;
                        margin-bottom: 6px;
                    }

                    .notification-item-time {
                        font-size: 0.75rem;
                        color: #999;
                    }

                    .notification-item-badge {
                        display: inline-block;
                        padding: 2px 8px;
                        border-radius: 12px;
                        font-size: 0.7rem;
                        margin-right: 5px;
                    }

                    .notification-item-badge.update {
                        background-color: #e3f2fd;
                        color: #1976d2;
                    }

                    .notification-item-badge.create {
                        background-color: #f0f4c3;
                        color: #f57f17;
                    }

                    .notification-item-badge.delete {
                        background-color: #ffebee;
                        color: #c62828;
                    }

                    .dropdown-notification {
                        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
                        border: 1px solid #e7e7e7;
                    }

                    .see-all {
                        display: block;
                        padding: 12px 15px;
                        text-align: center;
                        color: #2196f3;
                        text-decoration: none;
                        font-weight: 500;
                        border-top: 1px solid #e7e7e7;
                        transition: background-color 0.2s;
                    }

                    .see-all:hover {
                        background-color: #f5f5f5;
                    }
                </style>
                @endif

                @php
                    $user = Auth::user();
                    $name = $user?->name ?? 'User';
                    //  untuk role user, tampilkan plat nomor sebagai nama
                    $displayName =
                        $user && ($user->role ?? null) === 'user' && !empty($user->plat_nomor)
                            ? $user->plat_nomor
                            : $name;
                    $initials = collect(explode(' ', $displayName))
                        ->map(function ($p) {
                            return strtoupper(substr($p, 0, 1));
                        })
                        ->filter()
                        ->take(2)
                        ->implode('');
                    $colors = ['#31ce36', '#2d9cdb', '#f6a01a', '#e83e8c', '#6f42c1', '#20c997', '#fd7e14'];
                    $color = $colors[crc32($displayName) % count($colors)];
                @endphp

                <li class="nav-item topbar-user dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic d-flex align-items-center" data-bs-toggle="dropdown"
                        href="#" aria-expanded="false">
                        <div class="avatar-sm me-2">
                            @if ($user && (property_exists($user, 'profile_photo_path') && $user->profile_photo_path))
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="profile"
                                    class="avatar-img rounded-circle" />
                            @elseif($user && (property_exists($user, 'avatar') && $user->avatar))
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="profile"
                                    class="avatar-img rounded-circle" />
                            @else
                                <div class="avatar-initials rounded-circle d-flex align-items-center justify-content-center text-white"
                                    style="width:40px;height:40px;background:{{ $color }};font-weight:600">
                                    {{ $initials ?: 'U' }}</div>
                            @endif
                        </div>
                        <!-- Desktop view - tampilkan nama -->
                        <div class="d-none d-md-block">
                            <span class="op-7">Hi,</span>
                            <span class="fw-bold">{{ $user ? $displayName : 'User' }}</span>
                        </div>
                        <!-- Mobile view - tampilkan plat nomor jika user -->
                        @if (($user->role ?? null) === 'user' && !empty($user->plat_nomor))
                            <div class="d-block d-md-none" style="font-size: 0.85rem; font-weight: 600; color: #1a1a1a;">
                                 {{ $user->plat_nomor }}
                            </div>
                        @endif
                    </a>
                    
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <style>
                            .dropdown-user .user-box {
                                display: flex;
                                gap: 12px;
                                align-items: center;
                                padding: 12px;
                            }

                            .dropdown-user .avatar-lg img {
                                width: 64px;
                                height: 64px;
                                object-fit: cover;
                            }

                            .dropdown-user .u-text h4 {
                                margin: 0;
                                font-size: 1rem;
                            }

                            .dropdown-user .u-text p {
                                margin: 0;
                                font-size: 0.85rem;
                                color: #6c757d;
                            }

                            .dropdown-user .dropdown-footer {
                                padding: 12px;
                            }
                        </style>

                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg">
                                        @if ($user && (property_exists($user, 'profile_photo_path') && $user->profile_photo_path))
                                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}"
                                                alt="image profile" class="avatar-img rounded" />
                                        @elseif($user && (property_exists($user, 'avatar') && $user->avatar))
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="image profile"
                                                class="avatar-img rounded" />
                                        @else
                                            <div class="avatar-initials-lg rounded d-flex align-items-center justify-content-center text-white"
                                                style="width:64px;height:64px;background:{{ $color }};font-weight:600;font-size:22px">
                                                {{ $initials ?: 'U' }}</div>
                                        @endif
                                    </div>
                                    <div class="u-text">
                                        <h4>{{ $user ? $displayName : 'User' }}</h4>
                                        @if (($user->role ?? null) === 'user' && !empty($user->plat_nomor))
                                            <p class="text-muted">Plat: {{ $user->plat_nomor }}</p>
                                        @else
                                            <p class="text-muted">{{ $user ? $user->email : '—' }}</p>
                                        @endif
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                                {{-- <a class="dropdown-item" href="{{ route('profile.edit') ?? '#' }}">Account Settings</a> --}}
                                {{-- <a class="dropdown-item" href="#">Messages <span class="badge bg-primary ms-2">3</span></a> --}}
                                <div class="dropdown-divider"></div>
                                <div class="dropdown-footer d-flex gap-2 px-3">
                                    {{-- <a href="{{ route('profile.show') ?? '#' }}" class="btn btn-sm btn-light w-50">Profile</a> --}}
                                    <form method="POST" action="{{ route('logout') }}" class="w-50 m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger w-100">Logout</button>
                                    </form>
                                </div>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- End Navbar -->
</div>
<script>
    @if (auth()->check() && auth()->user()->role === 'admin')
    // Fungsi untuk memformat tipe notifikasi
    function getNotificationTypeLabel(type) {
        const labels = {
            'update': 'Update',
            'create': 'Baru',
            'delete': 'Hapus'
        };
        return labels[type] || type;
    }

    // Fungsi untuk load notifikasi
    function loadNotifications() {
        fetch('{{ route("admin.notifications.get") }}?limit=99')
        // Data dari server diubah ke bentuk JSON biar bisa dibaca JavaScript
            .then(response => response.json())
            .then(data => {

                // tempat daftar notifikasi & badge angka merah di lonceng
                const notificationList = document.getElementById('notification-list');
                const countBadge = document.getElementById('notification-count');

                // kalau notifikasi kosong
                if (data.notifications.length === 0) {
                    notificationList.innerHTML = '<div class="text-center py-4"><span class="text-muted">Tidak ada notifikasi</span></div>';
                    countBadge.style.display = 'none';
                } else {
                    // Kalau ada notifikasi
                    let unreadCount = 0;
                    let html = '';
                    //  satu persatu data notifikasi diproses
                    data.notifications.forEach(notif => {
                        // cek notifikasi belum dibaca
                        if (notif.is_unread) unreadCount++;
                        // notifikasi belum dibaca → kasih class unread
                        const unreadClass = notif.is_unread ? 'unread' : '';
                        // tipe notifikasi → ubah jadi label (misal: INFO, WARNING)
                        const typeLabel = getNotificationTypeLabel(notif.type);
                        
                        html += `
                            <div class="notification-item ${unreadClass}" onclick="markNotificationAsRead(${notif.id})" style="padding: 12px 15px; border-bottom: 1px solid #e7e7e7; cursor: pointer; transition: all 0.2s; border-left: 3px solid transparent;">
                                <div style="display: flex; gap: 10px; align-items: flex-start;">
                                    <span class="notification-item-badge ${notif.type}" style="min-width: fit-content; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">${typeLabel}</span>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; color: #1a1a1a; margin-bottom: 4px; font-size: 0.95rem;">${notif.table_name}</div>
                                        <div style="font-size: 0.85rem; color: #555; margin-bottom: 4px;">${notif.message}</div>
                                        <div style="font-size: 0.75rem; color: #999;">${notif.created_at}</div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    // Semua notifikasi dimunculkan ke halaman
                    notificationList.innerHTML = html;
                    // kalau ada notifikasi belum dibaca → badge muncul
                    if (unreadCount > 0) {
                        countBadge.textContent = unreadCount;
                        countBadge.style.display = 'flex';
                    } else {
                        countBadge.style.display = 'none';
                    }
                }
            })
            // error muncul di console
            .catch(error => console.error('Error loading notifications:', error));
    }

    // Fungsi untuk tandai satu notifikasi sebagai terbaca dan hapus otomatis
    function markNotificationAsRead(notificationId) {
        fetch('{{ route("admin.notifications.mark-as-read") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ id: notificationId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hapus notifikasi setelah ditandai terbaca
                deleteNotificationSilent(notificationId);
            }
        })
        .catch(error => console.error('Error marking notification as read:', error));
    }

    // Fungsi untuk hapus notifikasi secara silent (tanpa konfirmasi)
    function deleteNotificationSilent(notificationId) {
        fetch('{{ route("admin.notifications.delete", ":id") }}'.replace(':id', notificationId), {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications();
            }
        })
        .catch(error => console.error('Error deleting notification:', error));
    }

    // Fungsi untuk tandai semua notifikasi sebagai terbaca dan hapus otomatis
    function markAllNotificationsAsRead() {
        fetch('{{ route("admin.notifications.mark-all-as-read") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications();
            }
        })
        .catch(error => console.error('Error marking all as read:', error));
    }

    // Fungsi untuk hapus semua notifikasi
    function deleteAllNotifications() {
        fetch('{{ route("admin.notifications.delete-all") }}', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications();
            }
        })
        .catch(error => console.error('Error deleting all notifications:', error));
    }

    // Load notifikasi pertama kali
    document.addEventListener('DOMContentLoaded', function() {
        loadNotifications();
        
        // Refresh setiap 10 detik
        setInterval(loadNotifications, 10000);
    });
    @endif
</script>