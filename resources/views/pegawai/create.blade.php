<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Input Data Pegawai') }}
        </h2>
    </x-slot>

    <div class="container p-4">
        <a href="{{ route('pegawai.index') }}">&#8592; Kembali</a>

        @if ($errors->any())
        <div class="my-3" role="alert">
            <div class="bg-red-500 text-white font-bold rounded-t p-4">
                Input tidak sesuai!
                <p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </p>
            </div>
        </div>
        @endif

        <form action="{{ route('pegawai.store') }}" method="post" class="py-6">
            {{ csrf_field() }}

            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" id="nama" name="nama" placeholder="Nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="jabatan" class="form-label">Jabatan</label>
                <input type="text" id="jabatan" name="jabatan" placeholder="Jabatan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="umur" class="form-label">Umur</label>
                <input type="number" id="umur" name="umur" placeholder="Umur" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea name="alamat" id="alamat" placeholder="Alamat" class="form-control" required></textarea>
            </div>
            <div class="mb-3 d-flex justify-end">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>

    </div>
</x-app-layout>