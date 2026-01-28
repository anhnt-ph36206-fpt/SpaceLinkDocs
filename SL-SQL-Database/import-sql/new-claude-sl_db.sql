-- =====================================================
-- SPACELINK E-COMMERCE DATABASE - LARAVEL 12
-- Chỉ các chức năng YÊU CẦU BẮT BUỘC
-- Version: 2.0.0 | Date: 2026-01-15
-- Total Tables: 26 bảng
-- =====================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
START TRANSACTION;

-- Tạo database
DROP DATABASE IF EXISTS spacelink_db;
CREATE DATABASE spacelink_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE spacelink_db;

-- =====================================================
-- PHẦN 1: USERS & AUTHENTICATION (7 bảng)
-- =====================================================

-- 1. ROLES - Vai trò
CREATE TABLE roles (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE NOT NULL COMMENT 'admin, staff, customer',
    display_name VARCHAR(100) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB COMMENT='Vai trò người dùng';

-- 2. PERMISSIONS - Quyền hạn
CREATE TABLE permissions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) UNIQUE NOT NULL COMMENT 'products.view, orders.edit,...',
    display_name VARCHAR(100) NOT NULL,
    group_name VARCHAR(50) NOT NULL COMMENT 'products, orders, users,...',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB COMMENT='Quyền hạn hệ thống';

