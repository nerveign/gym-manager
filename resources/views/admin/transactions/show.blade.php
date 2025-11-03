<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-item:before {
            content: '';
            position: absolute;
            left: -21px;
            top: 8px;
            bottom: -12px;
            width: 2px;
            background: #dee2e6;
        }

        .timeline-item:last-child:before {
            display: none;
        }

        .timeline-marker {
            position: absolute;
            left: -25px;
            top: 4px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #dee2e6;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Fixed Sidebar -->
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
           <x-dashboard-header name="{{ auth()->user()->name }}" />
            
            <nav class="mt-6">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>
                
                <x-nav-item text="Home" color="text-zinc-700" src="home.svg" location="admin.dashboard" />
                
                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Management</div>                
                <x-nav-item text="Users" color="text-zinc-700" src="users.svg" location="admin.users_management" />
                <x-nav-item text="Trainer" color="text-gray-600" src="user.svg" location="admin.trainers_management" />
                <x-nav-item text="Booking" color="text-gray-600" src="calendar.svg" location="admin.bookings_management" />
                <x-nav-item text="Class" color="text-gray-600" src="class.svg" location="admin.classes_management" />
                <x-nav-item text="Equipment" color="text-gray-600" src="equipment.svg" location="admin.equipments_management" />
                <x-nav-item text="Transaction" color="text-gray-600" src="dollar-sign.svg" location="admin.transactions.index" style="bg-blue-50 border-r-4 border-blue-500" />
            </nav>
            
            <!-- User Profile Section -->
            <div class="absolute bottom-0 w-64 p-4  flex justify-between bg-white">
                <div class="flex items-center">
                    <a href="{{ route('profile.edit') }}">
                        <img class="w-8 h-8 rounded-full" src="{{ auth()->user()->image_url }}" alt="{{ auth()->user()->name }}">
                    </a>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">Administrator</p>
                    </div>
                </div>
                <div>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                        @csrf
                    </form>
                    <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        <img src="{{ asset('icons/logout.svg') }}" alt="logout" class="w-4 h-4 mr-1">
                        <span>Logout</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 ml-64">
            <!-- Scrollable Content -->
            <main class="pt-4 pb-8 px-4 h-screen overflow-y-auto scroll-container">
                <!-- Header -->
                <div class="p-2 mb-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Detail Transaksi</h2>
                            <nav class="text-sm text-gray-500">
                                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                                <span class="mx-2">/</span>
                                <a href="{{ route('admin.transactions.index') }}" class="hover:text-blue-600">Transaksi</a>
                                <span class="mx-2">/</span>
                                <span>Detail</span>
                            </nav>
                        </div>
                        <a href="{{ route('admin.transactions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                    </div>
                </div>
                
                <!-- Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Transaction Details -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white border-0 shadow-sm rounded-lg">
                            <div class="px-6 py-4 bg-white border-b rounded-t-lg">
                                <h5 class="text-lg font-semibold text-gray-900 mb-0">Informasi Transaksi</h5>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted" width="40%">Order ID:</td>
                                    <td class="fw-semibold">{{ $transaction->id }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Midtrans Transaction ID:</td>
                                    <td class="fw-semibold">
                                        {{ $transaction->payment_gateway_id ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Amount:</td>
                                    <td class="fw-semibold text-success">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Metode Pembayaran:</td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->payment_method == 'midtrans' ? 'info' : 'secondary' }}">
                                            {{ ucfirst($transaction->payment_method) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted" width="40%">Status:</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'completed' => 'success',
                                                'pending' => 'warning',
                                                'failed' => 'danger'
                                            ];
                                            $statusLabels = [
                                                'completed' => 'Berhasil',
                                                'pending' => 'Pending',
                                                'failed' => 'Gagal'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$transaction->status] ?? 'secondary' }}">
                                            {{ $statusLabels[$transaction->status] ?? $transaction->status }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tanggal Transaksi:</td>
                                    <td class="fw-semibold">{{ $transaction->created_at->format('d/m/Y H:i:s') }} WIB</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Terakhir Update:</td>
                                    <td class="fw-semibold">{{ $transaction->updated_at->format('d/m/Y H:i:s') }} WIB</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Snap Token:</td>
                                    <td>
                                        @if($transaction->snap_token)
                                            <small class="text-muted font-monospace">{{ substr($transaction->snap_token, 0, 20) }}...</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            @if($transaction->membership && $transaction->membership->user)
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Informasi Customer</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        @if($transaction->membership->user->image_url)
                            <img src="{{ $transaction->membership->user->image_url }}" 
                                 class="rounded-circle me-3" width="64" height="64">
                        @else
                            <div class="bg-secondary rounded-circle me-3 d-flex align-items-center justify-center" 
                                 style="width: 64px; height: 64px;">
                                <i class="fas fa-user text-white fa-2x"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h5 class="mb-1">{{ $transaction->membership->user->name }}</h5>
                            <p class="text-muted mb-2">{{ $transaction->membership->user->email }}</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">Telepon:</small><br>
                                    <span>{{ $transaction->membership->user->phone ?? '-' }}</span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Role:</small><br>
                                    <span class="badge bg-primary">{{ ucfirst($transaction->membership->user->role) }}</span>
                                </div>
                            </div>
                            @if($transaction->membership->user->address)
                            <div class="mt-2">
                                <small class="text-muted">Alamat:</small><br>
                                <span>{{ $transaction->membership->user->address }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Membership Information -->
            @if($transaction->membership)
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Informasi Membership</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">Status Membership:</small><br>
                            <span class="badge bg-{{ $transaction->membership->status == 'active' ? 'success' : 'warning' }}">
                                {{ ucfirst($transaction->membership->status) }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Tanggal Mulai:</small><br>
                            <span>{{ $transaction->membership->start_time ? $transaction->membership->start_time->format('d/m/Y H:i') . ' WIB' : '-' }}</span>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <small class="text-muted">Tanggal Berakhir:</small><br>
                            <span>{{ $transaction->membership->end_time ? $transaction->membership->end_time->format('d/m/Y H:i') . ' WIB' : '-' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Dibuat:</small><br>
                            <span>{{ $transaction->membership->created_at->format('d/m/Y H:i') }} WIB</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Actions Sidebar -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Aksi</h5>
                </div>
                <div class="card-body">
                    @if($transaction->status !== 'completed')
                    <button class="btn btn-success w-100 mb-2" onclick="updateStatus({{ $transaction->id }}, 'completed')">
                        <i class="fas fa-check me-2"></i>Tandai Berhasil
                    </button>
                    @endif
                    
                    @if($transaction->status !== 'pending')
                    <button class="btn btn-warning w-100 mb-2" onclick="updateStatus({{ $transaction->id }}, 'pending')">
                        <i class="fas fa-clock me-2"></i>Tandai Pending
                    </button>
                    @endif
                    
                    @if($transaction->status !== 'failed')
                    <button class="btn btn-outline-danger w-100 mb-2" onclick="updateStatus({{ $transaction->id }}, 'failed')">
                        <i class="fas fa-times me-2"></i>Tandai Gagal
                    </button>
                    @endif
                    
                    <hr>
                    
                    <button class="btn btn-danger w-100" onclick="deleteTransaction({{ $transaction->id }})">
                        <i class="fas fa-trash me-2"></i>Hapus Transaksi
                    </button>
                </div>
            </div>

            <!-- Transaction Timeline -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Transaksi Dibuat</h6>
                                <small class="text-muted">{{ $transaction->created_at->format('d/m/Y H:i:s') }} WIB</small>
                            </div>
                        </div>
                        @if($transaction->updated_at != $transaction->created_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Status Diupdate</h6>
                                <small class="text-muted">{{ $transaction->updated_at->format('d/m/Y H:i:s') }} WIB</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin mengubah status transaksi ini?</p>
                    <input type="hidden" name="status" id="newStatus">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus transaksi ini? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

            </main>
        </div>
    </div>

<script>
function updateStatus(transactionId, status) {
    document.getElementById('statusForm').action = `{{ url('admin/transactions') }}/${transactionId}/status`;
    document.getElementById('newStatus').value = status;
    
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}

function deleteTransaction(transactionId) {
    document.getElementById('deleteForm').action = `{{ url('admin/transactions') }}/${transactionId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
</body>
</html>
