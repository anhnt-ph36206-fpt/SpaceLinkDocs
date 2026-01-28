const express = require('express');
const cors = require('cors');
const bodyParser = require('body-parser');
const jwt = require('jsonwebtoken');
const { faker } = require('@faker-js/faker');

const app = express();
const PORT = 3333;
const JWT_SECRET = 'spacelink-secret-key-2026';

// Middleware
app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Logging middleware
app.use((req, res, next) => {
    console.log(`[${new Date().toISOString()}] ${req.method} ${req.path}`);
    next();
});

// ============================================
// HELPER FUNCTIONS - TẠO DỮ LIỆU GIẢ
// ============================================

// Tạo token JWT giả
const generateToken = (userId) => {
    return jwt.sign({ userId, role: 'customer' }, JWT_SECRET, { expiresIn: '24h' });
};

// Tạo user giả
const generateUser = (role = 'customer') => {
    return {
        id: faker.number.int({ min: 1, max: 1000 }),
        email: faker.internet.email(),
        fullname: faker.person.fullName(),
        phone: faker.helpers.fromRegExp('09[0-9]{8}'),
        avatar: faker.image.avatar(),
        date_of_birth: faker.date.birthdate({ min: 18, max: 65, mode: 'age' }),
        gender: faker.helpers.arrayElement(['male', 'female', 'other']),
        wallet_balance: faker.finance.amount(0, 5000000, 2),
        loyalty_points: faker.number.int({ min: 0, max: 10000 }),
        status: 'active',
        roles: [role],
        created_at: faker.date.past()
    };
};

// Tạo brand giả
const brands = [
    { id: 1, name: 'Apple', slug: 'apple', logo: '/images/brands/apple.png' },
    { id: 2, name: 'Samsung', slug: 'samsung', logo: '/images/brands/samsung.png' },
    { id: 3, name: 'Xiaomi', slug: 'xiaomi', logo: '/images/brands/xiaomi.png' },
    { id: 4, name: 'OPPO', slug: 'oppo', logo: '/images/brands/oppo.png' },
    { id: 5, name: 'Vivo', slug: 'vivo', logo: '/images/brands/vivo.png' },
];

// Tạo category giả
const categories = [
    { id: 1, name: 'Điện thoại', slug: 'dien-thoai', parent_id: null },
    { id: 2, name: 'Laptop', slug: 'laptop', parent_id: null },
    { id: 3, name: 'Tablet', slug: 'tablet', parent_id: null },
    { id: 4, name: 'Phụ kiện', slug: 'phu-kien', parent_id: null },
    { id: 5, name: 'iPhone', slug: 'iphone', parent_id: 1 },
    { id: 6, name: 'Samsung Galaxy', slug: 'samsung-galaxy', parent_id: 1 },
];

// Tạo product giả
const generateProduct = () => {
    const brand = faker.helpers.arrayElement(brands);
    const category = faker.helpers.arrayElement(categories.filter(c => c.parent_id !== null));
    const price = faker.number.int({ min: 3000000, max: 50000000 });
    const salePrice = price * faker.helpers.arrayElement([0.9, 0.85, 0.8, 1]);

    return {
        id: faker.number.int({ min: 1, max: 1000 }),
        name: `${brand.name} ${faker.commerce.productName()}`,
        slug: faker.helpers.slugify(`${brand.name} ${faker.commerce.productName()}`).toLowerCase(),
        sku: faker.string.alphanumeric({ length: 10, casing: 'upper' }),
        description: faker.commerce.productDescription(),
        content: faker.lorem.paragraphs(3),
        price: price.toFixed(2),
        sale_price: salePrice < price ? salePrice.toFixed(2) : null,
        quantity: faker.number.int({ min: 0, max: 100 }),
        sold_count: faker.number.int({ min: 0, max: 500 }),
        view_count: faker.number.int({ min: 0, max: 2000 }),
        is_featured: faker.datatype.boolean(0.3),
        is_active: true,
        category: {
            id: category.id,
            name: category.name,
            slug: category.slug
        },
        brand: {
            id: brand.id,
            name: brand.name,
            logo: brand.logo
        },
        primary_image: faker.image.url({ width: 800, height: 800 }),
        rating: parseFloat(faker.number.float({ min: 3.5, max: 5, precision: 0.1 }).toFixed(1)),
        review_count: faker.number.int({ min: 0, max: 100 }),
        in_wishlist: faker.datatype.boolean(0.2),
        created_at: faker.date.past(),
        updated_at: faker.date.recent()
    };
};

