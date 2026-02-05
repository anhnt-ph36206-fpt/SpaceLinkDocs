## 10 - Giải thích chi tiết các trường của 27 bảng core

> Ghi chú:
> - Dựa trực tiếp trên file `08-migrations-core-27.md` mà bạn sẽ dùng làm chuẩn.
> - Mỗi bảng có bảng mô tả: **cột**, **kiểu Laravel/SQL gần đúng**, **ý nghĩa/chức năng**.

---

### 1. Bảng `roles`

| Cột              | Kiểu                 | Ý nghĩa |
|------------------|----------------------|--------|
| `id`             | BIGINT, PK, AI       | Khóa chính tự tăng cho mỗi vai trò. |
| `name`           | VARCHAR(50), UNIQUE  | Tên hệ thống của role (vd: `admin`, `staff`, `customer`), dùng trong code. |
| `display_name`   | VARCHAR(100)         | Tên hiển thị cho người dùng/QA (vd: “Quản trị viên”). |
| `description`    | TEXT, NULL           | Mô tả chi tiết vai trò này có quyền gì. |
| `created_at`     | TIMESTAMP            | Thời gian tạo bản ghi. |
| `updated_at`     | TIMESTAMP            | Thời gian cập nhật bản ghi lần cuối. |

---

### 2. Bảng `permissions`

| Cột              | Kiểu                 | Ý nghĩa |
|------------------|----------------------|--------|
| `id`             | BIGINT, PK, AI       | Khóa chính quyền hạn. |
| `name`           | VARCHAR(100), UNIQUE | Tên hệ thống của permission (vd: `manage_products`). |
| `display_name`   | VARCHAR(100)         | Tên hiển thị (vd: “Quản lý sản phẩm”). |
| `group_name`     | VARCHAR(50)          | Nhóm quyền (vd: `products`, `orders`) để group trong UI. |
| `created_at`     | TIMESTAMP            | Thời gian tạo. |
| `updated_at`     | TIMESTAMP            | Thời gian cập nhật. |

---

### 3. Bảng `role_permissions`

| Cột            | Kiểu             | Ý nghĩa |
|----------------|------------------|--------|
| `role_id`      | BIGINT, FK       | Tham chiếu tới `roles.id`, xác định role. |
| `permission_id`| BIGINT, FK       | Tham chiếu tới `permissions.id`, xác định quyền. |

**PRIMARY KEY (`role_id`, `permission_id`)**: đảm bảo mỗi cặp chỉ xuất hiện 1 lần (một role không lặp lại cùng một permission).  

---

### 4. Bảng `brands`

| Cột            | Kiểu                 | Ý nghĩa |
|----------------|----------------------|--------|
| `id`           | BIGINT, PK, AI       | Khóa chính thương hiệu. |
| `name`         | VARCHAR(255)         | Tên thương hiệu (vd: Apple, Samsung). |
| `slug`         | VARCHAR(255), UNIQUE | Chuỗi URL-friendly, dùng làm filter/link. |
| `logo`         | VARCHAR(255), NULL   | Đường dẫn logo thương hiệu. |
| `description`  | TEXT, NULL           | Mô tả thương hiệu. |
| `is_active`    | BOOLEAN, default TRUE| Ẩn/hiện thương hiệu trên site. |
| `display_order`| INT, default 0       | Thứ tự sắp xếp khi hiển thị danh sách brands. |
| `created_at`   | TIMESTAMP            | Thời gian tạo. |
| `updated_at`   | TIMESTAMP            | Thời gian cập nhật. |

---

### 5. Bảng `attribute_groups`

| Cột            | Kiểu           | Ý nghĩa |
|----------------|----------------|--------|
| `id`           | BIGINT, PK, AI | Khóa chính nhóm thuộc tính (vd: Màu sắc, Dung lượng). |
| `name`         | VARCHAR(100)   | Tên hệ thống / ngắn của nhóm thuộc tính. |
| `display_name` | VARCHAR(100)   | Tên hiển thị cho người dùng. |
| `display_order`| INT, default 0 | Thứ tự hiển thị nhóm thuộc tính. |
| `created_at`   | TIMESTAMP      | Thời gian tạo. |
| `updated_at`   | TIMESTAMP      | Thời gian cập nhật. |

---

### 6. Bảng `vouchers`

