    @extends('layouts.template')

    @section('title', 'Pengiriman Sampah')

    @push('style')
        <!-- CSS Libraries -->
    @endpush

    @section('main')
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Daftar Pengiriman Sampah</h3>
                <h6 class="op-7 mb-2">
                    Anda dapat mengelola semua pengiriman sampah ke pengepul.
                </h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <div class="section-header-button">
                    <a href="{{ route('petugas.pengiriman.create') }}" class="btn btn-primary btn-round">Lakukan Pengiriman</a>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-head-bg-primary">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th>Tanggal Pengiriman</th>
                                        <th>Kode Pengiriman</th>
                                        <th>Cabang</th>
                                        <th>Gudang</th>
                                        <th>Status</th>
                                        <th style="width: 120px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pengirimanSampah as $index => $pengiriman)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($pengiriman->tanggal_pengiriman)->format('d-m-Y') }}
                                            </td>
                                            <td>{{ $pengiriman->kode_pengiriman }}</td>
                                            <td>{{ $pengiriman->cabang->nama_cabang ?? '-' }}</td>
                                            <td>{{ $pengiriman->gudang->nama_gudang ?? '-' }}</td>
                                            <td>
                                                @switch($pengiriman->status_pengiriman)
                                                    @case('draft')
                                                        <span class="badge bg-secondary">Draft</span>
                                                    @break

                                                    @case('dikirim')
                                                        <span class="badge bg-info text-white">Dikirim</span>
                                                    @break

                                                    @case('diterima')
                                                        <span class="badge bg-success">Diterima</span>
                                                    @break

                                                    @case('batal')
                                                        <span class="badge bg-danger">Batal</span>
                                                    @break

                                                    @default
                                                        <span class="badge bg-light text-dark">Tidak Diketahui</span>
                                                @endswitch
                                            </td>
                                            <td class="text-center">
                                                <div class="row">
                                                    <div class="col">
                                                        <a href="{{ route(Auth::user()->role . '.pengiriman.show', $pengiriman->kode_pengiriman) }}"
                                                            class="btn btn-sm btn-info">
                                                            Detail
                                                        </a>
                                                    </div>

                                                    @if (Auth::user()->role === 'petugas')
                                                        <div class="col">
                                                            <a href="{{ route('petugas.pengiriman.edit', $pengiriman->kode_pengiriman) }}"
                                                                class="btn btn-sm btn-primary">
                                                                Edit
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>

                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">
                                                    Belum ada pengiriman sampah.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <div class="float-right">
                                    {{-- {{ $pengirimanSampah->withQueryString()->links() }} --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endsection

        @push('scripts')
            <!-- JS Libraries -->
        @endpush