// Tạo order giả
const generateOrder = (userId = null) => {
    const statuses = ['pending', 'confirmed', 'processing', 'shipping', 'delivered', 'completed', 'cancelled'];
    const status = faker.helpers.arrayElement(statuses);
    const paymentMethods = ['cod', 'vnpay', 'momo'];
    const paymentStatus = status === 'completed' ? 'paid' : (status === 'cancelled' ? 'refunded' : 'unpaid');

    const items = Array.from({ length: faker.number.int({ min: 1, max: 3 }) }, () => {
        const product = generateProduct();
        const quantity = faker.number.int({ min: 1, max: 3 });
        const price = parseFloat(product.sale_price || product.price);

        return {
            id: faker.number.int({ min: 1, max: 1000 }),
            product: {
                id: product.id,
                name: product.name,
                slug: product.slug,
                image: product.primary_image
            },
            variant_info: {
                color: faker.helpers.arrayElement(['Đen', 'Trắng', 'Xanh', 'Hồng']),
                storage: faker.helpers.arrayElement(['128GB', '256GB', '512GB'])
            },
            quantity,
            price: price.toFixed(2),
            total: (price * quantity).toFixed(2),
            is_reviewed: faker.datatype.boolean(0.4)
        };
    });

    const subtotal = items.reduce((sum, item) => sum + parseFloat(item.total), 0);
    const discount = subtotal * faker.helpers.arrayElement([0, 0.05, 0.1]);
    const shippingFee = 30000;
    const total = subtotal - discount + shippingFee;

    return {
        id: faker.number.int({ min: 1, max: 10000 }),
        user_id: userId,
        order_code: `SPL-${faker.date.recent().getFullYear()}${String(faker.number.int({ min: 1, max: 12 })).padStart(2, '0')}${String(faker.number.int({ min: 1, max: 31 })).padStart(2, '0')}-${String(faker.number.int({ min: 1, max: 999 })).padStart(3, '0')}`,
        status,
        payment_status: paymentStatus,
        payment_method: faker.helpers.arrayElement(paymentMethods),
        shipping_name: faker.person.fullName(),
        shipping_phone: faker.helpers.fromRegExp('09[0-9]{8}'),
        shipping_email: faker.internet.email(),
        shipping_province: 'Hà Nội',
        shipping_district: faker.helpers.arrayElement(['Quận Ba Đình', 'Quận Hoàn Kiếm', 'Quận Cầu Giấy', 'Quận Đống Đa']),
        shipping_ward: faker.helpers.arrayElement(['Phường Dịch Vọng', 'Phường Trung Hòa', 'Phường Mai Dịch']),
        shipping_address: faker.location.streetAddress(),
        subtotal: subtotal.toFixed(2),
        discount_amount: discount.toFixed(2),
        shipping_fee: shippingFee.toFixed(2),
        total_amount: total.toFixed(2),
        note: faker.helpers.maybe(() => faker.lorem.sentence(), { probability: 0.3 }) || null,
        items,
        item_count: items.length,
        created_at: faker.date.past(),
        updated_at: faker.date.recent()
    };
};

// ============================================
// AUTH MIDDLEWARE
// ============================================
const authMiddleware = (req, res, next) => {
    const authHeader = req.headers.authorization;

    if (!authHeader || !authHeader.startsWith('Bearer ')) {
        return res.status(401).json({
            success: false,
            message: 'Unauthorized - Token không hợp lệ'
        });
    }

    const token = authHeader.substring(7);

    try {
        const decoded = jwt.verify(token, JWT_SECRET);
        req.user = decoded;
        next();
    } catch (error) {
        return res.status(401).json({
            success: false,
            message: 'Unauthorized - Token hết hạn hoặc không hợp lệ'
        });
    }
};

// ============================================
// ROUTES - AUTHENTICATION
// ============================================

