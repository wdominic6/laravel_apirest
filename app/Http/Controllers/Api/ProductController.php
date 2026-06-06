<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return response()->json($products, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'image_path' => $imagePath ? url('storage/' . $imagePath) : null,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Producto creado con éxito',
            'data' => $product
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json($product, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Solo el dueño puede editar su producto
        if ($product->user_id !== null && $product->user_id !== auth()->id()) {
            return response()->json(['message' => 'No tienes permiso para editar este producto'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image_path'] = url('storage/' . $imagePath);
        }

        $product->update($data);

        return response()->json([
            'message' => 'Producto actualizado con éxito',
            'data' => $product
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Solo el dueño puede eliminar su producto
        if ($product->user_id !== null && $product->user_id !== auth()->id()) {
            return response()->json(['message' => 'No tienes permiso para eliminar este producto'], 403);
        }

        $product->delete();

        return response()->json([
            'message' => 'Producto eliminado con éxito'
        ], 200);
    }
    public function report()
    {
        $products = Product::all();
        return response()->json($products, 200);
    }

    /**
     * Export products as a PDF document.
     */
    public function exportPdf()
    {
        $products = Product::all();
        $pdf = Pdf::loadView('reports.products', compact('products'));
        return $pdf->download('reporte_productos.pdf');
    }
}
