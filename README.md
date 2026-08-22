# HỆ THỐNG QUẢN LÝ HỌC VỤ TRƯỜNG TIỂU HỌC TÂN LẬP 3

## 1. Giới thiệu

Hệ thống quản lý học vụ Trường Tiểu học Tân Lập 3 được xây dựng nhằm hỗ trợ nhà trường trong việc quản lý các thông tin và hoạt động học vụ. Hệ thống hỗ trợ quản lý tập trung các thông tin liên quan đến học sinh, giáo viên, lớp học, môn học, năm học, kết quả học tập và các thông tin liên quan khác.

## 2. Công nghệ sử dụng

- Laravel 9
- PHP 8
- MySQL
- Bootstrap 5
- JavaScript
- HTML/CSS
- Vite
- Laravel Breeze

## 3. Các chức năng chính

### Quản lý tài khoản
- Đăng nhập hệ thống.
- Phân quyền người dùng theo vai trò.
- Quản lý tài khoản người dùng.

### Quản lý học sinh
- Xem danh sách học sinh.
- Thêm, sửa, xóa thông tin học sinh.
- Tìm kiếm học sinh.
- Xem thông tin chi tiết học sinh.
- Quản lý thông tin phụ huynh.
- Quản lý thông tin sức khỏe.

### Quản lý giáo viên
- Xem danh sách giáo viên.
- Thêm, sửa, xóa thông tin giáo viên.
- Quản lý thông tin giáo viên.

### Quản lý lớp học
- Quản lý danh sách lớp học.
- Thêm, sửa và xóa lớp học.
- Quản lý học sinh thuộc lớp.

### Quản lý môn học
- Quản lý danh sách môn học.
- Thêm, sửa và xóa môn học.

### Quản lý năm học
- Quản lý các năm học.
- Thêm, sửa và xóa thông tin năm học.

### Quản lý kết quả học tập
- Nhập và quản lý điểm học sinh.
- Cập nhật kết quả học tập.
- Xem kết quả học tập của học sinh.

### Quản lý kết quả rèn luyện
- Quản lý thông tin kết quả rèn luyện/hạnh kiểm của học sinh.

## 4. Cấu trúc thư mục

- `/backend`: Chứa mã nguồn hệ thống Laravel.
- `/frontend`: Chứa các tài nguyên giao diện tĩnh.
- `/docs`: Chứa báo cáo và slide thuyết trình.
- `README.md`: Tài liệu hướng dẫn và giới thiệu dự án.

## 5. Yêu cầu môi trường

Để chạy hệ thống cần chuẩn bị:

- Windows
- XAMPP
- PHP 8.x
- Composer
- Node.js và npm
- MySQL
- Git
- Visual Studio Code

## 6. Hướng dẫn cài đặt

### Bước 1: Clone project

Clone repository về máy và mở thư mục project bằng Visual Studio Code.

### Bước 2: Cài đặt thư viện Laravel

Mở Terminal tại thư mục `backend` và chạy:

    composer install

### Bước 3: Cài đặt thư viện frontend

Chạy:

    npm install

### Bước 4: Cấu hình file môi trường

Tạo file `.env` từ `.env.example` và cấu hình thông tin cơ sở dữ liệu:

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=ten_database
    DB_USERNAME=root
    DB_PASSWORD=

Thay `ten_database` bằng tên database của dự án.

### Bước 5: Tạo application key

Chạy:

    php artisan key:generate

### Bước 6: Cấu hình cơ sở dữ liệu

Mở XAMPP và khởi động Apache và MySQL.

Truy cập phpMyAdmin, tạo database tương ứng với thông tin trong file `.env`.

Nếu project được bàn giao kèm file cơ sở dữ liệu `.sql`, thực hiện Import file `.sql` vào database vừa tạo.

### Bước 7: Chạy project

Tại thư mục `backend`, chạy:

    php artisan serve

Nếu sử dụng Vite, mở thêm một Terminal và chạy:

    npm run dev

Sau đó truy cập hệ thống tại:

    http://127.0.0.1:8000

## 7. Cơ sở dữ liệu

Hệ thống sử dụng MySQL để lưu trữ dữ liệu.

Các nhóm dữ liệu chính bao gồm:

- Tài khoản người dùng
- Học sinh
- Giáo viên
- Lớp học
- Môn học
- Năm học
- Điểm số
- Thông tin phụ huynh
- Thông tin sức khỏe
- Kết quả rèn luyện

## 8. Tài khoản đăng nhập

Tài khoản đăng nhập được lưu trữ trong cơ sở dữ liệu của hệ thống.

Khi triển khai trên máy mới, cần import cơ sở dữ liệu được cung cấp để sử dụng các tài khoản và dữ liệu mẫu của hệ thống.

## 9. Tài liệu dự án

Thư mục `docs/` chứa các tài liệu liên quan đến đồ án:

- Báo cáo đồ án
- Slide thuyết trình
- Các tài liệu liên quan khác

## 10. Ghi chú

Đây là hệ thống được xây dựng phục vụ mục đích học tập và minh họa cho công tác quản lý học vụ tại trường tiểu học.