## 02 - Auth API chi tiết (đi từ dễ đến khó)

### 0. Chuẩn bị

- Migrations + models bạn đã có trong:
  - `02-DATABASE/08-migrations-core-27.md`
  - `02-DATABASE/09-models-core-27-with-relationships.md`
- Cấu hình:
  - Cài `laravel/sanctum`.
  - Cấu hình middleware, `config/cors.php`, `SANCTUM_STATEFUL_DOMAINS` như docs hiện có.

---

### 1. Bước 1 – API đăng ký (`POST /api/auth/register`)

**Mục tiêu**

- Tạo user mới với các trường cơ bản: `email`, `password`, `fullname`, `phone`.
- Trả về thông tin user + token để FE lưu.

**Các bước**

1. Tạo `AuthController` (hoặc `Auth\AuthController`).
2. Tạo `RegisterRequest` để validate:
   - `email`: required, email, unique:users.
   - `password`: required, min:8, confirmed.
   - `fullname`: required.
   - `phone`: nullable, regex/số.
3. Trong controller:
   - Hash password bằng `Hash::make`.
   - Tạo user qua Eloquent.
   - Tạo token Sanctum: `$user->createToken('auth_token')->plainTextToken`.
4. Response JSON:
   - `user`: thông tin cơ bản.
   - `token`: string.

**Route**

- `POST /api/auth/register` → `AuthController@register`

---

### 2. Bước 2 – API đăng nhập (`POST /api/auth/login`)

**Mục tiêu**

- Kiểm tra email/password, trả về token nếu đúng.

**Các bước**

1. `LoginRequest` validate:
   - `email`: required, email.
   - `password`: required.
2. Trong controller:
   - Tìm user theo email.
   - Check password bằng `Hash::check`.
   - Nếu sai → trả về 401 + message.
   - Nếu đúng:
     - Xóa token cũ (nếu muốn) rồi tạo token mới.
3. Response:
   - `user`
   - `token`

**Route**

- `POST /api/auth/login` → `AuthController@login`

---

### 3. Bước 3 – API lấy thông tin hiện tại (`GET /api/auth/me`)

**Mục tiêu**

- FE kiểm tra trạng thái login, lấy profile user.

**Các bước**

1. Middleware: `auth:sanctum`.
2. Trong controller:
   - Trả về `auth()->user()` (có thể dùng Resource).

**Route**

- `GET /api/auth/me` (trong group `Route::middleware('auth:sanctum')`).

---

### 4. Bước 4 – API đăng xuất (`POST /api/auth/logout`)

**Mục tiêu**

- Xóa token hiện tại (user logout).

**Các bước**

1. Middleware: `auth:sanctum`.
2. Trong controller:
   - `auth()->user()->currentAccessToken()->delete();`
3. Response:
   - 200 + message đơn giản.

**Route**

- `POST /api/auth/logout`

---

### 5. Bước 5 – Quản lý profile & địa chỉ (sau khi auth OK)

**API gợi ý**

- `GET /api/profile` → trả về user + addresses.
- `PUT /api/profile` → update `fullname`, `phone`, `avatar`…
- `GET /api/addresses` → list địa chỉ.
- `POST /api/addresses` → thêm địa chỉ.
- `PUT /api/addresses/{id}` → sửa địa chỉ.
- `DELETE /api/addresses/{id}` → xóa địa chỉ.

**Thứ tự thực hiện đề xuất**

1. Hoàn thành 4 endpoint auth cơ bản (register, login, me, logout).
2. Thêm profile đơn giản (`GET/PUT /profile`).
3. Sau đó mới làm CRUD địa chỉ (dựa trên model `UserAddress`).

