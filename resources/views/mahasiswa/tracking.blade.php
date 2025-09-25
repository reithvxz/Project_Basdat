@extends('layouts.app')

@section('content')
<h2>Tracking Surat: {{ $surat->perihal }}</h2>

<style>
    .tracking-wrapper {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        padding-bottom: 15px;
    }
    .tracking-step {
        padding: 10px 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        text-align: center;
        white-space: nowrap;
        background-color: #f8f9fa;
    }
    .tracking-step.active {
        background-color: #ffc107; /* Kuning */
        border-color: #e0a800;
    }
    .tracking-step.completed {
        background-color: #28a745; /* Hijau */
        color: white;
        border-color: #218838;
    }
    .tracking-step.rejected {
        background-color: #dc3545; /* Merah */
        color: white;
        border-color: #c82333;
    }
</style>

<div class="tracking-wrapper my-4">
    @php
        $isCompleted = ($surat->status == 'Disetujui');
        $isRejected = ($surat->status == 'Ditolak');
        $activeIndex = array_search($surat->status, $alur);
    @endphp

    @foreach ($alur as $index => $step)
        @php
            $stepClass = 'tracking-step';
            if ($isCompleted) {
                $stepClass .= ' completed';
            } elseif ($isRejected) {
                // Hanya tandai step 'Ditolak' jika statusnya ditolak
            } elseif ($activeIndex !== false && $index < $activeIndex) {
                $stepClass .= ' completed';
            }

            if ($surat->status == $step) {
                $stepClass .= ' active';
            }
        @endphp
        <div class="{{ $stepClass }}">{{ $step }}</div>
    @endforeach

    {{-- Selalu tampilkan status Ditolak di akhir untuk alur visual --}}
    <div class="tracking-step {{ $isRejected ? 'rejected active' : '' }}">Ditolak</div>
</div>

@if ($isRejected && $surat->approvals->where('status', 'Rejected')->first())
<div class="alert alert-danger">
    <strong>Alasan Penolakan:</strong>
    <p>{{ $surat->approvals->where('status', 'Rejected')->first()->catatan }}</p>
</div>
@endif

<a href="{{ route('status') }}" class="btn btn-secondary">⬅️ Kembali ke Status Surat</a>
@endsection