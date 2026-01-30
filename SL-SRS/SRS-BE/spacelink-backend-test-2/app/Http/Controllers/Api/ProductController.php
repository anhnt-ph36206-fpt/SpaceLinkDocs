<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm.
     */
    public function index(): JsonResponse
    {
        $products = Product::all();
        return response()->json([
            'status'  => 'success',
            'message' => 'Lấy danh sách sản phẩm thành công',
            'data'    => $products
        ]);
    }

    /**
     * Tạo sản phẩm mới.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validation với messages tiếng Việt
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',   // bắt buộc + tồn tại
                'name'        => 'required|string|max:255',
                'price'       => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'stock'       => 'nullable|integer|min:0',
                // Nếu có thêm field như sku thì thêm vào đây
            ], [
                // Messages tùy chỉnh bằng tiếng Việt
                'name.required'        => 'Tên sản phẩm là bắt buộc.',
                'name.string'          => 'Tên sản phẩm phải là chuỗi ký tự.',
                'name.max'             => 'Tên sản phẩm không được vượt quá 255 ký tự.',
                'price.required'       => 'Giá sản phẩm là bắt buộc.',
                'price.numeric'        => 'Giá sản phẩm phải là số.',
                'price.min'            => 'Giá sản phẩm không được nhỏ hơn 0.',
                'stock.integer'        => 'Số lượng tồn kho phải là số nguyên.',
                'stock.min'            => 'Số lượng tồn kho không được nhỏ hơn 0.',
                // Thêm messages cho các field khác nếu cần
            ], [
                // Tên attribute thân thiện hơn (tiếng Việt)
                'name'        => 'tên sản phẩm',
                'price'       => 'giá',
                'description' => 'mô tả',
                'stock'       => 'tồn kho',
            ]);

            $product = Product::create($validated);

            return response()->json([
                'status'  => 'success',
                'message' => 'Tạo sản phẩm thành công',
                'data'    => $product
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
                'error'   => $e->getMessage() // Giữ nguyên chi tiết lỗi để debug
            ], 500);
        }
    }

    /**
     * Hiển thị chi tiết sản phẩm.
     */
    public function show($id): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);

            return response()->json([
                'status'  => 'success',
                'message' => 'Lấy thông tin sản phẩm thành công',
                'data'    => $product
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Không tìm thấy sản phẩm'
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
     * Cập nhật sản phẩm.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);

            $validated = $request->validate([
                'name'        => 'sometimes|required|string|max:255',
                'price'       => 'sometimes|required|numeric|min:0',
                'description' => 'nullable|string',
                'stock'       => 'nullable|integer|min:0',
            ], [
                // Messages tiếng Việt tương tự store
                'name.required'        => 'Tên sản phẩm là bắt buộc.',
                'name.string'          => 'Tên sản phẩm phải là chuỗi ký tự.',
                'name.max'             => 'Tên sản phẩm không được vượt quá 255 ký tự.',
                'price.required'       => 'Giá sản phẩm là bắt buộc.',
                'price.numeric'        => 'Giá sản phẩm phải là số.',
                'price.min'            => 'Giá sản phẩm không được nhỏ hơn 0.',
                'stock.integer'        => 'Số lượng tồn kho phải là số nguyên.',
                'stock.min'            => 'Số lượng tồn kho không được nhỏ hơn 0.',
            ], [
                'name'        => 'tên sản phẩm',
                'price'       => 'giá',
                'description' => 'mô tả',
                'stock'       => 'tồn kho',
            ]);

            $product->update($validated);

            return response()->json([
                'status'  => 'success',
                'message' => 'Cập nhật sản phẩm thành công',
                'data'    => $product
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Không tìm thấy sản phẩm'
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

    /**
     * Xóa sản phẩm.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'Xóa sản phẩm thành công'
            ], 200); // hoặc dùng 204 nếu không muốn trả body

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Lỗi máy chủ',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}