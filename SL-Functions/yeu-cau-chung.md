#   Dự án xây dựng website bán sản phẩm công nghệ SpaceLink (ReactJS + Laravel + MySQL) 
(Bán các sản phẩm công nghệ như điện thoại là chủ đạo, laptop, máy tính bảng, phụ kiện điện thoại, phụ kiện laptop, phụ kiện máy tính bảng,... cho một doanh nghiệp tư nhân nhỏ)

## Yêu cầu bắt buộc
### Client (Dành cho người dùng)

-   Đăng nhập hệ thống (khách hàng và quản trị viên)
    +   Đăng nhập vào hệ thống bằng tài khoản đã đăng ký trước đó để truy cập các tính năng phù hợp với vai trò 
    (khách hàng hoặc quản trị viên)

-   Đăng ký tài khoản
    +   Đăng ký bằng tài khoản và mật khẩu
    +   Đăng ký bằng email hoặc số điện thoại

-   Đăng nhập tài khoản
    +   Đăng nhập bằng tài khoản và mật khẩu
    +   Đăng nhập bằng email hoặc số điện thoại
    +   Đăng nhập bằng bên thứ ba Facebook, Google, Github,...

-   Quản lý thông tin tài khoản:
    +   Xem thông tin chi tiết tài khoản
    +   Cập nhật thông tin tài khoản
        +   Thay đổi Họ và Tên
        +   Thay đổi Email
        +   Thay đổi Số điện thoại
        +   Thay đổi Ngày sinh
        +   Thay đổi Giới tính
        +   Thay đổi Mật khẩu
        +   Thay đổi Hình ảnh đại diện
    
-   Trang Chủ
    +   Hiển thị danh mục sản phẩm
    +   Hiển thị sản phẩm bán chạy
    +   Hiển thị sản phẩm gợi ý
    +   Hiển thị sản phẩm mới nhất
    +   Hiển thị sản phẩm đang được giảm giá
    +   Hiển thị sản phẩm có nhiều lượt xem (lượt truy cập)
    +   Hiển thị Top 5 sản phẩm được yêu thích nhất 

-   Sản phẩm  
    +   Hiển thị Danh sách sản phẩm (Gồm có phân trang, bộ lọc tìm kiếm, 
    +   Sắp xếp theo giá, theo lượt xem, theo lượt mua, theo lượt đánh giá, theo lượt bình luận, theo lượt chia sẻ, theo lượt lưu,...)*
    +   Hiển thị danh sách sản phẩm yêu thích

-   Tin tức:
    +   Danh sách tin tức
    +   Chi tiết tin tức

-   Liên hệ
    +   Hiển thị thông tin liên hệ (Liên hệ Zalo, Facebook)
    +   Gửi thông tin liên hệ

-   Chi tiết sản phẩm
    +   Hiển thị thông tin chi tiết sản phẩm
    +   Add Cart
    +   Mua ngay

-   Bình luận:
    +   Hiển thị bình luận tại trang chi tiết sản phẩm
    +   Thêm bình luận
    +   Ẩn bình luận
    +   Dịch bình luận
    +   Báo cáo bình luận
    +   
-   Đánh giá:
    +   Hiển thị đánh giá tại trang chi tiết sản phẩm
    +   Thêm đánh giá
    +   Ẩn đánh giá

-   Quản lý giỏ hàng:
    +   Danh sách sản phẩm
    +   Tăng giảm số lượng
    +   Xóa sản phẩm (Xoá 1 hoặc nhiều)
    +   Chọn sản phẩm để thanh toán (Tính tổng tiền)
    +   Click tên sản phẩm -> Chi Tiết Sản Phẩm

-   Thanh toán:
    +   Kiểm tra thoả mãn số lượng mua của các sản phẩm phải <= số lượng tồn kho
    +   Thông tin (Địa chỉ, số điện thoại, email,...) phải Fill sẵn thoe thông tin của account, nhập mới
    +   Thanh toán Online (VNPAY,MOMO)
    +   Thanh toán COD 
    +   Thêm mã giảm giá (Nơi xuất hiện mã giảm giá)
    +   Trừ số lượng trong kho hàng nếu đặt hàng thành công
    +   Thanh toán thành công (Loại bỏ sản phẩm đã thanh toán) -> Gửi mail thông báo đơn hàng thanh toán thành công
    +   Thanh toán thất bại (Giữ nguyên sản phẩm trong giỏ hàng)
    +   Không cần đăng nhập vẫn có thể thanh toán (Gửi thông tin địa chỉ, tên, sđt vào form chi tiết cho cửa hàng *)
    +   Điểm thưởng đơn hàng (point)

-   Lịch sử đơn hàng:
    +   Danh sách đơn hàng đã mua (Lọc theo tab các trạng thái đơn hàng)
    +   Thông tin hiển thị chính: 
        +   Mã đơn hàng
        +   Ngày Đặt
        +   Trạng thái đơn hàng
        +   Tổng tiền
        +   Trạng thái thanh toán
        +   Trạng thái hoàn hàng,...

