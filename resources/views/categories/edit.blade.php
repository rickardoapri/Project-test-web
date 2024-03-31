@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4 mx-4">Edit Kategori</h1>
        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4 mx-4">
                <label for="nama_kategori" class="block">Nama Kategori:</label>
                <input type="text" id="nama_kategori" name="nama_kategori" value="{{ $category->nama_kategori }}" class="border rounded-md px-4 py-2 w-full">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mx-4">Simpan</button>
            <a href="{{ route('categories.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Batal</a>
        </form>
    </div>
@endsection