// Đăng ký
app.post('/api/auth/register', (req, res) => {
    const { email, password, fullname, phone } = req.body;

    // Validation giả
    if (!email || !password || !fullname) {
        return res.status(422).json({
            success: false,
            message: 'Validation failed',
            errors: {
                email: !email ? ['Email là bắt buộc'] : [],
                password: !password ? ['Mật khẩu là bắt buộc'] : [],
                fullname: !fullname ? ['Họ tên là bắt buộc'] : []
            }
        });
    }

    const user = {
        id: faker.number.int({ min: 1, max: 1000 }),
        email,
        fullname,
        phone: phone || null,
        avatar: null
    };

    const token = generateToken(user.id);

    res.status(201).json({
        success: true,
        message: 'Đăng ký thành công',
        data: {
            user,
            token
        }
    });
});

// Đăng nhập
app.post('/api/auth/login', (req, res) => {
    const { email, password } = req.body;

    if (!email || !password) {
        return res.status(422).json({
            success: false,
            message: 'Email và mật khẩu là bắt buộc'
        });
    }

    // Giả lập đăng nhập thành công
    const user = generateUser('customer');
    user.email = email;
    const token = generateToken(user.id);

    res.json({
        success: true,
        message: 'Đăng nhập thành công',
        data: {
            user,
            token,
            expires_in: 86400
        }
    });
});

// Đăng xuất
app.post('/api/auth/logout', authMiddleware, (req, res) => {
    res.json({
        success: true,
        message: 'Đăng xuất thành công'
    });
});

// Quên mật khẩu
app.post('/api/auth/forgot-password', (req, res) => {
    const { email } = req.body;

    if (!email) {
        return res.status(422).json({
            success: false,
            message: 'Email là bắt buộc'
        });
    }

    res.json({
        success: true,
        message: 'Email đặt lại mật khẩu đã được gửi'
    });
});

// Refresh token
app.post('/api/auth/refresh', authMiddleware, (req, res) => {
    const newToken = generateToken(req.user.userId);

    res.json({
        success: true,
        data: {
            token: newToken,
            expires_in: 86400
        }
    });
});

// ============================================
// ROUTES - PRODUCTS
// ============================================

// Danh sách sản phẩm
app.get('/api/products', (req, res) => {
    const page = parseInt(req.query.page) || 1;
    const perPage = parseInt(req.query.per_page) || 20;
    const total = faker.number.int({ min: 50, max: 200 });
    const lastPage = Math.ceil(total / perPage);

    const products = Array.from({ length: perPage }, generateProduct);

    res.json({
        success: true,
        data: {
            current_page: page,
            per_page: perPage,
            total,
            last_page: lastPage,
            data: products
        }
    });
});

// Chi tiết sản phẩm
app.get('/api/products/:id', (req, res) => {
    const product = generateProduct();
    product.id = parseInt(req.params.id);

    // Thêm thông tin chi tiết
    product.images = Array.from({ length: faker.number.int({ min: 3, max: 6 }) }, (_, i) => ({
        id: i + 1,
        image_path: faker.image.url({ width: 800, height: 800 }),
        is_primary: i === 0,
        display_order: i + 1
    }));

    product.variants = Array.from({ length: faker.number.int({ min: 2, max: 6 }) }, (_, i) => ({
        id: i + 1,
        sku: faker.string.alphanumeric({ length: 10, casing: 'upper' }),
        price: product.price,
        sale_price: product.sale_price,
        quantity: faker.number.int({ min: 0, max: 50 }),
        is_active: true,
        attributes: [
            {
                group: 'Màu sắc',
                value: faker.helpers.arrayElement(['Đen', 'Trắng', 'Xanh Dương', 'Hồng', 'Vàng']),
                color_code: faker.color.rgb()
            },
            {
                group: 'Dung lượng',
                value: faker.helpers.arrayElement(['64GB', '128GB', '256GB', '512GB', '1TB'])
            }
        ]
    }));

    res.json({
        success: true,
        data: product
    });
});

// Sản phẩm nổi bật
app.get('/api/products/featured', (req, res) => {
    const limit = parseInt(req.query.limit) || 10;
    const products = Array.from({ length: limit }, () => {
        const product = generateProduct();
        product.is_featured = true;
        return product;
    });

    res.json({
        success: true,
        data: products
    });
});