-- 3. ROLE_PERMISSIONS - Phân quyền theo vai trò
CREATE TABLE role_permissions (
    role_id BIGINT UNSIGNED NOT NULL,
    permission_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Mapping vai trò - quyền hạn';

-- 4. USERS - Người dùng
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    role_id BIGINT UNSIGNED NOT NULL DEFAULT 3 COMMENT 'Mặc định: 3-Customer',
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(150) NOT NULL,
    phone VARCHAR(15) NULL,
    avatar VARCHAR(255) NULL,
    date_of_birth DATE NULL,
    gender ENUM('male', 'female', 'other') NULL,
    email_verified_at TIMESTAMP NULL,
    status ENUM('active', 'inactive', 'banned') DEFAULT 'active' COMMENT 'Trạng thái tài khoản',
    remember_token VARCHAR(100) NULL,
    last_login_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL COMMENT 'Soft delete',
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT,
    INDEX idx_role (role_id),
    INDEX idx_email (email),
    INDEX idx_status (status),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB COMMENT='Người dùng hệ thống';


-- 5. USER_ADDRESSES - Địa chỉ giao hàng (YÊU CẦU BẮT BUỘC - Chức năng #13)
CREATE TABLE user_addresses (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    fullname VARCHAR(150) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    province VARCHAR(100) NOT NULL,
    district VARCHAR(100) NOT NULL,
    ward VARCHAR(100) NOT NULL,
    address_detail TEXT NOT NULL,
    is_default BOOLEAN DEFAULT FALSE COMMENT 'Địa chỉ mặc định',
    address_type ENUM('home', 'office', 'other') DEFAULT 'home',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_default (is_default)
) ENGINE=InnoDB COMMENT='Địa chỉ giao hàng của user';

-- 6. PASSWORD_RESET_TOKENS - Reset mật khẩu (Chuẩn Laravel)
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
) ENGINE=InnoDB COMMENT='Token reset mật khẩu';

-- =====================================================
-- PHẦN 2: PRODUCTS (9 bảng)
-- =====================================================

-- 7. BRANDS - Thương hiệu
CREATE TABLE brands (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    logo VARCHAR(255) NULL,
    description TEXT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_active (is_active)
) ENGINE=InnoDB COMMENT='Thương hiệu sản phẩm';

-- 8. CATEGORIES - Danh mục (Hỗ trợ phân cấp)
CREATE TABLE categories (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    parent_id BIGINT UNSIGNED NULL COMMENT 'Danh mục cha',
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    image VARCHAR(255) NULL,
    icon VARCHAR(100) NULL,
    description TEXT NULL,
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL COMMENT 'Soft delete',
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_parent (parent_id),
    INDEX idx_slug (slug),
    INDEX idx_active (is_active)
) ENGINE=InnoDB COMMENT='Danh mục sản phẩm';

-- 9. ATTRIBUTE_GROUPS - Nhóm thuộc tính (Màu sắc, RAM, Dung lượng,...)
CREATE TABLE attribute_groups (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL COMMENT 'color, ram, storage,...',
    display_name VARCHAR(100) NOT NULL COMMENT 'Màu sắc, RAM, Dung lượng,...',
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB COMMENT='Nhóm thuộc tính sản phẩm';

-- 10. ATTRIBUTES - Giá trị thuộc tính
CREATE TABLE attributes (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    attribute_group_id BIGINT UNSIGNED NOT NULL,
    value VARCHAR(100) NOT NULL COMMENT 'Đen, Trắng, 8GB, 256GB,...',
    color_code VARCHAR(7) NULL COMMENT '#000000, #FFFFFF,...',
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (attribute_group_id) REFERENCES attribute_groups(id) ON DELETE CASCADE,
    INDEX idx_group (attribute_group_id)
) ENGINE=InnoDB COMMENT='Giá trị thuộc tính';

-- 11. PRODUCTS - Sản phẩm
CREATE TABLE products (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    category_id BIGINT UNSIGNED NOT NULL,
    brand_id BIGINT UNSIGNED NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    sku VARCHAR(100) UNIQUE NULL COMMENT 'Mã sản phẩm',
    description TEXT NULL COMMENT 'Mô tả ngắn',
    content LONGTEXT NULL COMMENT 'Mô tả chi tiết',
    price DECIMAL(15,2) NOT NULL COMMENT 'Giá gốc',
    sale_price DECIMAL(15,2) NULL COMMENT 'Giá khuyến mãi',
    quantity INT UNSIGNED DEFAULT 0 COMMENT 'Tổng tồn kho',
    sold_count INT UNSIGNED DEFAULT 0 COMMENT 'Đã bán',
    view_count INT UNSIGNED DEFAULT 0 COMMENT 'Lượt xem',
    is_featured BOOLEAN DEFAULT FALSE COMMENT 'Sản phẩm nổi bật',
    is_active BOOLEAN DEFAULT TRUE,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL COMMENT 'Soft delete',
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE SET NULL,
    INDEX idx_category (category_id),
    INDEX idx_brand (brand_id),
    INDEX idx_price (price),
    INDEX idx_sold (sold_count),
    INDEX idx_view (view_count),
    INDEX idx_featured (is_featured),
    INDEX idx_active (is_active),
    FULLTEXT INDEX ft_search (name, description)
) ENGINE=InnoDB COMMENT='Sản phẩm';

-- 12. PRODUCT_IMAGES - Hình ảnh sản phẩm
CREATE TABLE product_images (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    product_id BIGINT UNSIGNED NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE COMMENT 'Ảnh chính',
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product (product_id),
    INDEX idx_primary (is_primary)
) ENGINE=InnoDB COMMENT='Hình ảnh sản phẩm';

-- 13. PRODUCT_VARIANTS - Biến thể sản phẩm
CREATE TABLE product_variants (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    product_id BIGINT UNSIGNED NOT NULL,
    sku VARCHAR(100) UNIQUE NULL,
    price DECIMAL(15,2) NOT NULL,
    sale_price DECIMAL(15,2) NULL,
    quantity INT UNSIGNED DEFAULT 0 COMMENT 'Tồn kho biến thể',
    image VARCHAR(255) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product (product_id),
    INDEX idx_active (is_active)
) ENGINE=InnoDB COMMENT='Biến thể sản phẩm';

-- 14. PRODUCT_VARIANT_ATTRIBUTES - Liên kết biến thể-thuộc tính
CREATE TABLE product_variant_attributes (
    variant_id BIGINT UNSIGNED NOT NULL,
    attribute_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (variant_id, attribute_id),
    FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE CASCADE,
    FOREIGN KEY (attribute_id) REFERENCES attributes(id) ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Mapping biến thể - thuộc tính';

-- 15. PRODUCT_VIEWS - Lượt xem chi tiết (Tracking)
CREATE TABLE product_views (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    product_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NULL,
    session_id VARCHAR(255) NULL,
    ip_address VARCHAR(45) NULL,
    viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_product (product_id),
    INDEX idx_date (viewed_at)
) ENGINE=InnoDB COMMENT='Lịch sử xem sản phẩm';

-- =====================================================
-- PHẦN 3: ORDERS (6 bảng)
-- =====================================================

-- 16. CART - Giỏ hàng
CREATE TABLE cart (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NULL,
    session_id VARCHAR(255) NULL COMMENT 'Cho guest user',
    product_id BIGINT UNSIGNED NOT NULL,
    variant_id BIGINT UNSIGNED NULL,
    quantity INT UNSIGNED NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE SET NULL,
    UNIQUE KEY unique_cart_item (user_id, product_id, variant_id),
    INDEX idx_session (session_id)
) ENGINE=InnoDB COMMENT='Giỏ hàng';

-- 17. ORDERS - Đơn hàng
CREATE TABLE orders (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NULL,
    order_code VARCHAR(50) UNIQUE NOT NULL COMMENT 'Mã đơn hàng',
    
    -- Thông tin giao hàng
    shipping_name VARCHAR(150) NOT NULL,
    shipping_phone VARCHAR(15) NOT NULL,
    shipping_email VARCHAR(255) NULL,
    shipping_province VARCHAR(100) NOT NULL,
    shipping_district VARCHAR(100) NOT NULL,
    shipping_ward VARCHAR(100) NOT NULL,
    shipping_address TEXT NOT NULL,
    
    -- Thông tin tiền
    subtotal DECIMAL(15,2) NOT NULL COMMENT 'Tổng tiền hàng',
    discount_amount DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Tiền giảm giá',
    shipping_fee DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Phí vận chuyển',
    total_amount DECIMAL(15,2) NOT NULL COMMENT 'Tổng thanh toán',
    
    -- Trạng thái
    status ENUM('pending', 'confirmed', 'processing', 'shipping', 'delivered', 'completed', 'cancelled', 'returned') 
        DEFAULT 'pending' COMMENT 'Trạng thái đơn hàng',
    payment_status ENUM('unpaid', 'paid', 'refunded', 'partial_refund') 
        DEFAULT 'unpaid' COMMENT 'Trạng thái thanh toán',
    payment_method ENUM('cod', 'vnpay', 'momo', 'bank_transfer') NOT NULL,
    
    -- Voucher
    voucher_id BIGINT UNSIGNED NULL,
    voucher_code VARCHAR(50) NULL,
    voucher_discount DECIMAL(15,2) DEFAULT 0.00,
    
    -- Ghi chú
    note TEXT NULL COMMENT 'Ghi chú của khách',
    admin_note TEXT NULL COMMENT 'Ghi chú của admin',
    
    -- Hủy đơn
    cancelled_reason TEXT NULL,
    cancelled_by BIGINT UNSIGNED NULL,
    cancelled_at TIMESTAMP NULL,
    
    -- Timestamps
    confirmed_at TIMESTAMP NULL,
    shipped_at TIMESTAMP NULL,
    delivered_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    INDEX idx_payment (payment_status),
    INDEX idx_created (created_at),
    INDEX idx_code (order_code)
) ENGINE=InnoDB COMMENT='Đơn hàng';

-- 18. ORDER_ITEMS - Chi tiết đơn hàng
CREATE TABLE order_items (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    variant_id BIGINT UNSIGNED NULL,
    
    -- Sao lưu thông tin sản phẩm tại thời điểm đặt hàng
    product_name VARCHAR(255) NOT NULL,
    product_image VARCHAR(255) NULL,
    product_sku VARCHAR(100) NULL,
    variant_info JSON NULL COMMENT 'Thông tin biến thể: {color: "Đen", ram: "8GB"}',
    
    price DECIMAL(15,2) NOT NULL COMMENT 'Giá tại thời điểm mua',
    quantity INT UNSIGNED NOT NULL,
    total DECIMAL(15,2) NOT NULL COMMENT 'Thành tiền',
    
    is_reviewed BOOLEAN DEFAULT FALSE COMMENT 'Đã đánh giá chưa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE SET NULL,
    INDEX idx_order (order_id),
    INDEX idx_reviewed (is_reviewed)
) ENGINE=InnoDB COMMENT='Chi tiết đơn hàng';

-- 19. ORDER_STATUS_HISTORY - Lịch sử trạng thái đơn hàng
CREATE TABLE order_status_history (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    order_id BIGINT UNSIGNED NOT NULL,
    from_status VARCHAR(50) NULL,
    to_status VARCHAR(50) NOT NULL,
    note TEXT NULL,
    changed_by BIGINT UNSIGNED NULL COMMENT 'User thay đổi',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_order (order_id)
) ENGINE=InnoDB COMMENT='Lịch sử thay đổi trạng thái đơn hàng';

-- 20. PAYMENT_TRANSACTIONS - Giao dịch thanh toán
CREATE TABLE payment_transactions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    order_id BIGINT UNSIGNED NOT NULL,
    transaction_id VARCHAR(255) UNIQUE NULL COMMENT 'Mã giao dịch từ cổng thanh toán',
    payment_method VARCHAR(50) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    status ENUM('pending', 'processing', 'success', 'failed', 'refunded', 'cancelled') DEFAULT 'pending',
    bank_code VARCHAR(50) NULL,
    response_code VARCHAR(50) NULL,
    response_message TEXT NULL,
    response_data JSON NULL COMMENT 'Dữ liệu phản hồi từ cổng thanh toán',
    paid_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    INDEX idx_transaction (transaction_id),
    INDEX idx_status (status)
) ENGINE=InnoDB COMMENT='Giao dịch thanh toán';

-- 21. VOUCHERS - Mã giảm giá
CREATE TABLE vouchers (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    discount_type ENUM('percent', 'fixed') NOT NULL COMMENT 'Giảm % hoặc cố định',
    discount_value DECIMAL(15,2) NOT NULL,
    max_discount DECIMAL(15,2) NULL COMMENT 'Giảm tối đa (cho loại %)',
    min_order_amount DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Giá trị đơn hàng tối thiểu',
    quantity INT UNSIGNED NULL COMMENT 'Số lượng voucher (NULL = không giới hạn)',
    used_count INT UNSIGNED DEFAULT 0 COMMENT 'Đã sử dụng',
    usage_limit_per_user INT UNSIGNED DEFAULT 1 COMMENT 'Giới hạn/user',
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_code (code),
    INDEX idx_dates (start_date, end_date),
    INDEX idx_active (is_active)
) ENGINE=InnoDB COMMENT='Mã giảm giá';

-- =====================================================
-- PHẦN 4: REVIEWS & COMMENTS (3 bảng)
-- =====================================================

-- 22. REVIEWS - Đánh giá sản phẩm
CREATE TABLE reviews (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    order_item_id BIGINT UNSIGNED NOT NULL COMMENT 'Chỉ đánh giá khi đã mua',
    rating TINYINT UNSIGNED NOT NULL COMMENT '1-5 sao',
    content TEXT NULL,
    images JSON NULL COMMENT 'Ảnh đánh giá',
    is_hidden BOOLEAN DEFAULT FALSE COMMENT 'Ẩn đánh giá',
    admin_reply TEXT NULL COMMENT 'Phản hồi từ admin',
    replied_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_review (order_item_id) COMMENT 'Mỗi order_item chỉ đánh giá 1 lần',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (order_item_id) REFERENCES order_items(id) ON DELETE CASCADE,
    INDEX idx_product (product_id),
    INDEX idx_rating (rating),
    INDEX idx_hidden (is_hidden)
) ENGINE=InnoDB COMMENT='Đánh giá sản phẩm';

-- 23. COMMENTS - Bình luận sản phẩm (Hỗ trợ phân cấp)
CREATE TABLE comments (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    parent_id BIGINT UNSIGNED NULL COMMENT 'Bình luận cha (reply comment)',
    content TEXT NOT NULL,
    is_hidden BOOLEAN DEFAULT FALSE COMMENT 'Ẩn bình luận',
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'approved',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE,
    INDEX idx_product (product_id),
    INDEX idx_parent (parent_id),
    INDEX idx_status (status),
    INDEX idx_hidden (is_hidden)
) ENGINE=InnoDB COMMENT='Bình luận sản phẩm';

-- 24. COMMENT_REPORTS - Báo cáo bình luận
CREATE TABLE comment_reports (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    comment_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL COMMENT 'Người báo cáo',
    reason VARCHAR(255) NOT NULL COMMENT 'Lý do: spam, offensive,...',
    description TEXT NULL,
    status ENUM('pending', 'resolved', 'rejected') DEFAULT 'pending',
    resolved_by BIGINT UNSIGNED NULL COMMENT 'Admin xử lý',
    resolved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (comment_id) REFERENCES comments(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (resolved_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_comment (comment_id),
    INDEX idx_status (status)
) ENGINE=InnoDB COMMENT='Báo cáo bình luận';

-- =====================================================
-- PHẦN 5: CONTENT (3 bảng)
-- =====================================================

-- 25. NEWS - Tin tức
CREATE TABLE news (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    author_id BIGINT UNSIGNED NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    thumbnail VARCHAR(255) NULL,
    summary TEXT NULL COMMENT 'Tóm tắt',
    content LONGTEXT NOT NULL,
    view_count INT UNSIGNED DEFAULT 0,
    is_featured BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_published (published_at),
    INDEX idx_active (is_active)
) ENGINE=InnoDB COMMENT='Tin tức';

-- 26. CONTACTS - Liên hệ
CREATE TABLE contacts (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(15) NULL,
    subject VARCHAR(255) NULL,
    message TEXT NOT NULL,
    status ENUM('unread', 'read', 'replied', 'spam') DEFAULT 'unread',
    reply_content TEXT NULL,
    replied_by BIGINT UNSIGNED NULL,
    replied_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (replied_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status)
) ENGINE=InnoDB COMMENT='Liên hệ từ khách hàng';

-- =====================================================
-- PHẦN 6: SYSTEM (1 bảng)
-- =====================================================

-- 27. SETTINGS - Cấu hình hệ thống
CREATE TABLE settings (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    key_name VARCHAR(100) UNIQUE NOT NULL,
    value TEXT NULL,
    type ENUM('string', 'number', 'boolean', 'json', 'html') DEFAULT 'string',
    group_name VARCHAR(50) DEFAULT 'general' COMMENT 'general, payment, shipping,...',
    description VARCHAR(255) NULL,
    is_public BOOLEAN DEFAULT FALSE COMMENT 'Hiển thị cho client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_group (group_name),
    INDEX idx_public (is_public)
) ENGINE=InnoDB COMMENT='Cấu hình hệ thống';

-- =====================================================
-- DATA MẪU
-- =====================================================

-- Roles
INSERT INTO roles (id, name, display_name, description) VALUES
(1, 'admin', 'Quản trị viên', 'Có toàn quyền quản lý hệ thống'),
(2, 'staff', 'Nhân viên', 'Quản lý đơn hàng và sản phẩm'),
(3, 'customer', 'Khách hàng', 'Người dùng mua hàng');

-- Permissions
INSERT INTO permissions (id, name, display_name, group_name) VALUES
(1, 'dashboard.view', 'Xem Dashboard', 'dashboard'),
(2, 'products.view', 'Xem sản phẩm', 'products'),
(3, 'products.create', 'Thêm sản phẩm', 'products'),
(4, 'products.edit', 'Sửa sản phẩm', 'products'),
(5, 'products.delete', 'Xóa sản phẩm', 'products'),
(6, 'orders.view', 'Xem đơn hàng', 'orders'),
(7, 'orders.edit', 'Sửa đơn hàng', 'orders'),
(8, 'users.view', 'Xem người dùng', 'users'),
(9, 'users.edit', 'Sửa người dùng', 'users'),
(10, 'categories.manage', 'Quản lý danh mục', 'categories'),
(11, 'vouchers.manage', 'Quản lý voucher', 'vouchers'),
(12, 'comments.manage', 'Quản lý bình luận', 'comments'),
(13, 'news.manage', 'Quản lý tin tức', 'news'),
(14, 'settings.manage', 'Quản lý cấu hình', 'settings');

-- Role Permissions (Admin có tất cả quyền)
INSERT INTO role_permissions (role_id, permission_id)
SELECT 1, id FROM permissions;

-- Role Permissions (Staff có quyền hạn chế)
INSERT INTO role_permissions (role_id, permission_id) VALUES
(2, 1), (2, 2), (2, 3), (2, 4), (2, 6), (2, 7), (2, 12);

-- Attribute Groups
INSERT INTO attribute_groups (id, name, display_name, display_order) VALUES
(1, 'color', 'Màu sắc', 1),
(2, 'storage', 'Dung lượng', 2),
(3, 'ram', 'RAM', 3);

-- Attributes
INSERT INTO attributes (attribute_group_id, value, color_code, display_order) VALUES
(1, 'Đen', '#000000', 1),
(1, 'Trắng', '#FFFFFF', 2),
(1, 'Xanh Dương', '#0066CC', 3),
(1, 'Hồng', '#FF69B4', 4),
(1, 'Vàng', '#FFD700', 5),
(2, '64GB', NULL, 1),
(2, '128GB', NULL, 2),
(2, '256GB', NULL, 3),
(2, '512GB', NULL, 4),
(2, '1TB', NULL, 5),
(3, '4GB', NULL, 1),
(3, '6GB', NULL, 2),
(3, '8GB', NULL, 3),
(3, '12GB', NULL, 4),
(3, '16GB', NULL, 5);

-- Brands
INSERT INTO brands (name, slug, is_active, display_order) VALUES
('Apple', 'apple', TRUE, 1),
('Samsung', 'samsung', TRUE, 2),
('Xiaomi', 'xiaomi', TRUE, 3),
('OPPO', 'oppo', TRUE, 4),
('Vivo', 'vivo', TRUE, 5);

-- Categories
INSERT INTO categories (id, parent_id, name, slug, is_active, display_order) VALUES
(1, NULL, 'Điện thoại', 'dien-thoai', TRUE, 1),
(2, NULL, 'Máy tính bảng', 'may-tinh-bang', TRUE, 2),
(3, NULL, 'Laptop', 'laptop', TRUE, 3),
(4, NULL, 'Phụ kiện', 'phu-kien', TRUE, 4),
(5, 1, 'iPhone', 'iphone', TRUE, 1),
(6, 1, 'Samsung Galaxy', 'samsung-galaxy', TRUE, 2),
(7, 2, 'iPad', 'ipad', TRUE, 1),
(8, 3, 'MacBook', 'macbook', TRUE, 1),
(9, 4, 'Tai nghe', 'tai-nghe', TRUE, 1),
(10, 4, 'Sạc & Cáp', 'sac-cap', TRUE, 2);

-- Settings
INSERT INTO settings (key_name, value, type, group_name, description, is_public) VALUES
('site_name', 'SpaceLink', 'string', 'general', 'Tên website', TRUE),
('site_logo', '/images/logo.png', 'string', 'general', 'Logo website', TRUE),
('site_email', 'contact@spacelink.com', 'string', 'general', 'Email liên hệ', TRUE),
('site_phone', '1900 1234', 'string', 'general', 'Hotline', TRUE),
('site_address', 'Hà Nội, Việt Nam', 'string', 'general', 'Địa chỉ', TRUE),
('shipping_fee', '30000', 'number', 'shipping', 'Phí vận chuyển mặc định', FALSE),
('free_shipping_amount', '500000', 'number', 'shipping', 'Miễn phí ship khi đơn hàng trên', FALSE),
('vnpay_enabled', 'true', 'boolean', 'payment', 'Bật thanh toán VNPAY', FALSE),
('momo_enabled', 'true', 'boolean', 'payment', 'Bật thanh toán MOMO', FALSE);

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

-- =====================================================
-- HOÀN TẤT - 27 BẢNG (YÊU CẦU BẮT BUỘC)
-- =====================================================