| Cột                    | Kiểu                        | Ý nghĩa |
|------------------------|-----------------------------|--------|
| `id`                   | BIGINT, PK, AI              | Khóa chính voucher. |
| `code`                 | VARCHAR(50), UNIQUE         | Mã voucher nhập khi thanh toán (vd: `SALE10`). |
| `name`                 | VARCHAR(255)                | Tên mô tả voucher. |
| `description`          | TEXT, NULL                  | Mô tả chi tiết điều kiện/sử dụng. |
| `discount_type`        | ENUM('percent','fixed')     | Loại giảm: % hay số tiền cố định. |
| `discount_value`       | DECIMAL(15,2)               | Giá trị giảm (tùy theo loại). |
| `max_discount`         | DECIMAL(15,2), NULL         | Số tiền giảm tối đa (cho loại %). |
| `min_order_amount`     | DECIMAL(15,2), default 0    | Giá trị đơn tối thiểu để được áp dụng. |
| `quantity`             | INT UNSIGNED, NULL          | Số lần được dùng toàn hệ thống (NULL = không giới hạn). |
| `used_count`           | INT UNSIGNED, default 0     | Số lần đã được sử dụng. |
| `usage_limit_per_user` | INT UNSIGNED, default 1     | Mỗi user được dùng tối đa bao nhiêu lần. |
| `start_date`           | DATETIME                    | Thời gian bắt đầu hiệu lực. |
| `end_date`             | DATETIME                    | Thời gian kết thúc hiệu lực. |
| `is_active`            | BOOLEAN, default TRUE       | Trạng thái bật/tắt voucher. |
| `created_at`           | TIMESTAMP                   | Thời gian tạo. |
| `updated_at`           | TIMESTAMP                   | Thời gian cập nhật. |

---

### 7. Bảng `users`

| Cột              | Kiểu                            | Ý nghĩa |
|------------------|---------------------------------|--------|
| `id`             | BIGINT, PK, AI                  | Khóa chính user. |
| `email`          | VARCHAR(255), UNIQUE            | Email đăng nhập. |
| `password`       | VARCHAR(255)                    | Mật khẩu đã hash. |
| `fullname`       | VARCHAR(150)                    | Họ tên đầy đủ. |
| `phone`          | VARCHAR(15), NULL               | Số điện thoại. |
| `avatar`         | VARCHAR(255), NULL              | Đường dẫn ảnh đại diện. |
| `date_of_birth`  | DATE, NULL                      | Ngày sinh. |
| `gender`         | ENUM('male','female','other'), NULL | Giới tính. |
| `email_verified_at` | TIMESTAMP, NULL              | Thời điểm email được xác thực. |
| `status`         | ENUM('active','inactive','banned'), default 'active' | Trạng thái tài khoản. |
| `wallet_balance` | DECIMAL(15,2), default 0        | Số dư ví nội bộ. |
| `loyalty_points` | INT UNSIGNED, default 0         | Điểm tích lũy. |
| `remember_token` | VARCHAR(100), NULL (hidden)     | Token nhớ đăng nhập (Laravel). |
| `last_login_at`  | TIMESTAMP, NULL                 | Lần đăng nhập gần nhất. |
| `created_at`     | TIMESTAMP                       | Thời gian tạo. |
| `updated_at`     | TIMESTAMP                       | Thời gian cập nhật. |
| `deleted_at`     | TIMESTAMP, NULL                 | Thời gian soft-delete. |

---

### 8. Bảng `password_reset_tokens`

| Cột        | Kiểu           | Ý nghĩa |
|------------|----------------|--------|
| `email`    | VARCHAR(255), PK | Email của user yêu cầu reset. |
| `token`    | VARCHAR(255)   | Token reset mật khẩu gửi qua email. |
| `created_at` | TIMESTAMP, NULL | Thời gian tạo token (để giới hạn thời gian hiệu lực). |

---

### 9. Bảng `categories`

