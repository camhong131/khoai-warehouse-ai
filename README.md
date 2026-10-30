# KhoAI – Laravel 13 Starter cho GitHub, VS Code và WampServer

**Đề tài:** Hệ thống quản lý quy trình xuất nhập kho có hỗ trợ AI.

Bộ mã nguồn này được dựng từ thông số gốc người dùng gửi (`composer.json`, `composer.lock`, `artisan`, `.gitignore`, Vite) và mở rộng thành khung Laravel + migration CSDL 30 bảng theo ERD. Đồng thời giữ giao diện demo có thể bấm nút ở `public/demo`.

> **Phạm vi:** bản khởi tạo phục vụ đồ án, không phải phần mềm kho hoàn chỉnh. UI demo dùng `localStorage`, không đọc/ghi CSDL MySQL. Migration + seeder cho phép xem dữ liệu cấu trúc thật trong phpMyAdmin. Chưa có backend xác thực/phân quyền/giao dịch kho tích hợp.

## 1. Yêu cầu máy Windows

- WampServer (Apache + MySQL) đang hoạt động.
- PHP **8.3 trở lên**, cài và chọn phiên bản PHP tương thích cả trong WampServer lẫn terminal VS Code.
- Composer phiên bản hiện hành.
- Git + VS Code.
- Node.js nếu muốn phát triển CSS/JS qua Vite: **20.19+** hoặc **22.12+** cho Vite 8. Không cần Node để xem prototype ở `public/demo`.
- Mở WampServer và kiểm tra PHP hỗ trợ extension: `pdo_mysql`, `mbstring`, `openssl`, `xml`, `fileinfo`, `curl`, `dom`.

Kiểm tra trong terminal VS Code:

```powershell
php -v
composer -V
node -v
php -m
```

## 2. Chép project vào WampServer và mở VS Code

Giải nén thư mục `khoai-laravel-starter` vào:

```text
C:\wamp64\www\khoai-laravel-starter
```

Mở VS Code → **File > Open Folder** → chọn thư mục đó.

Các tệp do người dùng cung cấp trước đó chưa có `app/`, `bootstrap/`, `routes/`, `public/`, `resources/`, `database/`. Gói này đã bổ sung khung cần thiết. Laravel vẫn phải cài `vendor/` bằng Composer trên máy của bạn.

## 3. Tạo MySQL database

Bật MySQL trong WampServer, vào phpMyAdmin và tạo CSDL tên **`khoai_db`**, collation `utf8mb4_unicode_ci`.

Hoặc chạy SQL sau trong phpMyAdmin:

```sql
CREATE DATABASE IF NOT EXISTS khoai_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Nếu MySQL máy bạn không dùng cổng 3306 hoặc tài khoản `root` mật khẩu rỗng, hãy đặt đúng thông số kết nối trong `.env` riêng trên máy.

## 4. Cài Laravel dependencies và cấu hình .env

Mở terminal tại thư mục dự án:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
php artisan config:clear
php artisan migrate --seed
```

Nếu dùng CMD thay vì PowerShell, đổi `Copy-Item` thành `copy .env.example .env`.

**Tuyệt đối không đưa `.env` lên GitHub.** File `.env` người dùng tải lên trước đó có chứa `APP_KEY` thực tế của một dự án cũ: không dùng lại khóa đó; tạo khóa mới. Nếu khóa cũ đã dùng ở nơi khác, nên thay khóa tại các môi trường liên quan.

Các thông số mặc định trong `.env.example`:

```dotenv
APP_NAME=KhoAI
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://khoai.test
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=khoai_db
DB_USERNAME=root
DB_PASSWORD=
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

Seeder tạo 5 vai trò, 20 SKU, tồn kho mẫu và 20 kết quả AI. Kiểm tra bằng phpMyAdmin → `khoai_db`.

## 5. Cấu hình VirtualHost WampServer (khuyến nghị)

Trong WampServer bật `rewrite_module` của Apache. Sửa `httpd-vhosts.conf` (qua menu WampServer hoặc đường dẫn phù hợp với phiên bản Apache) và thêm cấu hình trong tệp:

`wamp/khoai.test.conf.example`

Chú ý: `DocumentRoot` **phải là** `C:/wamp64/www/khoai-laravel-starter/public`, **không được trỏ vào thư mục gốc của Laravel**.

Mở Notepad bằng quyền Administrator và bổ sung vào:

```text
C:\Windows\System32\drivers\etc\hosts
```

Dòng:

```text
127.0.0.1 khoai.test
```

Khởi động lại dịch vụ Apache của WampServer.

Truy cập:

- Website: `http://khoai.test/`
- Kiểm tra CSDL: `http://khoai.test/kiem-tra-csdl`
- UI tĩnh độc lập: `http://khoai.test/demo/`

