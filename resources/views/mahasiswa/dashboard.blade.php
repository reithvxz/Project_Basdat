@extends('layouts.app')

@section('content')
<h2>Dashboard - Halo, {{ $nama }}</h2>
<div class="list-group">
    <a href="{{ route('pengajuan.create') }}" class="list-group-item list-group-item-action">Pengajuan Surat</a>
    <a href="{{ route('status') }}" class="list-group-item list-group-item-action">Status Surat</a>
    <a href="{{ route('template.index') }}" class="list-group-item list-group-item-action">Template Surat</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <a href="{{ route('logout') }}" class="list-group-item list-group-item-action"
            onclick="event.preventDefault(); this.closest('form').submit();">
            Logout
        </a>
    </form>
</div>
@endsection