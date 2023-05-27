<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Data Pegawai') }}
            </h2>
            <a
                href="{{ route('pegawai.create') }}"
                class="btn btn-primary"
            >+ Add Pegawai</a>
        </div>
    </x-slot>

    <div class="container mx-auto py-4">
        <form action="{{ route('pegawai.index') }}" method="GET" class="d-flex justify-between gap-2 my-4">
            <input
                type="text"
                name="keyword"
                placeholder="Masukkan keyword pencarian.."
                class="form-control"
                value="{{ old('search') }}"
            >
            <button type="submit" class="btn btn-primary">
                Search
            </button>
        </form>
        <table class="table table-bordered" aria-label="tabel-pegawai">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Jabatan</th>
                    <th scope="col">Umur</th>
                    <th scope="col">Alamat</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
            @if (count($pegawai)>0)
                @foreach ($pegawai as $p)
                    <tr>
                        <th scope="row">{{ $p->id }}</th>
                        <td>{{ $p->nama }}</td>
                        <td>{{ $p->jabatan }}</td>
                        <td>{{ $p->umur }}</td>
                        <td>{{ $p->alamat }}</td>
                        <td class="d-flex gap-1">
                            <a href="{{ route('pegawai.edit', ['id' => $p->id]) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('pegawai.destroy', ['id' => $p->id]) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit" class="btn btn-danger">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr class="text-center">
                    <td colspan="6">Data tidak ditemukan</td>
                </tr>
            @endif
            </tbody>
        </table>

        <div>
            {{ $pegawai->links() }}
        </div>

    </div>
</x-app-layout>
