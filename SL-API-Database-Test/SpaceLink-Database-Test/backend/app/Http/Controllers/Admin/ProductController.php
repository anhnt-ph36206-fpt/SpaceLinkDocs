<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Danh sách products với filter
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'primaryImage']);

        // Tìm kiếm
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Lọc theo category
        if ($request->filled('category_id')) {
            $query->ofCategory($request->category_id);
        }

        // Lọc theo brand
        if ($request->filled('brand_id')) {
            $query->ofBrand($request->brand_id);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            match ($request->status) {
                'active' => $query->active(),
                'inactive' => $query->where('is_active', false),
                'featured' => $query->featured(),
                'sale' => $query->onSale(),
                'out_of_stock' => $query->where('quantity', 0),
                default => null,
            };
        }

        // Sắp xếp
        $sort = $request->get('sort', 'newest');
        $query->sortBy($sort);

        $products = $query->paginate(10)->withQueryString();

        // Data cho filter
        $categories = Category::active()->ordered()->get();
        $brands = Brand::active()->ordered()->get();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    /**
     * Form tạo mới
     */
    public function create()
    {
        $categories = Category::active()->ordered()->get();
        $brands = Brand::active()->ordered()->get();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    /**
     * Lưu product mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'quantity' => 'required|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Tạo slug
            $validated['slug'] = Str::slug($validated['name']);
            $count = Product::where('slug', $validated['slug'])->count();
            if ($count > 0) {
                $validated['slug'] .= '-' . ($count + 1);
            }

            $validated['is_featured'] = $request->has('is_featured');
            $validated['is_active'] = $request->has('is_active');

            $product = Product::create($validated);

            // Upload images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products/' . $product->id, 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => $index === 0, // Ảnh đầu tiên là primary
                        'display_order' => $index,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Tạo sản phẩm thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Chi tiết product
     */
    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'images']);

        return view('admin.products.show', compact('product'));
    }

    /**
     * Form chỉnh sửa
     */
    public function edit(Product $product)
    {
        $product->load('images');
        $categories = Category::active()->ordered()->get();
        $brands = Brand::active()->ordered()->get();

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    /**
     * Cập nhật product
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'quantity' => 'required|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Cập nhật slug nếu name thay đổi
            if ($product->name !== $validated['name']) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            $validated['is_featured'] = $request->has('is_featured');
            $validated['is_active'] = $request->has('is_active');

            $product->update($validated);

            // Upload images mới (thêm vào, không xóa cũ)
            if ($request->hasFile('images')) {
                $maxOrder = $product->images()->max('display_order') ?? -1;

                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products/' . $product->id, 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => false,
                        'display_order' => $maxOrder + $index + 1,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Cập nhật sản phẩm thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Xóa product (soft delete)
     */
    public function destroy(Product $product)
    {
        $product->delete(); // Soft delete - không xóa images

        return redirect()->route('admin.products.index')
            ->with('success', 'Xóa sản phẩm thành công!');
    }

    /**
     * Xóa image của product
     */
    public function deleteImage(ProductImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return back()->with('success', 'Xóa ảnh thành công!');
    }

    /**
     * Set primary image
     */
    public function setPrimaryImage(ProductImage $image)
    {
        $image->setAsPrimary();

        return back()->with('success', 'Đã đặt làm ảnh chính!');
    }
}
