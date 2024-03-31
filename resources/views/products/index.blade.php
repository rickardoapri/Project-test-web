@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-4 mx-4">
            <h1 class="text-2xl font-bold mb-4">Daftar Produk</h1>
        </div>
        <div class="flex justify-between items-center mb-4 mx-4">
            <form method="GET" action="{{ route('products.index') }}" class="mb-4 flex items-center space-x-2">
                <input type="text" name="search" placeholder="Cari barang" value="{{ request('search') }}" class="input h-10" />
                
                <select name="filter" class="input h-10">
                    <option value="">
                        <img src="{{ asset('images/Package.png') }}" class="w-4 h-4 mr-2">
                        Semua
                    </option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('filter') == $category->id ? 'selected' : '' }}>{{ $category->nama_kategori }}</option>
                    @endforeach
                </select>

                <button type="submit" class="btn h-10">Search</button>
                {{-- <a href="{{ route('products.index') }}" class="btn h-10">Clear</a> --}}
            </form>
        
            <div class="flex items-center">
                <a href="{{ route('products.export', ['search' => $search, 'filter' => $filter]) }}" class="bg-green-500 hover:bg-green-700 text-white py-2 px-4 rounded flex items-center">
                    <img src="{{ asset('images/MicrosoftExcelLogo.png') }}" class="w-4 h-4 mr-2">
                    Export Excel
                </a>
                <a href="{{ route('products.create') }}" class="ml-4 bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded flex items-center">
                    <img src="{{ asset('images/PlusCircle.png') }}" class="w-4 h-4 mr-2">
                    Tambah Produk
                </a>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg mt-4 mx-4">
            <table id="productTable"  class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2 px-4">No</th>
                        <th class="text-left py-2 px-4">Image</th>
                        <th class="text-left py-2 px-4">Nama Produk</th>
                        <th class="text-left py-2 px-4">Kategori Produk</th>
                        <th class="text-left py-2 px-4">Harga Beli (Rp)</th>
                        <th class="text-left py-2 px-4">Harga Jual (Rp)</th>
                        <th class="text-left py-2 px-4">Stok Produk</th>
                        <th class="text-left py-2 px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $key => $product)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="py-2 px-4">{{ $products->firstItem() + $key }}</td>
                            <td class="py-2 px-4"><img src="{{ asset('storage/images/gambar-produk/' . $product->gambar) }}" alt="Gambar {{ $product->nama_produk }}" class="w-10 h-10"></td>
                            <td class="py-2 px-4">{{ $product->nama_produk }}</td>
                            <td class="py-2 px-4">{{ $product->category->nama_kategori }}</td>
                            <td class="py-2 px-4">{{ formatNumber($product->harga_beli) }}</td>
                            <td class="py-2 px-4">{{ formatNumber($product->harga_jual) }}</td>
                            <td class="py-2 px-4">{{ $product->stok }}</td>
                            <td class="py-2 px-4">
                                <a href="{{ route('products.edit', $product->id) }}" class="text-yellow-500 mr-2 flex items-center">
                                    <img src="{{ asset('images/edit.png') }}" alt="Edit" class="w-4 h-4 mr-2">
                                </a>
                            <td class="py-2 px-4">
                                {{-- <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 flex items-center">
                                        <img src="{{ asset('images/delete.png') }}" alt="Delete" class="w-4 h-4 mr-1">
                                    </button>
                                </form> --}}
                                <button onclick="openModal('{{ $product->id }}', '{{ $product->nama_produk }}')" class="text-red-500 hover:text-red-700 flex items-center">
                                    <img src="{{ asset('images/delete.png') }}" alt="Delete" class="w-4 h-4 mr-1">
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($products->count())
            <nav class="my-4 mx-4">
                {{ $products->links() }}
            </nav>
        @endif
    </div>

    <!-- Modal -->
    <div id="confirmationModal" class="hidden fixed z-50 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <div class="bg-white rounded-lg p-4 max-w-md w-full z-50">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Konfirmasi Hapus Produk</h2>
                    <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <p id="modalText" class="text-sm mb-3">
                    Apakah Anda yakin ingin menghapus produk 
                    <strong id="productName"></strong>?
                </p>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">Hapus</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(id, namaProduk) {
            var modal = document.getElementById('confirmationModal');
            var text = document.getElementById('modalText');
            var form = document.getElementById('deleteForm');
            productName.textContent = namaProduk;
            form.action = "{{ route('products.destroy', ':id') }}".replace(':id', id);
            modal.classList.remove('hidden');
        }

        function closeModal() {
            var modal = document.getElementById('confirmationModal');
            modal.classList.add('hidden');
        }
    </script>

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


