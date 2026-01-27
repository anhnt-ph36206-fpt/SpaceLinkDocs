<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Danh sách categories (dạng cây)
     */
    public function index(Request $request)
    {
        $query = Category::query();

        // Tìm kiếm
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Lọc theo parent
        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Hiển thị dạng flat với pagination
        $categories = $query->with('parent')
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        // Danh mục cha để filter
        $parentCategories = Category::parentOnly()->active()->ordered()->get();

        return view('admin.categories.index', compact('categories', 'parentCategories'));
    }

    /**
     * Form tạo mới
     */
    public function create()
    {
        // Lấy tất cả categories để chọn parent
        $parentCategories = Category::parentOnly()->active()->ordered()->get();

        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * Lưu category mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);

        // Tạo slug
        $validated['slug'] = Str::slug($validated['name']);

        // Kiểm tra slug unique
        $count = Category::where('slug', $validated['slug'])->count();
        if ($count > 0) {
            $validated['slug'] .= '-' . ($count + 1);
        }

        // Upload image
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['parent_id'] = $validated['parent_id'] ?: null;

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Tạo danh mục thành công!');
    }

    /**
     * Chi tiết category
     */
    public function show(Category $category)
    {
        $category->load(['parent', 'children', 'products']);

        return view('admin.categories.show', compact('category'));
    }

    /**
     * Form chỉnh sửa
     */
    public function edit(Category $category)
    {
        // Lấy categories có thể làm parent (không bao gồm chính nó và con của nó)
        $parentCategories = Category::where('id', '!=', $category->id)
            ->whereNotIn('parent_id', [$category->id])
            ->parentOnly()
            ->active()
            ->ordered()
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Cập nhật category
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);

        // Không cho phép chọn chính nó làm parent
        if ($validated['parent_id'] == $category->id) {
            return back()->with('error', 'Không thể chọn chính nó làm danh mục cha!');
        }

        // Cập nhật slug nếu name thay đổi
        if ($category->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Upload image mới
        if ($request->hasFile('image')) {
            if ($category->image) {
                \Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['parent_id'] = $validated['parent_id'] ?: null;

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Cập nhật danh mục thành công!');
    }

    /**
     * Xóa category (soft delete)
     */
    public function destroy(Category $category)
    {
        // Kiểm tra có thể xóa không
        if (!$category->canDelete()) {
            return back()->with('error', 'Không thể xóa! Danh mục này có sản phẩm hoặc danh mục con.');
        }

        // Xóa image
        if ($category->image) {
            \Storage::disk('public')->delete($category->image);
        }

        $category->delete(); // Soft delete

        return redirect()->route('admin.categories.index')
            ->with('success', 'Xóa danh mục thành công!');
    }

    /**
     * Lấy danh mục con (AJAX)
     */
    public function getChildren(Category $category)
    {
        $children = $category->children()->active()->ordered()->get();

        return response()->json($children);
    }
}
