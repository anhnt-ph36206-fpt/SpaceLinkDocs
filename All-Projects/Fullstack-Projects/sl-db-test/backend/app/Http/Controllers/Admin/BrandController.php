<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Danh sách brands
     */
    public function index(Request $request)
    {
        $query = Brand::query();

        // Tìm kiếm
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $brands = $query->ordered()->paginate(10)->withQueryString();

        return view('admin.brands.index', compact('brands'));
    }

    /**
     * Form tạo mới
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Lưu brand mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);

        // Tạo slug từ name
        $validated['slug'] = Str::slug($validated['name']);

        // Upload logo nếu có
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        Brand::create($validated);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Tạo thương hiệu thành công!');
    }

    /**
     * Chi tiết brand
     */
    public function show(Brand $brand)
    {
        $brand->load('products');
        return view('admin.brands.show', compact('brand'));
    }

    /**
     * Form chỉnh sửa
     */
    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    /**
     * Cập nhật brand
     */
    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);

        // Cập nhật slug nếu name thay đổi
        if ($brand->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Upload logo mới nếu có
        if ($request->hasFile('logo')) {
            // Xóa logo cũ
            if ($brand->logo) {
                \Storage::disk('public')->delete($brand->logo);
            }
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $brand->update($validated);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Cập nhật thương hiệu thành công!');
    }

    /**
     * Xóa brand
     */
    public function destroy(Brand $brand)
    {
        // Kiểm tra có sản phẩm không
        if ($brand->products()->exists()) {
            return back()->with('error', 'Không thể xóa! Thương hiệu này có sản phẩm.');
        }

        // Xóa logo
        if ($brand->logo) {
            \Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')
            ->with('success', 'Xóa thương hiệu thành công!');
    }
}
