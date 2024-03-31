@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold mx-4">Daftar Kategori</h1>
            <a href="{{ route('categories.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mx-4">Tambah Kategori Baru</a>
        </div>
        <div class="bg-white shadow-md rounded-lg mx-4">
            <table id="categoryTable" class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2 px-4">No</th>
                        <th class="text-left py-2 px-4">Nama Kategori</th>
                        <th class="text-left py-2 px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="py-2 px-4">{{ $loop->iteration }}</td>
                            <td class="py-2 px-4">{{ $category->nama_kategori }}</td>
                            <td class="py-2 px-4 flex">
                                <a href="{{ route('categories.edit', $category->id) }}" class="flex items-center">
                                    <img src="{{ asset('images/edit.png') }}" alt="Edit" class="w-4 h-4 mr-2">
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: '{{ session('success') }}',
            });
        </script>
    @endif

    
@endsection