| Cột            | Kiểu                      | Ý nghĩa |
|----------------|---------------------------|--------|
| `id`           | BIGINT, PK, AI            | Khóa chính danh mục. |
| `parent_id`    | BIGINT, FK, NULL          | Tham chiếu category cha (tạo cây danh mục). |
| `name`         | VARCHAR(255)              | Tên danh mục. |
| `slug`         | VARCHAR(255), UNIQUE      | Chuỗi URL-friendly, dùng filter/router. |
| `image`        | VARCHAR(255), NULL        | Ảnh đại diện danh mục. |
| `icon`         | VARCHAR(100), NULL        | Icon (class hoặc tên icon). |
| `description`  | TEXT, NULL                | Mô tả danh mục. |
| `display_order`| INT, default 0            | Thứ tự hiển thị. |
| `is_active`    | BOOLEAN, default TRUE     | Bật/tắt danh mục. |
| `created_at`   | TIMESTAMP                 | Thời gian tạo. |
| `updated_at`   | TIMESTAMP                 | Thời gian cập nhật. |
| `deleted_at`   | TIMESTAMP, NULL           | Soft delete (ẩn thay vì xóa hẳn). |

---

### 10. Bảng `attributes`

| Cột                | Kiểu                 | Ý nghĩa |
|--------------------|----------------------|--------|
| `id`               | BIGINT, PK, AI       | Khóa chính thuộc tính. |
| `attribute_group_id` | BIGINT, FK         | Nhóm thuộc tính cha (vd: Màu sắc). |
| `value`            | VARCHAR(100)         | Giá trị cụ thể (vd: “Đen”, “8GB”). |
| `color_code`       | VARCHAR(7), NULL     | Mã màu HEX nếu là thuộc tính màu (vd: `#000000`). |
| `display_order`    | INT, default 0       | Thứ tự hiển thị giá trị. |
| `created_at`       | TIMESTAMP            | Thời gian tạo. |
| `updated_at`       | TIMESTAMP            | Thời gian cập nhật. |

---

### 11. Bảng `news`

| Cột            | Kiểu                      | Ý nghĩa |
|----------------|---------------------------|--------|
| `id`           | BIGINT, PK, AI            | Khóa chính bài viết tin tức. |
| `category_id`  | BIGINT, NULL              | Id danh mục tin tức (nếu có phân loại). |
| `author_id`    | BIGINT, FK, NULL          | User tác giả bài viết. |
| `title`        | VARCHAR(255)              | Tiêu đề bài viết. |
| `slug`         | VARCHAR(255), UNIQUE      | URL-friendly slug. |
| `thumbnail`    | VARCHAR(255), NULL        | Ảnh thumbnail. |
| `summary`      | TEXT, NULL                | Tóm tắt ngắn. |
| `content`      | LONGTEXT                  | Nội dung chi tiết. |
| `view_count`   | INT UNSIGNED, default 0   | Số lượt xem. |
| `is_featured`  | BOOLEAN, default FALSE    | Có đánh dấu nổi bật không. |
| `is_active`    | BOOLEAN, default TRUE     | Bật/tắt bài viết. |
| `meta_title`   | VARCHAR(255), NULL        | SEO title. |
| `meta_description` | TEXT, NULL            | SEO description. |
| `published_at` | TIMESTAMP, NULL           | Thời gian xuất bản. |
| `created_at`   | TIMESTAMP                 | Thời gian tạo. |
| `updated_at`   | TIMESTAMP                 | Thời gian cập nhật. |
| `deleted_at`   | TIMESTAMP, NULL           | Soft delete. |

---

### 12. Bảng `settings`

| Cột          | Kiểu                           | Ý nghĩa |
|--------------|--------------------------------|--------|
| `id`         | BIGINT, PK, AI                 | Khóa chính cấu hình. |
| `key_name`   | VARCHAR(100), UNIQUE           | Khóa cấu hình (vd: `site_name`, `support_email`). |
| `value`      | TEXT, NULL                     | Giá trị cấu hình (string/json/html...). |
| `type`       | ENUM('string','number','boolean','json','html'), default 'string' | Loại dữ liệu để parse trong code. |
| `group_name` | VARCHAR(50), default 'general' | Nhóm cấu hình (general, payment, mail...). |
| `description`| VARCHAR(255), NULL             | Mô tả cấu hình. |
| `is_public`  | BOOLEAN, default FALSE         | Có cho client (frontend) đọc được không. |
| `created_at` | TIMESTAMP                      | Thời gian tạo. |
| `updated_at` | TIMESTAMP                      | Thời gian cập nhật. |

---

### 13. Bảng `user_addresses`

