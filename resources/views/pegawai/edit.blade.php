<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Data Pegawai')  }}
        </h2>
    </x-slot>

    <div class="container p-4">
        <a href="{{ route('pegawai.index') }}">&#8592; Kembali</a>

        <form action="{{ route('pegawai.update', ['id' => $pegawai->id]) }}" method="post" class="py-6">
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            <input type="hidden" name="id" value="{{ old('id') ?? $pegawai->id }}" required>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input
                    type="text" id="nama" name="nama" placeholder="Nama"
                    value="{{ old('nama') ?? $pegawai->nama }}"
                    class="form-control"
                    required
                >
            </div>
            <div class="mb-3">
                <label for="jabatan" class="form-label">Jabatan</label>
                <input
                type="text" id="jabatan" name="jabatan" placeholder="Jabatan"
                value="{{ old('jabatan') ?? $pegawai->jabatan }}"
                class="form-control"
                required
                >
            </div>
            <div class="mb-3">
                <label for="umur" class="form-label">Umur</label>
                <input
                    type="number" id="umur" name="umur" placeholder="Umur"
                    value="{{ old('umur') ?? $pegawai->umur }}"
                    class="form-control"
                    required
                >
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea
                    name="alamat" id="alamat" placeholder="Alamat"
                    class="form-control"
                    required
                >{{ old('alamat') ?? $pegawai->alamat }}</textarea>
            </div>
            <div class="mb-3 d-flex justify-end">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>

    </div>
</x-app-layout>