// Danh sách danh mục
app.get('/api/categories', (req, res) => {
    const categoriesWithChildren = categories
        .filter(c => c.parent_id === null)
        .map(parent => ({
            ...parent,
            image: faker.image.url({ width: 400, height: 400 }),
            icon: 'icon-' + parent.slug,
            description: faker.commerce.productDescription(),
            display_order: parent.id,
            children: categories
                .filter(c => c.parent_id === parent.id)
                .map(child => ({
                    ...child,
                    image: faker.image.url({ width: 400, height: 400 })
                }))
        }));

    res.json({
        success: true,
        data: categoriesWithChildren
    });
});

// Danh sách thương hiệu
app.get('/api/brands', (req, res) => {
    const brandsWithDescription = brands.map(b => ({
        ...b,
        description: faker.company.catchPhrase()
    }));

    res.json({
        success: true,
        data: brandsWithDescription
    });
});

// ============================================
// ROUTES - CART
// ============================================

// Lấy giỏ hàng
app.get('/api/cart', (req, res) => {
    const items = Array.from({ length: faker.number.int({ min: 1, max: 5 }) }, () => {
        const product = generateProduct();
        const quantity = faker.number.int({ min: 1, max: 3 });
        const price = parseFloat(product.sale_price || product.price);

        return {
            id: faker.number.int({ min: 1, max: 1000 }),
            product: {
                id: product.id,
                name: product.name,
                slug: product.slug,
                primary_image: product.primary_image
            },
            variant: {
                id: faker.number.int({ min: 1, max: 100 }),
                attributes: [
                    { group: 'Màu sắc', value: faker.helpers.arrayElement(['Đen', 'Trắng', 'Xanh']) },
                    { group: 'Dung lượng', value: faker.helpers.arrayElement(['128GB', '256GB']) }
                ]
            },
            quantity,
            price: price.toFixed(2),
            subtotal: (price * quantity).toFixed(2)
        };
    });

    const subtotal = items.reduce((sum, item) => sum + parseFloat(item.subtotal), 0);
    const shippingFee = 30000;
    const total = subtotal + shippingFee;

    res.json({
        success: true,
        data: {
            items,
            summary: {
                subtotal: subtotal.toFixed(2),
                shipping_fee: shippingFee.toFixed(2),
                total: total.toFixed(2)
            }
        }
    });
});

// Thêm vào giỏ hàng
app.post('/api/cart/add', (req, res) => {
    const { product_id, variant_id, quantity } = req.body;

    res.json({
        success: true,
        message: 'Đã thêm vào giỏ hàng',
        data: {
            cart_item: {
                id: faker.number.int({ min: 1, max: 1000 }),
                product_id,
                variant_id,
                quantity
            }
        }
    });
});

// Cập nhật giỏ hàng
app.put('/api/cart/:id', (req, res) => {
    res.json({
        success: true,
        message: 'Cập nhật giỏ hàng thành công'
    });
});

// Xóa khỏi giỏ hàng
app.delete('/api/cart/:id', (req, res) => {
    res.json({
        success: true,
        message: 'Đã xóa khỏi giỏ hàng'
    });
});

// Xóa toàn bộ giỏ hàng
app.delete('/api/cart/clear', (req, res) => {
    res.json({
        success: true,
        message: 'Đã xóa toàn bộ giỏ hàng'
    });
});

// ============================================
// ROUTES - ORDERS
// ============================================

// Tạo đơn hàng
app.post('/api/orders', (req, res) => {
    const order = generateOrder();
    order.shipping_name = req.body.shipping_name || order.shipping_name;
    order.shipping_phone = req.body.shipping_phone || order.shipping_phone;
    order.payment_method = req.body.payment_method || 'cod';
    order.status = 'pending';
    order.payment_status = 'unpaid';

    res.status(201).json({
        success: true,
        message: 'Đặt hàng thành công',
        data: {
            order,
            payment_url: req.body.payment_method === 'cod' ? null : 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?...'
        }
    });
});

