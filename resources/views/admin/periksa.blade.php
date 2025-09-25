@extends('layouts.app')

@section('content')
<h2>Detail Surat</h2>
<div class="card my-4">
    <div class="card-body">
        <h5 class="card-title"><strong>Jenis:</strong> {{ $surat->jenisSurat->nama_surat }}</h5>
        <p class="card-text"><strong>Perihal:</strong> {{ $surat->perihal }}</p>
        <p class="card-text"><strong>Status Sekarang:</strong> <span class="badge badge-info">{{ $surat->status }}</span></p>
    </div>
</div>

@if($surat->lampiran)
<div class="mb-4">
    <h3>Lampiran</h3>
    <embed src="{{ route('file.preview', ['filepath' => base64_encode($surat->lampiran->file_path)]) }}" type="application/pdf" width="100%" height="600px" />
</div>
@endif

<div class="d-flex justify-content-start">
    <form action="{{ route('surat.approve', $surat->surat_id) }}" method="POST" class="mr-2">
        @csrf
        <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda yakin ingin menyetujui dan meneruskan surat ini?');">✅ Approve & Teruskan</button>
    </form>

    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
        ❌ Reject
    </button>
</div>

<a href="{{ route('surat.masuk') }}" class="btn btn-secondary mt-3">⬅️ Kembali ke Daftar Surat</a>

<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Alasan Penolakan Surat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('surat.reject', $surat->surat_id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="catatan">Tuliskan alasan penolakan:</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="4" required minlength="10"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection