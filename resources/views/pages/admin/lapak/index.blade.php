@extends('layouts.template')

@section('title', 'Data Lapak - Admin')

@push('style')
    
@endpush

@section('main')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Data Lapak</h3>
            <h6 class="op-7 mb-2">Kelola dan approve data lapak dari petugas</h6>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Filter Card -->
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.lapak.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nama Lapak</label>
                                    <input type="text" name="nama_lapak" class="form-control" 
                                           placeholder="Cari nama lapak..." 
                                           value="{{ request('nama_lapak') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Status Approval</label>
                                    <select name="approval_status" class="form-control">
                                        <option value="">Semua Status</option>
                                        <option value="pending" {{ request('approval_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ request('approval_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ request('approval_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Cabang</label>
                                    <select name="cabang_id" class="form-control">
                                        <option value="">Semua Cabang</option>
                                        @foreach($cabangs as $cabang)
                                            <option value="{{ $cabang->id }}" {{ request('cabang_id') == $cabang->id ? 'selected' : '' }}>
                                                {{ $cabang->nama_cabang }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-search"></i> Filter
                                        </button>
                                        <a href="{{ route('admin.lapak.index') }}" class="btn btn-secondary btn-block mt-1">
                                            <i class="fas fa-redo"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Data Table Card -->
            <div class="card">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-head-bg-primary">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode Lapak</th>
                                    <th>Nama Lapak</th>
                                    <th>Collation Center</th>
                                    <th>Alamat</th>
                                    <th>Status</th>
                                    <th>Approval</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lapaks as $index => $lapak)
                                    <tr>
                                        <td>{{ $lapaks->firstItem() + $index }}</td>
                                        <td><strong>{{ $lapak->kode_lapak }}</strong></td>
                                        <td>{{ $lapak->nama_lapak }}</td>
                                        <td>
                                            {{ $lapak->cabang->nama_cabang ?? '-' }}<br>
                                            <small class="text-muted">{{ $lapak->cabang->kode_cabang ?? '-' }}</small>
                                        </td>
                                        <td>{{ Str::limit($lapak->alamat, 30) }}</td>
                                        <td>
                                            @if($lapak->status == 'aktif')
                                                <span class="badge badge-success" style="color: #000 !important;">Aktif</span>
                                            @else
                                                <span class="badge badge-danger" style="color: #000 !important;">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($lapak->approval_status == 'pending')
                                                <span class="badge badge-warning" style="color: #000 !important;">Pending</span>
                                            @elseif($lapak->approval_status == 'approved')
                                                <span class="badge badge-success" style="color: #000 !important;">Approved</span>
                                            @else
                                                <span class="badge badge-danger" style="color: #000 !important;">Rejected</span>
                                            @endif
                                            @if($lapak->approved_at)
                                                <br><small class="text-muted">{{ $lapak->approved_at->format('d/m/Y') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.lapak.show', $lapak->id) }}" class="btn btn-sm btn-info" title="Detail">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>
                                                
                                                @if($lapak->approval_status == 'pending' || $lapak->approval_status == 'rejected')
                                                    <form action="{{ route('admin.lapak.approve', $lapak->id) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('Apakah Anda yakin ingin menyetujui lapak ini?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                                            <i class="bi bi-check-circle-fill"></i> Approve
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($lapak->approval_status == 'pending' || $lapak->approval_status == 'approved')
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                            data-toggle="modal"
                                                            data-target="#rejectModal{{ $lapak->id }}"
                                                            title="Reject">
                                                        <i class="bi bi-x-circle-fill"></i> Reject
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $lapak->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.lapak.reject', $lapak->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tolak Lapak</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Alasan Penolakan <span class="text-danger">*</span></label>
                                                            <textarea name="rejection_reason" 
                                                                      class="form-control" 
                                                                      rows="4" 
                                                                      required
                                                                      placeholder="Masukkan alasan penolakan..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Tolak Lapak</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <p class="text-muted my-3">Belum ada data lapak</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
                        <div class="text-muted small mb-2 mb-md-0">
                            Menampilkan {{ $lapaks->firstItem() ?? 0 }} - {{ $lapaks->lastItem() ?? 0 }}
                            dari {{ $lapaks->total() }} data
                        </div>
                        <div>
                            {{ $lapaks->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('sweetalert::alert')
@endpush