// Danh sách đơn hàng
app.get('/api/orders', authMiddleware, (req, res) => {
    const page = parseInt(req.query.page) || 1;
    const perPage = parseInt(req.query.per_page) || 20;
    const total = faker.number.int({ min: 5, max: 50 });

    const orders = Array.from({ length: Math.min(perPage, total) }, () => generateOrder(req.user.userId));

    res.json({
        success: true,
        data: {
            current_page: page,
            per_page: perPage,
            total,
            data: orders.map(o => ({
                id: o.id,
                order_code: o.order_code,
                status: o.status,
                payment_status: o.payment_status,
                total_amount: o.total_amount,
                item_count: o.item_count,
                created_at: o.created_at
            }))
        }
    });
});

// Chi tiết đơn hàng
app.get('/api/orders/:id', authMiddleware, (req, res) => {
    const order = generateOrder(req.user.userId);
    order.id = parseInt(req.params.id);

    // Thêm status history
    order.status_history = [
        {
            from_status: null,
            to_status: 'pending',
            created_at: order.created_at
        },
        {
            from_status: 'pending',
            to_status: order.status,
            created_at: faker.date.between({ from: order.created_at, to: new Date() })
        }
    ];

    res.json({
        success: true,
        data: order
    });
});

// Hủy đơn hàng
app.post('/api/orders/:id/cancel', authMiddleware, (req, res) => {
    res.json({
        success: true,
        message: 'Đã hủy đơn hàng'
    });
});

// ============================================
// ROUTES - USER PROFILE
// ============================================

// Lấy profile
app.get('/api/user/profile', authMiddleware, (req, res) => {
    const user = generateUser('customer');
    user.id = req.user.userId;

    res.json({
        success: true,
        data: user
    });
});

// Cập nhật profile
app.put('/api/user/profile', authMiddleware, (req, res) => {
    res.json({
        success: true,
        message: 'Cập nhật thông tin thành công'
    });
});

// Đổi mật khẩu
app.put('/api/user/change-password', authMiddleware, (req, res) => {
    res.json({
        success: true,
        message: 'Đổi mật khẩu thành công'
    });
});

// ============================================
// ERROR HANDLER
// ============================================
app.use((req, res) => {
    res.status(404).json({
        success: false,
        message: 'API endpoint not found'
    });
});

// ============================================
// START SERVER
// ============================================
app.listen(PORT, () => {
    console.log('='.repeat(60));
    console.log('🚀 SPACELINK MOCK API SERVER STARTED');
    console.log('='.repeat(60));
    console.log(`📍 Server running at: http://localhost:${PORT}`);
    console.log(`📚 Base API URL: http://localhost:${PORT}/api`);
    console.log('='.repeat(60));
    console.log('📋 Available endpoints:');
    console.log('   AUTH:');
    console.log('   - POST   /api/auth/register');
    console.log('   - POST   /api/auth/login');
    console.log('   - POST   /api/auth/logout (auth required)');
    console.log('   - POST   /api/auth/forgot-password');
    console.log('   - POST   /api/auth/refresh (auth required)');
    console.log('');
    console.log('   PRODUCTS:');
    console.log('   - GET    /api/products');
    console.log('   - GET    /api/products/:id');
    console.log('   - GET    /api/products/featured');
    console.log('   - GET    /api/categories');
    console.log('   - GET    /api/brands');
    console.log('');
    console.log('   CART:');
    console.log('   - GET    /api/cart');
    console.log('   - POST   /api/cart/add');
    console.log('   - PUT    /api/cart/:id');
    console.log('   - DELETE /api/cart/:id');
    console.log('   - DELETE /api/cart/clear');
    console.log('');
    console.log('   ORDERS:');
    console.log('   - POST   /api/orders');
    console.log('   - GET    /api/orders (auth required)');
    console.log('   - GET    /api/orders/:id (auth required)');
    console.log('   - POST   /api/orders/:id/cancel (auth required)');
    console.log('');
    console.log('   USER:');
    console.log('   - GET    /api/user/profile (auth required)');
    console.log('   - PUT    /api/user/profile (auth required)');
    console.log('   - PUT    /api/user/change-password (auth required)');
    console.log('='.repeat(60));
    console.log('✨ Mock API server is ready for Frontend testing!');
    console.log('='.repeat(60));
});
