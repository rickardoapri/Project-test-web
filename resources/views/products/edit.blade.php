@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4 mx-4">Edit Produk</h1>
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4 mx-4">
                <label for="category_id" class="block">Kategori</label>
                <select name="category_id" id="category_id" class="border rounded-md px-4 py-2 w-full">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $product->kategori_id == $category->id ? 'selected' : '' }}>
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
                <input type="text" id="nama_produk" name="nama_produk" class="border rounded-md px-4 py-2 w-full" value="{{ $product->nama_produk }}">
                @error('nama_produk')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4 mx-4">
                <label for="harga_beli" class="block">Harga Beli</label>
                <input type="text" id="harga_beli" name="harga_beli" class="border rounded-md px-4 py-2 w-full" oninput="maskCurrency(this)" value="{{ number_format($product->harga_beli, 0, ',', '.') }}">
                @error('harga_beli')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4 mx-4">
                <label for="harga_jual" class="block">Harga Jual</label>
                <input type="text" id="harga_jual" name="harga_jual" class="border rounded-md px-4 py-2 w-full" readonly value="{{ number_format($product->harga_jual, 0, ',', '.') }}">
                @error('harga_jual')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4 mx-4">
                <label for="stok" class="block">Stok Barang</label>
                <input type="number" id="stok" name="stok" class="border rounded-md px-4 py-2 w-full" value="{{ $product->stok }}">
                @error('stok')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4 mx-4">
                <label for="gambar" class="block">Gambar Produk</label>
                <input type="file" id="gambar" name="gambar" class="border rounded-md px-4 py-2">
                @error('gambar')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
                <img src="{{ asset('storage/images/gambar-produk/' . $product->gambar) }}" alt="Gambar {{ $product->nama_produk }}" class="mt-2" style="max-width: 200px;">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mx-4 mb-4">Simpan</button>
            <a href="{{ route('products.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Batal</a>
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
