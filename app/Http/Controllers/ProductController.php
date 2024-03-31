<?php

namespace App\Http\Controllers;

use App\Exports\ExportProduk;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get search & filter
        $search = $request->input('search');
        $filter = $request->input('filter');

        // Set default values if search and filter are null
        $search = $search ?? '';
        $filter = $filter ?? '';

        // Query untuk mengambil data produk
        $productsQuery = Product::query();

        // Filter berdasarkan kategori jika filter dipilih
        if ($filter) {
            $productsQuery->where('kategori_id', $filter);
        }

        // Filter berdasarkan pencarian jika ada
        if ($search) {
            $productsQuery->where('nama_produk', 'ilike', '%' . $search . '%');
        }

        // Ambil data produk sesuai dengan query yang telah difilter
        $products = $productsQuery->paginate(10);
        // dd($filter);

        // Load view index dengan data produk dan kategori
        return view('products.index', [
            'products' => $products,
            'categories' => Category::all(),
            'search' => $search,
            'filter' => $filter,
        ]);

        // $products = Product::paginate(10);
        // $categories = Category::all();
        // return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|unique:products|max:255',
            'harga_beli' => 'required',
            'harga_jual' => 'required',
            'stok' => 'required|numeric',
            'category_id' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png|max:100',
        ]);

        // Upload gambar ke penyimpanan
        $gambarPath = $request->file('gambar')->store('public/images/gambar-produk');
        $gambarName = basename($gambarPath);

        // Hapus pemisah ribuan dari harga beli dan harga jual
        $hargaBeli = preg_replace('/[^0-9]/', '', $request->harga_beli);
        $hargaJual = preg_replace('/[^0-9]/', '', $request->harga_jual);

        // Simpan produk beserta path gambar
        $product = new Product([
            'nama_produk' => $request->get('nama_produk'),
            'harga_beli' => $hargaBeli,
            'harga_jual' => $hargaJual,
            'stok' => $request->get('stok'),
            'kategori_id' => $request->get('category_id'),
            'gambar' => $gambarName,
        ]);
        $product->save();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    // public function show(Product $product)
    // {
    //     return view('products.show', compact('product'));
    // }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nama_produk' => 'required|max:255',
            'harga_beli' => 'required',
            'harga_jual' => 'required',
            'stok' => 'required|numeric',
            'category_id' => 'required',
            'gambar' => 'image|mimes:jpeg,png|max:100',
        ]);

        // Jika ada gambar baru diunggah, hapus yang lama
        if ($request->hasFile('gambar')) {
            // Upload gambar baru ke penyimpanan
            $gambarPath = $request->file('gambar')->store('public/images/gambar-produk');
            $gambarName = basename($gambarPath);
            
            // Hapus gambar lama jika ada
            Storage::delete('public/images/gambar-produk/' . $product->gambar);
            
            // Simpan nama gambar baru ke dalam data produk
            $product->gambar = $gambarName;
        }

        // Hapus pemisah ribuan dari harga beli dan harga jual
        $hargaBeli = preg_replace('/[^0-9]/', '', $request->harga_beli);
        $hargaJual = preg_replace('/[^0-9]/', '', $request->harga_jual);

        // Update data produk
        $product->update([
            'nama_produk' => $request->nama_produk,
            'harga_beli' => $hargaBeli,
            'harga_jual' => $hargaJual,
            'stok' => $request->stok,
            'kategori_id' => $request->category_id,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    // Ekspor data ke Excel
    public function export(Request $request)
    {
        // Get search & filter
        $search = $request->input('search');
        $filter = $request->input('filter');

        //dd($filter);

        // Query untuk mengambil data produk
        $productsQuery = Product::query();
        

        // Filter berdasarkan kategori jika filter dipilih
        if ($filter !== null) {
            $productsQuery->where('kategori_id', $filter);
        }

        // Filter berdasarkan pencarian jika ada
        if ($search) {
            $productsQuery->where('nama_produk', 'ilike', '%' . $search . '%');
        }

        // Ambil semua data produk sesuai dengan query yang telah difilter
        $products = $productsQuery->get();
        

        // Inisialisasi objek Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Tulis header kolom
        $sheet->setCellValue('A1', 'Nama Produk');
        $sheet->setCellValue('B1', 'Harga Beli');
        $sheet->setCellValue('C1', 'Harga Jual');
        $sheet->setCellValue('D1', 'Stok');
        $sheet->setCellValue('E1', 'Kategori');

        // Mengatur style header
        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

        // Tulis data produk
        $row = 2;
        foreach ($products as $product) {
            $sheet->setCellValue('A' . $row, $product->nama_produk);
            $sheet->setCellValue('B' . $row, $product->harga_beli);
            $sheet->setCellValue('C' . $row, $product->harga_jual);
            $sheet->setCellValue('D' . $row, $product->stok);
            $sheet->setCellValue('E' . $row, $product->category->nama_kategori);
            $row++;
        }

        // Set lebar kolom
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Simpan file Excel
        $fileName = 'produk_' . date('YmdHis') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('app/public/' . $fileName));

        // Redirect atau berikan link untuk mendownload file Excel
        return redirect()->route('products.index')->with('success', 'Data produk berhasil diekspor. <a href="' . asset('storage/' . $fileName) . '">Download</a>');
    }


}
