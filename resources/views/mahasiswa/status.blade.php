@extends('layouts.app')

@section('content')
<h2>Status Surat Saya</h2>
<a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">⬅️ Kembali ke Dashboard</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@forelse ($surats as $surat)
<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">{{ $surat->jenisSurat->nama_surat }}</h5>
        <p class="card-text">Perihal: {{ $surat->perihal }}</p>
        <p>Status: <span class="badge badge-info">{{ $surat->status }}</span></p>

        @if($surat->lampiran)
        <a href="{{ route('file.preview', ['filepath' => base64_encode($surat->lampiran->file_path)]) }}" target="_blank" class="btn btn-sm btn-info">📄 Preview File</a>
        @endif
        <a href="{{ route('surat.tracking', $surat->surat_id) }}" class="btn btn-sm btn-success">📍 Lacak Surat</a>

        @if(!in_array($surat->status, ['Disetujui', 'Ditolak']))
        <form action="{{ route('surat.batal', $surat->surat_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">Batalkan</button>
        </form>
        @endif
    </div>
</div>
@empty
    <p>Anda belum pernah mengajukan surat.</p>
@endforelse
@endsection