@extends('layouts.app')

@section('content')
<h2>Form Pengajuan Surat</h2>

@if ($errors->any())
    <div class="alert alert-danger mt-3">
        <strong>Terjadi Kesalahan:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('pengajuan.store') }}" enctype="multipart/form-data" class="my-4">
    @csrf

    <div class="form-group">
        <label for="atas_nama">Atas Nama:</label>
        <select name="atas_nama" id="atas_nama" class="form-control" required>
            <option value="">-- Pilih Atas Nama --</option>
            <option value="HIMA" {{ old('atas_nama') == 'HIMA' ? 'selected' : '' }}>HIMA</option>
            <option value="BSO" {{ old('atas_nama') == 'BSO' ? 'selected' : '' }}>BSO</option>
            <option value="BEM" {{ old('atas_nama') == 'BEM' ? 'selected' : '' }}>BEM</option>
        </select>
    </div>

    <div id="hima_field" class="form-group" style="display:none;">
        <label for="ormawa_id_hima">Pilih HIMA:</label>
        <select name="ormawa_id_hima" id="ormawa_id_hima" class="form-control">
            @foreach($himas as $hima)
            <option value="{{ $hima->ormawa_id }}">{{ $hima->nama_ormawa }}</option>
            @endforeach
        </select>
    </div>

    <div id="bso_field" class="form-group" style="display:none;">
        <label for="ormawa_id_bso">Pilih BSO:</label>
        <select name="ormawa_id_bso" id="ormawa_id_bso" class="form-control">
            @foreach($bsos as $bso)
            <option value="{{ $bso->ormawa_id }}">{{ $bso->nama_ormawa }}</option>
            @endforeach
        </select>
    </div>

    <input type="hidden" name="ormawa_id" id="ormawa_id">

    <div class="form-group">
        <label for="jenis_id">Jenis Surat:</label>
        <select name="jenis_id" id="jenis_id" class="form-control" required>
            <option value="">-- Pilih Jenis Surat --</option>
            @foreach($jenisSurats as $jenis)
            <option value="{{ $jenis->jenis_id }}" {{ old('jenis_id') == $jenis->jenis_id ? 'selected' : '' }}>{{ $jenis->nama_surat }}</option>
            @endforeach
        </select>
        <div id="template_link" class="mt-2"></div>
    </div>

    <div class="form-group">
        <label for="perihal">Perihal:</label>
        <input type="text" name="perihal" id="perihal" class="form-control" value="{{ old('perihal') }}" required>
    </div>

    <div class="form-group">
        <label for="deskripsi">Deskripsi:</label>
        <textarea name="deskripsi" id="deskripsi" class="form-control" required>{{ old('deskripsi') }}</textarea>
    </div>

    <div class="form-group">
        <label for="lampiran">Upload Lampiran (Template yang sudah diedit, PDF, maks 2MB):</label>
        <input type="file" name="lampiran" id="lampiran" class="form-control-file" accept="application/pdf" required>
    </div>

    <button type="submit" class="btn btn-primary">Ajukan</button>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const atasNamaSelect = document.getElementById('atas_nama');
        const himaField = document.getElementById('hima_field');
        const bsoField = document.getElementById('bso_field');
        const himaSelect = document.getElementById('ormawa_id_hima');
        const bsoSelect = document.getElementById('ormawa_id_bso');
        const hiddenOrmawaId = document.getElementById('ormawa_id');
        const jenisSelect = document.getElementById('jenis_id');
        const templateLinkDiv = document.getElementById('template_link');

        function toggleOrmawa() {
            const val = atasNamaSelect.value;
            himaField.style.display = 'none';
            bsoField.style.display = 'none';
            hiddenOrmawaId.value = '';

            if (val === 'HIMA') {
                himaField.style.display = 'block';
                hiddenOrmawaId.value = himaSelect.value;
            } else if (val === 'BSO') {
                bsoField.style.display = 'block';
                hiddenOrmawaId.value = bsoSelect.value;
            }
        }

        himaSelect.addEventListener('change', () => hiddenOrmawaId.value = himaSelect.value);
        bsoSelect.addEventListener('change', () => hiddenOrmawaId.value = bsoSelect.value);

        function showTemplateLink() {
            const jenisId = jenisSelect.value;
            templateLinkDiv.innerHTML = '';
            if (jenisId) {
                fetch(`{{ route('ajax.template.link') }}?jenis_id=${jenisId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.link) {
                            templateLinkDiv.innerHTML = `📄 <a href="${data.link}" target="_blank" class="text-primary">Download Template Surat</a>`;
                        }
                    });
            }
        }

        atasNamaSelect.addEventListener('change', toggleOrmawa);
        jenisSelect.addEventListener('change', showTemplateLink);

        // Panggil fungsi saat halaman dimuat untuk menangani old value
        toggleOrmawa();
        showTemplateLink();
    });
</script>
@endpush