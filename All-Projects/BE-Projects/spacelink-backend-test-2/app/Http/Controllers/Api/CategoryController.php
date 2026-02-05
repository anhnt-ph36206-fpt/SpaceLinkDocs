<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Hiển thị danh sách danh mục (chỉ danh mục gốc + con của nó).
     */
    public function index(): JsonResponse
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Lấy danh sách danh mục thành công',
            'data'    => $categories
        ]);
    }

    /**
     * Tạo danh mục mới.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'parent_id'   => 'nullable|exists:categories,id',
                'name'        => 'required|string|max:100|unique:categories,name',
                'icon'        => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'status'      => 'nullable|in:active,inactive',
                'order'       => 'nullable|integer|min:0',
            ], [
                'parent_id.exists'     => 'Danh mục cha không tồn tại.',
                'name.required'        => 'Tên danh mục là bắt buộc.',
                'name.string'          => 'Tên danh mục phải là chuỗi ký tự.',
                'name.max'             => 'Tên danh mục không được vượt quá 100 ký tự.',
                'name.unique'          => 'Tên danh mục đã tồn tại.',
                'icon.string'          => 'Icon phải là chuỗi ký tự.',
                'icon.max'             => 'Icon không được vượt quá 255 ký tự.',
                'description.string'   => 'Mô tả phải là chuỗi ký tự.',
                'status.in'            => 'Trạng thái phải là active hoặc inactive.',
                'order.integer'        => 'Thứ tự phải là số nguyên.',
                'order.min'            => 'Thứ tự không được nhỏ hơn 0.',
            ], [
                'parent_id'   => 'danh mục cha',
                'name'        => 'tên danh mục',
                'icon'        => 'icon',
                'description' => 'mô tả',
                'status'      => 'trạng thái',
                'order'       => 'thứ tự',
            ]);

            // Slug được tự động tạo trong Model (boot method) nên không cần set thủ công ở đây
            // $validated['slug'] = Str::slug($validated['name']);

            $category = Category::create($validated);

            return response()->json([
                'status'  => 'success',
                'message' => 'Tạo danh mục thành công',
                'data'    => $category
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Lỗi máy chủ',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hiển thị chi tiết danh mục (bao gồm danh mục con và cha nếu có).
     */
    public function show($id): JsonResponse
    {
        try {
            $category = Category::with(['children', 'parent'])->findOrFail($id);

            return response()->json([
                'status'  => 'success',
                'message' => 'Lấy thông tin danh mục thành công',
                'data'    => $category
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Không tìm thấy danh mục'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Lỗi máy chủ',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật danh mục.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $category = Category::findOrFail($id);

            $validated = $request->validate([
                'parent_id'   => 'nullable|exists:categories,id',
                'name'        => 'sometimes|required|string|max:100|unique:categories,name,' . $id,
                'icon'        => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'status'      => 'nullable|in:active,inactive',
                'order'       => 'nullable|integer|min:0',
            ], [
                'parent_id.exists'     => 'Danh mục cha không tồn tại.',
                'name.required'        => 'Tên danh mục là bắt buộc.',
                'name.string'          => 'Tên danh mục phải là chuỗi ký tự.',
                'name.max'             => 'Tên danh mục không được vượt quá 100 ký tự.',
                'name.unique'          => 'Tên danh mục đã tồn tại.',
                'icon.string'          => 'Icon phải là chuỗi ký tự.',
                'icon.max'             => 'Icon không được vượt quá 255 ký tự.',
                'description.string'   => 'Mô tả phải là chuỗi ký tự.',
                'status.in'            => 'Trạng thái phải là active hoặc inactive.',
                'order.integer'        => 'Thứ tự phải là số nguyên.',
                'order.min'            => 'Thứ tự không được nhỏ hơn 0.',
            ], [
                'parent_id'   => 'danh mục cha',
                'name'        => 'tên danh mục',
                'icon'        => 'icon',
                'description' => 'mô tả',
                'status'      => 'trạng thái',
                'order'       => 'thứ tự',
            ]);

            // Cập nhật slug nếu tên thay đổi (Cần thiết vì Model chỉ check empty slug)
            if (isset($validated['name'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            $category->update($validated);

            return response()->json([
                'status'  => 'success',
                'message' => 'Cập nhật danh mục thành công',
                'data'    => $category
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Không tìm thấy danh mục'
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Lỗi máy chủ',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
