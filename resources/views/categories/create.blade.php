@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4 mx-4">Tambah Kategori Baru</h1>
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="mb-4 mx-4">
                <label for="nama_kategori" class="block">Nama Kategori:</label>
                <input type="text" id="nama_kategori" name="nama_kategori" class="border rounded-md px-4 py-2 w-full">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mx-4">Simpan</button>
        </form>
    </div>
@endsection
