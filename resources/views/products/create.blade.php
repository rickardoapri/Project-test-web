@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4 mx-4">Tambah Produk Baru</h1>
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4 mx-4">
                <label for="category_id" class="block">Kategori:</label>
                <select name="category_id" id="category_id" class="border rounded-md px-4 py-2 w-full">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4 mx-4">
                <label for="nama_produk" class="block">Nama Produk</label>
                <input type="text" id="nama_produk" name="nama_produk" class="border rounded-md px-4 py-2 w-full" value="{{ old('nama_produk') }}">
                @error('nama_produk')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4 mx-4">
                <label for="harga_beli" class="block">Harga Beli</label>
                <input type="text" id="harga_beli" name="harga_beli" class="border rounded-md px-4 py-2 w-full" value="{{ old('harga_beli') }}" oninput="maskCurrency(this)">
                @error('harga_beli')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4 mx-4">
                <label for="harga_jual" class="block">Harga Jual</label>
                <input type="text" id="harga_jual" name="harga_jual" class="border rounded-md px-4 py-2 w-full" value="{{ old('harga_jual') }}" readonly>
                @error('harga_jual')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4 mx-4">
                <label for="stok" class="block">Stok Barang</label>
                <input type="number" id="stok" name="stok" class="border rounded-md px-4 py-2 w-full" value="{{ old('stok') }}">
                @error('stok')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4 mx-4">
                <label for="gambar" class="block">Gambar Produk</label>
                <input type="file" id="gambar" name="gambar" class="border rounded-md px-4 py-2" accept="image/jpeg,image/png">
                @error('gambar')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 my-4 mx-4">Simpan</button>
        </form>
    </div>

    <script>
        // Fungsi untuk menambahkan pemisah ribuan pada input harga beli
        function maskCurrency(input) {
            // Hapus karakter non-digit dari input
            var sanitized = input.value.replace(/[^0-9]/g, '');
            
            // Format angka dengan pemisah ribuan
            var formatted = sanitized.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            
            // Tampilkan angka yang diformat kembali di input
            input.value = formatted;
            
            // Hitung harga jual otomatis berdasarkan harga beli
            var hargaBeli = parseInt(sanitized.replace(/\D/g, ''));
            var hargaJual = hargaBeli + (hargaBeli * 0.3);
            
            // Set nilai harga jual otomatis dan tambahkan pemisah ribuan
            document.getElementById('harga_jual').value = hargaJual.toLocaleString();
        }
    </script>
@endsection
