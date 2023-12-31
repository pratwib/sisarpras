<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<title>Pinjam</title>

@include('partials.head')

<body>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        @if (session()->has('message'))
        <div class="bs-toast toast fade show toast-placement-ex bg-primary top-0 start-50 translate-middle-x m-3" role=" alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <div class="me-auto fw-semibold">Sisarpras</div>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">{{ session()->get('message') }}</div>
        </div>
        @endif
        <div class="layout-container">

            @include('partials.menu')

            <!-- Layout container -->
            <div class="layout-page">

                @include('partials.header')

                <!-- Content wrapper -->
                <div class="content-wrapper">

                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-style1">
                                <li class="breadcrumb-item mb-2">
                                    <a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Pinjam</li>
                            </ol>
                        </nav>

                        <!-- Small table -->
                        <div class="card">

                            <!-- Header table -->
                            <div class="card-header d-flex align-items-center">
                                <h5 class="card-title">Daftar Pinjam Barang</h5>
                            </div>

                            <!-- Tabel -->
                            <div class="ms-4 me-4 mb-4 table-responsive text-nowrap">
                                <table id="dataTable" class="table table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama</th>
                                            <th>Barang</th>
                                            <th>Jumlah</th>
                                            <th>Lokasi</th>
                                            <th>Tanggal Pengembalian</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @foreach($borrows as $borrow)
                                        <tr>
                                            <td>{{ $borrow->borrow_id }}</td>
                                            <td><strong>{{ $borrow->name }}</strong></td>
                                            <td><strong>{{ $borrow->item_name }}</strong></td>
                                            <td>{{ $borrow->lend_quantity }}</td>
                                            <td>{{ $borrow->location_name }}</td>
                                            <td><strong>{{ $borrow->return_date }}</strong></td>
                                            <td>
                                                @if ($borrow->lend_status == 'requested')
                                                <span class="badge bg-label-secondary"> Diajukan</span>
                                                @endif
                                                @if ($borrow->lend_status == 'approved')
                                                <span class="badge bg-label-success">Disetujui</span>
                                                @endif
                                                @if ($borrow->lend_status == 'borrowed')
                                                <span class="badge bg-label-primary">Dipinjam</span>
                                                @endif
                                                @if ($borrow->lend_status == 'overdue')
                                                <span class="badge bg-label-warning">Terlambat</span>
                                                @endif
                                            <td>

                                                <!-- Button Detail Modal -->
                                                <button type="button" href="#detailModal{{ $borrow->borrow_id }}" class="btn btn-sm btn-icon btn-warning" data-bs-toggle="modal" data-bs-target="#detailModal{{ $borrow->borrow_id }}">
                                                    <span class="tf-icons bx bx-detail"></span>
                                                </button>

                                                <!-- Detail Borrow Modal -->
                                                <div class="modal fade" id="detailModal{{ $borrow->borrow_id }}" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalTitle">Detail Peminjaman</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('borrow.detail.'. $user->role, ['id' => $borrow->borrow_id]) }}" method="GET" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label for="name" class="form-label">Nama</label>
                                                                        <input readonly type="text" class="form-control" id="name" name="name" value="{{ $borrow->name }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="hp_number" class="form-label">Nomor HP</label>
                                                                        <input readonly type="tel" class="form-control" id="hp_number" name="hp_number" value="{{ $borrow->hp_number }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="item_name" class="form-label">Barang</label>
                                                                        <input readonly type="text" class="form-control" id="item_name" name="item_name" value="{{ $borrow->item_name }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="location_name" class="form-label">Lokasi</label>
                                                                        <input readonly type="text" class="form-control" id="location_name" name="location_name" value="{{ $borrow->location_name }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="lend_quantity" class="form-label">Jumlah</label>
                                                                        <input readonly type="number" class="form-control @error('lend_quantity') is-invalid @enderror" id="lend_quantity" name="lend_quantity" value="{{ $borrow->lend_quantity }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="lend_date" class="form-label">Tanggal Peminjaman</label>
                                                                        <input readonly type="datetime-local" class="form-control" id="lend_date" name="lend_date" value="{{ $borrow->lend_date }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="return_date" class="form-label">Tanggal Pengembalian</label>
                                                                        <input readonly type="date" class="form-control" id="return_date" name="return_date" value="{{ $borrow->return_date }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="lend_detail" class="form-label">Tujuan</label>
                                                                        <textarea readonly type="text" class="form-control" rows="3">{{ $borrow->lend_detail }}</textarea>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="lend_photo" class="form-label">Foto Selfie</label>
                                                                        <br>
                                                                        @if($borrow->lend_photo)
                                                                        <img src="{{ asset('storage/images/borrows/' . $borrow->lend_photo) }}" id="lend_photo" alt="Lend Photo" class="img-fluid" style="max-width: 240px;">
                                                                        @else
                                                                        <br>
                                                                        <p>Foto tidak tersedia</p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if($user->role === 'user'&&$borrow->lend_status == 'requested')
                                                <!-- Button Cancel Modal -->
                                                <button type="button" href="#cancelModal{{ $borrow->borrow_id }}" class="btn btn-sm btn-icon btn-danger ms-3" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $borrow->borrow_id }}">
                                                    <span class="tf-icons bx bx-x"></span>
                                                </button>

                                                <!-- Cancel Borrow Modal -->
                                                <div class="modal fade" id="cancelModal{{ $borrow->borrow_id }}" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('borrow.cancel.'. $user->role, ['id' => $borrow->borrow_id]) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="borrow_id" id="borrow_id" value="{{ $borrow->borrow_id }}">
                                                                    <div class="row">
                                                                        <div class="col mb-3">
                                                                            <h5 class="text" style="text-align: center; max-width: 30ch; overflow-wrap: break-word; white-space: normal;">Kamu yakin ingin membatalkan<br>peminjaman ini?</h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Kembali</button>
                                                                    <button type="submit" class="btn btn-danger">Ya, batalkan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if($user->role === 'admin'||$user->role === 'superadmin')

                                                @if($borrow->lend_status === 'requested')
                                                <!-- Button Approve Modal -->
                                                <button type="button" href="#approveModal{{ $borrow->borrow_id }}" class="btn btn-sm btn-icon btn-success ms-3" data-bs-toggle="modal" data-bs-target="#approveModal{{ $borrow->borrow_id }}">
                                                    <span class="tf-icons bx bx-check"></span>
                                                </button>

                                                <!-- Approve Borrow Modal -->
                                                <div class="modal fade" id="approveModal{{ $borrow->borrow_id }}" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('borrow.approve.'. $user->role, ['id' => $borrow->borrow_id]) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="borrow_id" id="borrow_id" value="{{ $borrow->borrow_id }}">
                                                                    <div class="row">
                                                                        <div class="col mb-3">
                                                                            <h5 class="text" style="text-align: center; max-width: 30ch; overflow-wrap: break-word; white-space: normal;">Kamu yakin ingin menyetujui<br>peminjaman ini?</h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Kembali</button>
                                                                    <button type="submit" class="btn btn-danger">Ya, setuju</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Button Decline Modal -->
                                                <button type="button" href="#declineModal{{ $borrow->borrow_id }}" class="btn btn-sm btn-icon btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#declineModal{{ $borrow->borrow_id }}">
                                                    <span class="tf-icons bx bx-x"></span>
                                                </button>

                                                <!-- Decline Borrow Modal -->
                                                <div class="modal fade" id="declineModal{{ $borrow->borrow_id }}" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('borrow.decline.'. $user->role, ['id' => $borrow->borrow_id]) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="borrow_id" id="borrow_id" value="{{ $borrow->borrow_id }}">
                                                                    <div class="row">
                                                                        <div class="col mb-3">
                                                                            <h5 class="text" style="text-align: center; max-width: 30ch; overflow-wrap: break-word; white-space: normal;">Kamu yakin ingin menolak<br>peminjaman ini?</h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Kembali</button>
                                                                    <button type="submit" class="btn btn-danger">Ya, tolak</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if($borrow->lend_status === 'approved')
                                                <!-- Button Borrow Modal -->
                                                <button type="button" href="#borrowModal{{ $borrow->borrow_id }}" class="btn btn-sm btn-primary ms-3" data-bs-toggle="modal" data-bs-target="#borrowModal{{ $borrow->borrow_id }}"><span style="white-space: nowrap;">Dipinjam</span></button>

                                                <!-- Borrow Borrow Modal -->
                                                <div class="modal fade" id="borrowModal{{ $borrow->borrow_id }}" tabindex="-1" aria-labelledby="borrowModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('borrow.borrow.'. $user->role, ['id' => $borrow->borrow_id]) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="borrow_id" id="borrow_id" value="{{ $borrow->borrow_id }}">
                                                                    <div class="row">
                                                                        <div class="col mb-3">
                                                                            <h5 class="text" style="text-align: center; max-width: 30ch; overflow-wrap: break-word; white-space: normal;">Kamu yakin peminjaman ini<br>sudah diambil?</h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Kembali</button>
                                                                    <button type="submit" class="btn btn-danger">Ya, sudah</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if($borrow->lend_status === 'borrowed'||$borrow->lend_status === 'overdue')
                                                <!-- Button Return Modal -->
                                                <button type="button" href="#returnModal{{ $borrow->borrow_id }}" class="btn btn-sm btn-primary ms-3" data-bs-toggle="modal" data-bs-target="#returnModal{{ $borrow->borrow_id }}"><span style="white-space: nowrap;">Dikembalikan</span></button>

                                                <!-- Approve Return Modal -->
                                                <div class="modal fade" id="returnModal{{ $borrow->borrow_id }}" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('borrow.return.'. $user->role, ['id' => $borrow->borrow_id]) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="borrow_id" id="borrow_id" value="{{ $borrow->borrow_id }}">
                                                                    <div class="row">
                                                                        <div class="col mb-3">
                                                                            <h5 class="text" style="text-align: center; max-width: 30ch; overflow-wrap: break-word; white-space: normal;">Kamu yakin peminjaman ini<br>sudah dikembalikan?</h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Kembali</button>
                                                                    <button type="submit" class="btn btn-danger">Ya, sudah</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--/ Small table -->
                    </div>
                    <!-- / Content -->

                    @include('partials.footer')

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    @include('partials.script')
</body>

</html>