| Cột            | Kiểu                 | Ý nghĩa |
|----------------|----------------------|--------|
| `id`           | BIGINT, PK, AI       | Khóa chính địa chỉ. |
| `user_id`      | BIGINT, FK           | Tham chiếu user sở hữu địa chỉ. |
| `fullname`     | VARCHAR(150)         | Tên người nhận. |
| `phone`        | VARCHAR(15)          | Số điện thoại người nhận. |
| `province`     | VARCHAR(100)         | Tỉnh/thành. |
| `district`     | VARCHAR(100)         | Quận/huyện. |
| `ward`         | VARCHAR(100)         | Phường/xã. |
| `address_detail` | TEXT               | Địa chỉ chi tiết (số nhà, đường…). |
| `is_default`   | BOOLEAN, default FALSE | Địa chỉ mặc định hay không. |
| `address_type` | ENUM('home','office','other'), default 'home' | Loại địa chỉ. |
| `created_at`   | TIMESTAMP            | Thời gian tạo. |
| `updated_at`   | TIMESTAMP            | Thời gian cập nhật. |

---

### 14. Bảng `products`

| Cột            | Kiểu                        | Ý nghĩa |
|----------------|-----------------------------|--------|
| `id`           | BIGINT, PK, AI              | Khóa chính sản phẩm. |
| `category_id`  | BIGINT, FK                  | Danh mục chính của sản phẩm. |
| `brand_id`     | BIGINT, FK, NULL            | Thương hiệu (nếu có). |
| `name`         | VARCHAR(255)                | Tên sản phẩm. |
| `slug`         | VARCHAR(255), UNIQUE        | Slug thân thiện URL. |
| `sku`          | VARCHAR(100), UNIQUE, NULL  | Mã SKU nội bộ. |
| `description`  | TEXT, NULL                  | Mô tả ngắn. |
| `content`      | LONGTEXT, NULL              | Mô tả chi tiết / thông số dài. |
| `price`        | DECIMAL(15,2)               | Giá gốc. |
| `sale_price`   | DECIMAL(15,2), NULL         | Giá sale (nếu có). |
| `quantity`     | INT UNSIGNED, default 0     | Tồn kho hiện tại. |
| `sold_count`   | INT UNSIGNED, default 0     | Số lượng đã bán. |
| `view_count`   | INT UNSIGNED, default 0     | Số lượt xem. |
| `is_featured`  | BOOLEAN, default FALSE      | Có gắn nhãn nổi bật không. |
| `is_active`    | BOOLEAN, default TRUE       | Bật/tắt sản phẩm. |
| `meta_title`   | VARCHAR(255), NULL          | SEO title. |
| `meta_description` | TEXT, NULL              | SEO description. |
| `created_at`   | TIMESTAMP                   | Thời gian tạo. |
| `updated_at`   | TIMESTAMP                   | Thời gian cập nhật. |
| `deleted_at`   | TIMESTAMP, NULL             | Soft delete. |

---

### 15. Bảng `contacts`

| Cột          | Kiểu                         | Ý nghĩa |
|--------------|------------------------------|--------|
| `id`         | BIGINT, PK, AI               | Khóa chính liên hệ. |
| `name`       | VARCHAR(150)                 | Tên người liên hệ. |
| `email`      | VARCHAR(255)                 | Email người liên hệ. |
| `phone`      | VARCHAR(15), NULL            | Số điện thoại. |
| `subject`    | VARCHAR(255), NULL           | Tiêu đề liên hệ. |
| `message`    | TEXT                         | Nội dung liên hệ. |
| `status`     | ENUM('unread','read','replied','spam'), default 'unread' | Trạng thái xử lý liên hệ. |
| `reply_content` | TEXT, NULL                | Nội dung trả lời. |
| `replied_by` | BIGINT, FK, NULL             | User admin đã trả lời. |
| `replied_at` | TIMESTAMP, NULL              | Thời gian trả lời. |
| `created_at` | TIMESTAMP                    | Thời gian tạo. |
| `updated_at` | TIMESTAMP                    | Thời gian cập nhật. |

---

### 16–19. Bảng `orders`, `order_items`, `order_status_history`, `payment_transactions`

#### Bảng `orders`