Trang kiểm tra CSDL chỉ khả dụng khi `APP_ENV=local`.

### Phương án không cấu hình VirtualHost

Có thể chạy bằng máy chủ tích hợp Laravel:

```powershell
php artisan serve
```

Truy cập `http://127.0.0.1:8000/`. WampServer khi đó chỉ cần chạy MySQL; Apache VirtualHost không bắt buộc. Khi dùng cách này, đổi `APP_URL=http://127.0.0.1:8000` trong `.env`, rồi `php artisan config:clear`.

## 6. Tài khoản dùng trình diễn giao diện

Trong prototype JS của trang chính, các tài khoản `admin`, `muahang`, `kinhdoanh`, `nhanvienkho`, `quanlykho` sử dụng mật khẩu **`123456`**. Đây là **đăng nhập giả lập tại trình duyệt**, không phải xác thực qua MySQL/Laravel.

Riêng tài khoản trong CSDL do seeder tạo có mật khẩu băm phục vụ phát triển backend, **không** sử dụng để xác thực demo hiện tại.

Kịch bản trình diễn gợi ý:

1. Đăng nhập `quanlykho` / `123456`.
2. AI dự báo: 20 SKU, 10 thiếu, 6 dư, 4 bình thường.
3. HH004 dự báo 185, tồn 100, đề xuất 85 → chọn Chấp nhận.
4. Tạo yêu cầu nhập nháp → xử lý đặt hàng và nhập hàng theo prototype.
5. Xác nhận phiếu nhập: tồn kho demo thay đổi; kiểm tra biến động tồn.
6. Chọn `Đặt lại dữ liệu mẫu` nếu muốn về trạng thái ban đầu.

**Quan trọng:** Trình duyệt lưu dữ liệu thao tác trong `localStorage`. Các thay đổi này không cập nhật MySQL. Hai bộ dữ liệu khởi tạo nhất quán nhưng độc lập.

## 7. Đưa mã nguồn lên GitHub

Đăng nhập GitHub và tạo repository rỗng, ví dụ `khoai-warehouse-ai`. Nếu mới bắt đầu, chọn **Private**.

Trong terminal VS Code:

```powershell
git init
git add .
git commit -m "Initial KhoAI Laravel starter"
git branch -M main
git remote add origin https://github.com/USERNAME/khoai-warehouse-ai.git
git push -u origin main
```

Thay `USERNAME` bằng tên GitHub của bạn. Repository nên để **trống** khi tạo (không tự thêm README/.gitignore/license) để dễ push lần đầu.

Với một repo đã tồn tại, thay vì `git init` hãy `git clone <repo-url>`, rồi chép nội dung starter vào thư mục đã clone.

`.gitignore` đã loại `.env`, `vendor/`, `node_modules/` và file sinh ra bởi build.

**GitHub Pages không chạy Laravel/PHP/MySQL**. Dùng GitHub để lưu code; dùng WampServer trên máy để chạy backend hoặc deploy lên host có PHP/MySQL về sau.

## 8. Các phần cần phát triển tiếp

- Chuyển dữ liệu giao diện từ `localStorage` sang Laravel Controller / Form Request / Model, dùng MySQL thật.
- Xây dựng đăng nhập Laravel, xác thực và middleware phân quyền 5 vai trò.
- Tạo transaction xác nhận nhập/xuất/kiểm kê để ghi `TonKho` và `BienDongTon` an toàn.
- Lưu kết quả AI/QuyetDinhAI và liên kết `CTYeuCauNhap.QuyetDinhAIID` với backend.
- Viết test nghiệp vụ, tránh xác nhận lặp và trừ tồn âm.
- Không đưa tài khoản demo hoặc `APP_DEBUG=true` lên môi trường công khai.

## 9. Cấu trúc thư mục quan trọng

```text
khoai-laravel-starter/
├── app/Providers/
├── bootstrap/
├── config/
├── database/migrations/           # Tạo 30 bảng theo ERD
├── database/seeders/              # Dữ liệu 20 SKU, dự báo, cảnh báo
├── public/
│   ├── index.php                  # Điểm vào Laravel
│   └── demo/                      # Website demo click được (localStorage)
├── resources/views/demo.blade.php # Tích hợp demo vào Laravel Route::view('/')
├── routes/web.php                 # / và /kiem-tra-csdl
├── wamp/                           # VirtualHost + hosts
├── .env.example                   # Cấu hình an toàn để công khai
├── .gitignore
├── composer.json / composer.lock
└── README.md
```

Chi tiết thông số: `docs/THONG_SO_KHOI_TAO.md`.
