<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KhoAiDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Seeder cho môi trường local/demo, không sử dụng làm tài khoản mặc định khi triển khai thật.
        $roles = ['Quản trị viên', 'Bộ phận mua hàng', 'Bộ phận kinh doanh', 'Nhân viên kho', 'Quản lý kho'];
        foreach ($roles as $i => $name) {
            DB::table('VaiTro')->insert(['VaiTroID' => $i + 1, 'TenVaiTro' => $name]);
        }
        $functions = ['Quản lý danh mục', 'Nhập kho', 'Xuất kho', 'Tồn kho', 'Kiểm kê', 'Báo cáo', 'Cảnh báo', 'Phân tích AI', 'Quản lý người dùng'];
        foreach ($functions as $i => $name) {
            DB::table('ChucNang')->insert(['ChucNangID' => $i + 1, 'TenChucNang' => $name]);
        }
        $rolePermission = [
            1 => [1, 4, 9], 2 => [1, 2, 4, 7], 3 => [1, 3, 4],
            4 => [1, 2, 3, 4, 5, 7], 5 => [1, 2, 3, 4, 5, 6, 7, 8],
        ];
        foreach ($rolePermission as $roleId => $featureIds) {
            foreach ($featureIds as $featureId) {
                DB::table('VaiTro_ChucNang')->insert([
                    'VaiTroID' => $roleId, 'ChucNangID' => $featureId,
                    'QuyenTruyCap' => 'Được truy cập',
                ]);
            }
        }

        $accounts = [
            ['admin', 'Nguyễn Minh Anh', 'admin@khoai.local'],
            ['muahang', 'Trần Thu Hà', 'muahang@khoai.local'],
            ['kinhdoanh', 'Lê Quốc Bảo', 'kinhdoanh@khoai.local'],
            ['nhanvienkho', 'Phạm Ngọc Linh', 'nhanvienkho@khoai.local'],
            ['quanlykho', 'Đặng Hoàng Nam', 'quanlykho@khoai.local'],
        ];
        foreach ($accounts as $i => [$username, $name, $email]) {
            DB::table('TaiKhoan')->insert([
                'TaiKhoanID' => $i + 1,
                'TenDangNhap' => $username,
                'MatKhau' => Hash::make('Demo@123456'),
                'HoTen' => $name,
                'Email' => $email,
                'VaiTroID' => $i + 1,
                'TrangThai' => 'Hoạt động',
            ]);
        }
        foreach (['Thiết bị điện tử', 'Phụ kiện', 'Vật tư'] as $i => $group) {
            DB::table('NhomHang')->insert(['NhomHangID' => $i + 1, 'TenNhom' => $group, 'TrangThai' => 'Hoạt động']);
        }
        DB::table('DonViTinh')->insert(['DonViTinhID' => 1, 'TenDonVi' => 'Cái', 'TrangThai' => 'Hoạt động']);
        DB::table('Kho')->insert(['KhoID' => 1, 'MaKho' => 'K01', 'TenKho' => 'Kho trung tâm', 'DiaDiem' => 'Kho mô phỏng', 'TrangThai' => 'Hoạt động']);
        for ($i = 1; $i <= 6; $i++) {
            DB::table('ViTriLuuTru')->insert([
                'ViTriID' => $i, 'KhoID' => 1, 'MaViTri' => sprintf('A-%02d', $i),
                'TenViTri' => sprintf('Kệ A-%02d', $i), 'TrangThai' => 'Hoạt động',
            ]);
        }
        DB::table('NhaCungCap')->insert([
            'NCCID' => 1, 'MaNCC' => 'NCC001', 'TenNCC' => 'Nhà cung cấp mẫu',
            'TrangThai' => 'Hoạt động',
        ]);

        // SKU, tên, mã nhóm hàng, lượng xuất 6 kỳ, tồn hiện tại, xu hướng, dự báo kỳ 7.
        $data = [
            ['HH001','Bộ chuyển đổi USB-C',1,[100,110,120,125,140,150],120,'Tăng',159],
            ['HH002','Bàn phím văn phòng',2,[180,175,185,178,182,180],250,'Ổn định',181],
            ['HH003','Chuột quang có dây',2,[120,105,95,90,75,65],90,'Giảm',55],
            ['HH004','Tai nghe Bluetooth',1,[80,100,110,130,150,170],100,'Tăng',185],
            ['HH005','Cáp mạng Cat6',3,[60,65,62,68,70,72],150,'Ổn định',74],
            ['HH006','Ổ cắm đa năng',3,[95,100,98,105,110,115],90,'Tăng',118],
            ['HH007','Màn hình LED 24 inch',1,[210,205,215,220,225,230],260,'Tăng',234],
            ['HH008','Loa di động mini',1,[70,85,95,110,125,140],80,'Tăng',153],
            ['HH009','Giá đỡ laptop',2,[160,150,145,140,138,135],210,'Giảm',128],
            ['HH010','Hub USB 4 cổng',2,[50,55,60,58,62,65],55,'Ổn định',68],
            ['HH011','Webcam HD',1,[130,135,140,145,150,155],100,'Tăng',160],
            ['HH012','Kẹp quản lý dây',3,[90,88,92,95,97,96],160,'Ổn định',99],
            ['HH013','Bộ sạc dự phòng',1,[200,195,190,185,180,175],300,'Giảm',170],
            ['HH014','Đèn bàn LED',1,[75,80,90,100,115,125],70,'Tăng',134],
            ['HH015','Bàn di chuột',2,[110,108,112,115,118,120],140,'Ổn định',122],
            ['HH016','Thiết bị phát Wi-Fi',1,[45,50,55,65,75,85],40,'Tăng',91],
            ['HH017','Hộp mực in',3,[250,245,255,260,258,262],280,'Ổn định',265],
            ['HH018','Giấy in A4',3,[140,135,130,128,125,120],190,'Giảm',116],
            ['HH019','Camera IP',1,[85,90,92,95,100,105],75,'Tăng',108],
            ['HH020','Đầu đọc thẻ',2,[55,60,70,75,85,95],60,'Tăng',101],
        ];
        $timestamp = '2026-10-30 09:00:00';
        DB::table('PhanTichAI')->insert([
            'PhanTichAIID' => 1,
            'TuNgay' => '2026-01-01', 'DenNgay' => '2026-06-30', 'KyDuBao' => 'Kỳ 7',
            'ThoiGianPhanTich' => $timestamp, 'NguoiYeuCauID' => 5,
            'TrangThai' => 'Hoàn thành',
        ]);
        foreach ($data as $i => [$sku, $name, $groupId, $history, $stock, $trend, $forecast]) {
            DB::table('HangHoa')->insert([
                'SKU' => $sku, 'TenHang' => $name, 'NhomHangID' => $groupId,
                'DonViTinhID' => 1, 'TrangThai' => 'Hoạt động',
            ]);
            DB::table('TonKho')->insert([
                'SKU' => $sku, 'ViTriID' => ($i % 6) + 1,
                'SoLuongTon' => $stock, 'NgayCapNhat' => $timestamp,
            ]);
            $status = $stock < $forecast ? 'Thiếu' : ($stock >= 1.5 * $forecast ? 'Dư' : 'Bình thường');
            $suggest = max($forecast - $stock, 0);
            $resultId = $i + 1;
            DB::table('KetQuaAI')->insert([
                'KetQuaAIID' => $resultId, 'PhanTichAIID' => 1,
                'SKU' => $sku, 'KhoID' => 1, 'XuHuong' => $trend,
                'NhuCauDuBao' => $forecast, 'TonHienTai' => $stock,
                'TinhTrangTon' => $status, 'SoLuongDeXuat' => $suggest,
            ]);
            if ($status !== 'Bình thường') {
                DB::table('CanhBao')->insert([
                    'SKU' => $sku, 'KhoID' => 1, 'KetQuaAIID' => $resultId,
                    'LoaiCanhBao' => $status, 'NoiDung' => "SKU {$sku} có nguy cơ {$status} hàng theo dự báo Kỳ 7.",
                    'MucDo' => $status === 'Thiếu' ? 'Cao' : 'Trung bình',
                    'ThoiGianTao' => $timestamp, 'TrangThai' => 'Chưa xử lý',
                ]);
            }
        }
    }
}