| Cột              | Kiểu                                           | Ý nghĩa |
|------------------|------------------------------------------------|--------|
| `id`             | BIGINT, PK, AI                                 | Khóa chính đơn hàng. |
| `user_id`        | BIGINT, FK, NULL                               | User đặt đơn (có thể NULL nếu guest). |
| `order_code`     | VARCHAR(50), UNIQUE                            | Mã đơn hàng hiển thị cho khách. |
| `shipping_name`  | VARCHAR(150)                                   | Tên người nhận. |
| `shipping_phone` | VARCHAR(15)                                    | SĐT người nhận. |
| `shipping_email` | VARCHAR(255), NULL                             | Email người nhận. |
| `shipping_province` | VARCHAR(100)                                | Tỉnh/thành giao hàng. |
| `shipping_district` | VARCHAR(100)                                | Quận/huyện. |
| `shipping_ward`  | VARCHAR(100)                                   | Phường/xã. |
| `shipping_address` | TEXT                                         | Địa chỉ chi tiết. |
| `subtotal`       | DECIMAL(15,2)                                  | Tổng tiền hàng trước giảm giá. |
| `discount_amount`| DECIMAL(15,2), default 0                       | Tổng tiền giảm giá (voucher, điểm…). |
| `shipping_fee`   | DECIMAL(15,2), default 0                       | Phí vận chuyển. |
| `total_amount`   | DECIMAL(15,2)                                  | Tổng thanh toán cuối cùng. |
| `status`         | ENUM('pending','confirmed','processing','shipping','delivered','completed','cancelled','returned'), default 'pending' | Trạng thái xử lý đơn. |
| `payment_status` | ENUM('unpaid','paid','refunded','partial_refund'), default 'unpaid' | Trạng thái thanh toán. |
| `payment_method` | ENUM('cod','vnpay','momo','bank_transfer')     | Phương thức thanh toán. |
| `voucher_id`     | BIGINT, FK, NULL                               | Voucher áp dụng (nếu có). |
| `voucher_code`   | VARCHAR(50), NULL                              | Mã voucher lưu lại thời điểm đó. |
| `voucher_discount` | DECIMAL(15,2), default 0                     | Số tiền giảm riêng từ voucher. |
| `note`           | TEXT, NULL                                     | Ghi chú của khách. |
| `admin_note`     | TEXT, NULL                                     | Ghi chú nội bộ admin. |
| `cancelled_reason` | TEXT, NULL                                   | Lý do hủy (nếu bị hủy). |
| `cancelled_by`   | BIGINT, FK, NULL                               | Ai hủy (user hay admin). |
| `cancelled_at`   | TIMESTAMP, NULL                                | Thời gian hủy. |
| `confirmed_at`   | TIMESTAMP, NULL                                | Thời gian xác nhận đơn. |
| `shipped_at`     | TIMESTAMP, NULL                                | Thời gian bắt đầu giao hàng. |
| `delivered_at`   | TIMESTAMP, NULL                                | Thời gian giao thành công. |
| `completed_at`   | TIMESTAMP, NULL                                | Thời điểm hoàn tất đơn. |
| `created_at`     | TIMESTAMP                                      | Thời gian tạo. |
| `updated_at`     | TIMESTAMP                                      | Thời gian cập nhật. |

#### Bảng `order_items`

| Cột            | Kiểu                         | Ý nghĩa |
|----------------|------------------------------|--------|
| `id`           | BIGINT, PK, AI               | Khóa chính dòng chi tiết. |
| `order_id`     | BIGINT, FK                   | Tham chiếu đơn hàng cha. |
| `product_id`   | BIGINT, FK                   | Sản phẩm được mua. |
| `variant_id`   | BIGINT, FK, NULL             | Biến thể (nếu có). |
| `product_name` | VARCHAR(255)                 | Tên sản phẩm tại thời điểm mua (snapshot). |
| `product_image`| VARCHAR(255), NULL           | Ảnh sản phẩm tại thời điểm mua. |
| `product_sku`  | VARCHAR(100), NULL           | SKU tại thời điểm mua. |
| `variant_info` | JSON, NULL                   | Thông tin biến thể (màu/ram/rom…). |
| `price`        | DECIMAL(15,2)                | Giá 1 đơn vị tại thời điểm mua. |
| `quantity`     | INT UNSIGNED                 | Số lượng mua. |
| `total`        | DECIMAL(15,2)                | Thành tiền dòng này. |
| `is_reviewed`  | BOOLEAN, default FALSE       | Đã được review chưa. |
| `created_at`   | TIMESTAMP                    | Thời gian tạo dòng. |

#### Bảng `order_status_history`

