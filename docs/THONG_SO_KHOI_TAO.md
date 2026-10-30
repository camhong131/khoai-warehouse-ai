# Thông số khởi tạo dự án KhoAI

## Đối chiếu từ các file người dùng cung cấp

| Thành phần | Cấu hình mẫu được gửi | Cấu hình KhoAI đề xuất | Lý do |
|---|---|---|---|
| PHP | `^8.3` | PHP 8.3 trở lên | Phù hợp Laravel 13 |
| Laravel | `^13.0` | Giữ `^13.0` | Dùng Composer lock đã cung cấp |
| Node/Vite | Vite `^8.0.0` | Node 20.19+ hoặc 22.12+; Vite 8 | Theo yêu cầu Vite 8 |
| CSDL | MySQL `musicdb2` | MySQL `khoai_db` | Tách dự án quản lý kho khỏi dự án âm nhạc |
| Server web | `localhost` | WampServer + vhost `khoai.test` | Apache phải trỏ vào `public/` |
| Session/cache/queue | `database` | `file` / `file` / `sync` | Chạy demo dễ hơn |
| Ngôn ngữ | `en` | `vi` | Phù hợp giao diện đồ án |
| Tài khoản root | root + mật khẩu rỗng | Chỉ giả định nếu máy Wamp đang cấu hình như vậy | Cấu hình riêng, không đẩy mật khẩu lên GitHub |
| APP_KEY | Khóa có giá trị trong file `.env` mẫu tải lên | Để trống `.env.example`, tạo khóa mới bằng Artisan | Bảo vệ bí mật ứng dụng |

## Quy mô mô phỏng

- 30 bảng theo ERD Chương 4, có tên trường theo từ điển dữ liệu.
- 5 vai trò nghiệp vụ.
- 20 SKU ở kho K01; 20 kết quả AI; 16 cảnh báo (10 thiếu, 6 dư).
- 10 đề xuất nhập dương, tổng 487 đơn vị theo dữ liệu minh họa.
- Website front-end trong `public/demo` hiện dùng localStorage độc lập với CSDL MySQL.

## Lưu ý kỹ thuật

- Tệp `GUEST_USER_INTEGRATION.md` thuộc bài toán nghe nhạc không được sao chép vào KhoAI.
- Đã giữ `composer.json`, `composer.lock` phù hợp Laravel 13, nhưng rút gọn `package.json` chỉ phục vụ Vite. `package-lock.json` cũ thuộc cấu hình dự án âm nhạc nên bỏ; có thể tạo lại bằng `npm install`.
- Mã nguồn chỉ là khởi tạo + demo UI; chưa có xác thực Laravel, middleware phân quyền, transaction cập nhật tồn, API AI thật hoặc kiểm thử hệ thống đầy đủ.
- Các tài khoản trong bảng `TaiKhoan` do seeder tạo có mật khẩu **băm**, chỉ phục vụ định hướng backend. Prototype front-end có tài khoản/mật khẩu demo riêng trong JavaScript.
- Schema sử dụng bảng CamelCase để khớp với ERD; khi chạy MySQL trên Linux cần lưu ý tính phân biệt chữ hoa/chữ thường của tên bảng.
