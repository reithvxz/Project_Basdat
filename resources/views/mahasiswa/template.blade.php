@extends('layouts.app')

@section('content')
<h2>Template Surat</h2>
<div class="list-group my-4">
    @forelse ($templates as $template)
        <div class="list-group-item">
            <strong>{{ $template->jenisSurat->nama_surat }}</strong> - {{ $template->nama_template }}
            <a href="{{ $template->file_link }}" target="_blank" class="btn btn-primary btn-sm float-right">Download</a>
        </div>
    @empty
        <div class="list-group-item">Belum ada template surat yang tersedia.</div>
    @endforelse
</div>
<a href="{{ route('dashboard') }}" class="btn btn-secondary">⬅️ Kembali ke Dashboard</a>
@endsection