| Cột          | Kiểu                 | Ý nghĩa |
|--------------|----------------------|--------|
| `id`         | BIGINT, PK, AI       | Khóa chính bản ghi trạng thái. |
| `order_id`   | BIGINT, FK           | Đơn hàng liên quan. |
| `from_status`| VARCHAR(50), NULL    | Trạng thái cũ. |
| `to_status`  | VARCHAR(50)          | Trạng thái mới. |
| `note`       | TEXT, NULL           | Ghi chú khi đổi trạng thái. |
| `changed_by` | BIGINT, FK, NULL     | Ai đổi (user/admin). |
| `created_at` | TIMESTAMP            | Thời gian tạo bản ghi. |

#### Bảng `payment_transactions`

| Cột             | Kiểu                                           | Ý nghĩa |
|-----------------|------------------------------------------------|--------|
| `id`            | BIGINT, PK, AI                                 | Khóa chính giao dịch. |
| `order_id`      | BIGINT, FK                                     | Đơn hàng tương ứng. |
| `transaction_id`| VARCHAR(255), UNIQUE, NULL                     | Mã giao dịch từ cổng thanh toán. |
| `payment_method`| VARCHAR(50)                                    | Tên phương thức thanh toán. |
| `amount`        | DECIMAL(15,2)                                  | Số tiền giao dịch. |
| `status`        | ENUM('pending','processing','success','failed','refunded','cancelled'), default 'pending' | Trạng thái giao dịch. |
| `bank_code`     | VARCHAR(50), NULL                              | Mã ngân hàng (nếu có). |
| `response_code` | VARCHAR(50), NULL                              | Mã phản hồi từ cổng. |
| `response_message` | TEXT, NULL                                  | Thông báo chi tiết từ cổng. |
| `response_data` | JSON, NULL                                     | Dữ liệu raw từ cổng. |
| `paid_at`       | TIMESTAMP, NULL                                | Thời gian thanh toán thành công. |
| `created_at`    | TIMESTAMP                                      | Thời gian tạo. |
| `updated_at`    | TIMESTAMP                                      | Thời gian cập nhật. |

---

### 20–21. Bảng `comments` và `comment_reports`

#### Bảng `comments`

| Cột          | Kiểu                                     | Ý nghĩa |
|--------------|------------------------------------------|--------|
| `id`         | BIGINT, PK, AI                           | Khóa chính bình luận. |
| `user_id`    | BIGINT, FK                               | User bình luận. |
| `product_id` | BIGINT, FK                               | Sản phẩm được bình luận. |
| `parent_id`  | BIGINT, FK, NULL                         | Comment cha (để làm thread/reply). |
| `content`    | TEXT                                     | Nội dung bình luận. |
| `is_hidden`  | BOOLEAN, default FALSE                   | Có bị ẩn (moderation) không. |
| `status`     | ENUM('pending','approved','rejected'), default 'pending' | Trạng thái duyệt. |
| `created_at` | TIMESTAMP                                | Thời gian tạo. |
| `updated_at` | TIMESTAMP                                | Thời gian cập nhật. |

#### Bảng `comment_reports`

| Cột          | Kiểu                                     | Ý nghĩa |
|--------------|------------------------------------------|--------|
| `id`         | BIGINT, PK, AI                           | Khóa chính report. |
| `comment_id` | BIGINT, FK                               | Comment bị report. |
| `user_id`    | BIGINT, FK                               | User report comment đó. |
| `reason`     | VARCHAR(255)                             | Lý do ngắn gọn (vd: spam, xúc phạm). |
| `description`| TEXT, NULL                               | Mô tả chi tiết. |
| `status`     | ENUM('pending','resolved','rejected'), default 'pending' | Trạng thái xử lý report. |
| `resolved_by`| BIGINT, FK, NULL                         | Admin xử lý. |
| `resolved_at`| TIMESTAMP, NULL                          | Thời gian xử lý xong. |
| `created_at` | TIMESTAMP                                | Thời gian tạo report. |

---

### 22–25. Bảng `product_images`, `product_variants`, `product_variant_attributes`, `product_views`

#### Bảng `product_images`

| Cột          | Kiểu                 | Ý nghĩa |
|--------------|----------------------|--------|
| `id`         | BIGINT, PK, AI       | Khóa chính ảnh. |
| `product_id` | BIGINT, FK           | Sản phẩm sở hữu ảnh. |
| `image_path` | VARCHAR(255)         | Đường dẫn ảnh. |
| `is_primary` | BOOLEAN, default FALSE | Ảnh chính hay phụ. |
| `display_order` | INT, default 0    | Thứ tự hiển thị. |
| `created_at` | TIMESTAMP            | Thời gian tạo. |
| `updated_at` | TIMESTAMP            | Thời gian cập nhật. |