-   Chi tiết đơn hàng:
    +   Thông tin chung (Mã đơn hàng, ngày đặt, địa chỉ, email. số điện thoại, các trạng thái)
    +   Thôg tin chi tiết sản phẩm trong đơn hàng (Tên sản phẩm, thông tin, biến thể, đơn giá, số lượng, thành tiền)
    +   Thông tin giảm giá
    +   Tổng tiền
    +   Huỷ đơn hàng (Điều kiện huỷ đơn hàng (cần kiểm tra ở backend)) -> Nếu đã thanh toán online thì tiền về đâu ? -> Tiền sẽ hoàn trả về ví cá nhân 
    +   Lịch sử thanh đổi trạng thái đơn hàng

-   Đánh giá sản phẩm:
    +   Chỉ đánh giá khi đơn hàng đã được giao thành công (Hoàn thành)
    +   Đánh giá từng sản phẩm trong đơn hàng

### Admin (Dành cho quản trị viên)
-   Báo cáo thống kê
    +   Thống kế những biểu đồ có kết quả ý nghĩa:
    +   Doanh thu
    +   Sản phẩm
    +   Danh mục bán chạy
    +   Top người mua hàng
    +   Sản phẩm tồn kho
    +   Đơn hàng gần đây (Chờ xác nhận)
    +   Tỉ lệ đơn hàng
    +   Bộ lọc thời gian
    +   Có thể chuyển hướng khi click vào biểu đồ (sản phẩm, danh mục, đơn hàng, user,...)

-   Quản lý danh mục:
    +   Ở tất cả các chức năng quản lý sử dụng chức năng xoá mềm
    +   Có thể xoá cứng nếu đảm bảo không mất mát dữ liệu tại các bảng quan hệ (Vì sẽ gây lỗi hoặc chết hệ thống)
    +   Test Validate tại backend và frontend
        +   Không để trống
        +   Min|Max
        +   Ngày tháng (Bắt đầu - Kết thúc)
        +   Kiểu dữ liệu,...
    +   Danh mục: Không cho xoá nếu đang còn tồn tại sản phẩm    

-   Quản lý sản phẩm
    +   Khi xoá mềm:
        +   Không hiển thị tại những nơi có liên quan (Danh sách sản phẩm, Thống kê,...)
        +   Dữ liệu sản phẩm trong đơn hàng vẫn toàn vẹn

-   Quản lý biến thể sản phẩm
    +   Quản lý biến thể sản phẩm động (Có thể thêm biến thể mới)
    +   Không xoá mềm
    +   Xoá nếu không có rằng buộc sản phẩm

-   Quản lý đơn hàng:
    +   Ngoài việc có khoá ngoại đến sản phẩm, tạo bản sao thông tin sản phẩm (tên, giá bán, thông tin biến thể,...)
    +   Xác định các trạng thái đơn hàng
        +   Chờ giao hàng
        +   Xác nhận
        +   Đang giao hàng
        +   Đã giao hàng
        +   Hoàn thành
        +   Hủy đơn
        +   Hoàn hàng
    +   Trạng thái thanh toán:
        +   Đã thanh toán
        +   Chưa thanh toán (Tự động chuyển trạng thái khi đơn hàng hoàn thành)
    +   Hiển thị chi tiết đơn hàng:
        +   Thông tin chung đơn hàng
        +   Thông tin sản phẩm, giảm giá, tổng tiền,...
        +   Có thể thay đổi trạng thái đơn hàng tại danh sách và chi tiết đơn hàng (Khônhg thể quay lại trạng thái cũ, cần xác nhậ trước khi chuyển trạng thái)
    +   Chuyển trang chi tiết sản phẩm khi click vào sản phẩm trong đơn hàng
    +   Chuyển trạng thái gửi email
           
-   Quản lý voucher:
    +   Hai loại: Giảm cố định và Giảm phần trăm
    +   Giá trị đơn hàng tối thiểu
    +   Giá trị giảm giá tối đa
    +   Thời gian bắt đầu - Kết thúc
    +   Số lượng
-   Quản lý Bình luận:
    +   
    +   Chuyển trạng thái bình luận
-   Quản lý Banner
-   Quản lý Tin tức
-   Quản lý Sự kiện
-   Quản lý User
    +   Không được XOÁ USER
    +   Chỉ được chuyển trạng thái (Kích Hoạt, Khoá)
    +   Khi khoá không thể đăng nhập (đơn hàng đã mua vẫn phải xử lý)


### Chung
-   Phân quyền:
    +   Admin
    +   Khách hàng
    +   Nhân viên bán hàng

-   Thông báo
-   Chat (Realtime)
 
<!-- ## Mục tiêu
+   Hiển thị danh sách các sản phẩm
+   Hiển thị thông tin chi tiết của sản phẩm
+   Hiển thị danh sách tin tức liên hệ
+   Hiển thị chi tiết tin tức   -->