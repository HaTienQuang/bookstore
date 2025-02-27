# 📚 BOOKstore

## 📝 Giới thiệu
BOOKstore là một nền tảng thương mại điện tử chuyên cung cấp các loại sách đa dạng. Website cho phép người dùng duyệt, tìm kiếm và mua sách trực tuyến một cách dễ dàng.

---

## 🎯 Chức năng chính

### 🔹 1. Quản lý tài khoản người dùng
- Đăng ký, đăng nhập và đăng xuất tài khoản.
- Quản lý thông tin cá nhân.
- Đổi mật khẩu và khôi phục mật khẩu.

### 🔹 2. Duyệt và tìm kiếm sản phẩm
- Hiển thị danh mục sách theo thể loại:
  - Khuyến mãi
  - Sản phẩm mới
  - Sách giáo dục
  - Truyện tranh
  - Kỹ năng sống
  - Tiểu thuyết
- Tìm kiếm sách theo tên hoặc thể loại.
- Xem chi tiết sản phẩm, bao gồm mô tả, giá cả và đánh giá.

### 🔹 3. Giỏ hàng và thanh toán
- Thêm, xóa và chỉnh sửa số lượng sản phẩm trong giỏ hàng.
- Thanh toán đơn hàng bằng các phương thức phổ biến.
- Xem lịch sử mua hàng.

### 🔹 4. Quản lý đơn hàng
- Theo dõi trạng thái đơn hàng (đang xử lý, đang giao, hoàn thành).
- Hủy đơn hàng khi chưa giao hàng.

### 🔹 5. Đánh giá và nhận xét
- Người dùng có thể để lại đánh giá và bình luận về sách đã mua.
- Hệ thống đánh giá sao giúp người dùng khác có thêm thông tin về sản phẩm.

### 🔹 6. Hỗ trợ khách hàng
- Chức năng liên hệ và gửi yêu cầu hỗ trợ.
- Hệ thống chatbot hoặc FAQs để hỗ trợ nhanh chóng.

---

## 🏗️ Công nghệ sử dụng
- **Backend:** Laravel (PHP)
- **Frontend:** HTML, CSS, JavaScript
- **Cơ sở dữ liệu:** MySQL
- **XAMPP** để chạy môi trường localhost

---

## 🚀 Cách cài đặt và chạy dự án
### Yêu cầu hệ thống
- PHP >= 8.0
- Composer
- MySQL
- XAMPP
- Git

### Hướng dẫn cài đặt
1. Clone repository:
   ```sh
   git clone https://github.com/HaTienQuang/bookstore.git
   ```
2. Di chuyển vào thư mục dự án:
   ```sh
   cd bookstore
   ```
3. Cài đặt các gói PHP bằng Composer:
   ```sh
   composer install
   ```
4. Tạo file `.env` và thiết lập thông tin database:
   ```sh
   cp .env.example .env
   ```
5. Tạo key ứng dụng:
   ```sh
   php artisan key:generate
   ```
6. Chạy migration để tạo database:
   ```sh
   php artisan migrate --seed
   ```
7. Khởi động server:
   ```sh
   php artisan serve
   ```
8. Mở trình duyệt và truy cập:
   ```
   http://bookstore.localhost/
   ```



🚀 **Chúc bạn có trải nghiệm mua sắm sách tuyệt vời tại BOOKstore!**