#### Bảng `product_variants`

| Cột          | Kiểu                        | Ý nghĩa |
|--------------|-----------------------------|--------|
| `id`         | BIGINT, PK, AI              | Khóa chính biến thể. |
| `product_id` | BIGINT, FK                  | Sản phẩm cha. |
| `sku`        | VARCHAR(100), UNIQUE, NULL  | SKU riêng cho biến thể. |
| `price`      | DECIMAL(15,2)               | Giá của biến thể. |
| `sale_price` | DECIMAL(15,2), NULL         | Giá sale của biến thể. |
| `quantity`   | INT UNSIGNED, default 0     | Tồn kho riêng biến thể. |
| `image`      | VARCHAR(255), NULL          | Ảnh riêng cho biến thể. |
| `is_active`  | BOOLEAN, default TRUE       | Bật/tắt biến thể. |
| `created_at` | TIMESTAMP                   | Thời gian tạo. |
| `updated_at` | TIMESTAMP                   | Thời gian cập nhật. |

#### Bảng `product_variant_attributes`

| Cột          | Kiểu           | Ý nghĩa |
|--------------|----------------|--------|
| `variant_id` | BIGINT, FK     | Biến thể. |
| `attribute_id` | BIGINT, FK   | Thuộc tính (màu, RAM…). |

**PRIMARY KEY (`variant_id`,`attribute_id`)**: Mỗi cặp biến thể–thuộc tính chỉ xuất hiện một lần.

#### Bảng `product_views`

| Cột          | Kiểu                 | Ý nghĩa |
|--------------|----------------------|--------|
| `id`         | BIGINT, PK, AI       | Khóa chính lượt xem. |
| `product_id` | BIGINT, FK           | Sản phẩm được xem. |
| `user_id`    | BIGINT, FK, NULL     | User đã đăng nhập (nếu có). |
| `session_id` | VARCHAR(255), NULL   | Session cho khách (guest). |
| `ip_address` | VARCHAR(45), NULL    | Địa chỉ IP. |
| `viewed_at`  | TIMESTAMP            | Thời gian xem. |

---

### 26. Bảng `cart`

| Cột          | Kiểu                         | Ý nghĩa |
|--------------|------------------------------|--------|
| `id`         | BIGINT, PK, AI               | Khóa chính dòng cart. |
| `user_id`    | BIGINT, FK, NULL             | User sở hữu cart (NULL nếu guest dùng session). |
| `session_id` | VARCHAR(255), NULL           | Mã session của khách chưa login. |
| `product_id` | BIGINT, FK                   | Sản phẩm trong giỏ. |
| `variant_id` | BIGINT, FK, NULL             | Biến thể trong giỏ (nếu có). |
| `quantity`   | INT UNSIGNED, default 1      | Số lượng sản phẩm. |
| `created_at` | TIMESTAMP                    | Thời gian tạo. |
| `updated_at` | TIMESTAMP                    | Thời gian cập nhật. |

---

### 27. Bảng `reviews`

| Cột          | Kiểu                         | Ý nghĩa |
|--------------|------------------------------|--------|
| `id`         | BIGINT, PK, AI               | Khóa chính đánh giá. |
| `user_id`    | BIGINT, FK                   | User thực hiện đánh giá. |
| `product_id` | BIGINT, FK                   | Sản phẩm được đánh giá. |
| `order_item_id` | BIGINT, FK                | Dòng đơn hàng liên quan (đảm bảo mua rồi mới đánh giá). |
| `rating`     | TINYINT UNSIGNED             | Số sao (vd: 1–5). |
| `content`    | TEXT, NULL                   | Nội dung đánh giá. |
| `images`     | JSON, NULL                   | Danh sách ảnh đính kèm (mảng đường dẫn). |
| `is_hidden`  | BOOLEAN, default FALSE       | Admin ẩn review hay không. |
| `admin_reply`| TEXT, NULL                   | Phản hồi của admin. |
| `replied_at` | TIMESTAMP, NULL              | Thời gian admin phản hồi. |
| `created_at` | TIMESTAMP                    | Thời gian tạo. |
| `updated_at` | TIMESTAMP                    | Thời gian cập nhật. |

