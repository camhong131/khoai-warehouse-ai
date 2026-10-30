<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('VaiTro', function (Blueprint $t) {
            $t->id('VaiTroID'); $t->string('TenVaiTro', 100); $t->text('MoTa')->nullable();
        });
        Schema::create('ChucNang', function (Blueprint $t) {
            $t->id('ChucNangID'); $t->string('TenChucNang', 120); $t->text('MoTa')->nullable();
        });
        Schema::create('VaiTro_ChucNang', function (Blueprint $t) {
            $t->unsignedBigInteger('VaiTroID'); $t->unsignedBigInteger('ChucNangID');
            $t->string('QuyenTruyCap', 30);
            $t->primary(['VaiTroID', 'ChucNangID']);
            $t->foreign('VaiTroID')->references('VaiTroID')->on('VaiTro');
            $t->foreign('ChucNangID')->references('ChucNangID')->on('ChucNang');
        });
        Schema::create('TaiKhoan', function (Blueprint $t) {
            $t->id('TaiKhoanID'); $t->string('TenDangNhap', 60)->unique();
            $t->string('MatKhau'); $t->string('HoTen', 120); $t->string('Email')->unique();
            $t->foreignId('VaiTroID')->constrained('VaiTro', 'VaiTroID');
            $t->string('TrangThai', 30)->default('Hoạt động');
        });
        Schema::create('NhomHang', function (Blueprint $t) {
            $t->id('NhomHangID'); $t->string('TenNhom', 100); $t->text('MoTa')->nullable();
            $t->string('TrangThai', 30)->default('Hoạt động');
        });
        Schema::create('DonViTinh', function (Blueprint $t) {
            $t->id('DonViTinhID'); $t->string('TenDonVi', 60);
            $t->string('TrangThai', 30)->default('Hoạt động');
        });
        Schema::create('HangHoa', function (Blueprint $t) {
            $t->string('SKU', 12)->primary(); $t->string('TenHang', 180);
            $t->foreignId('NhomHangID')->constrained('NhomHang', 'NhomHangID');
            $t->foreignId('DonViTinhID')->constrained('DonViTinh', 'DonViTinhID');
            $t->string('TrangThai', 30)->default('Hoạt động');
        });
        Schema::create('Kho', function (Blueprint $t) {
            $t->id('KhoID'); $t->string('MaKho', 20)->unique();
            $t->string('TenKho', 140); $t->string('DiaDiem')->nullable();
            $t->string('TrangThai', 30)->default('Hoạt động');
        });
        Schema::create('ViTriLuuTru', function (Blueprint $t) {
            $t->id('ViTriID'); $t->foreignId('KhoID')->constrained('Kho', 'KhoID');
            $t->string('MaViTri', 30); $t->string('TenViTri', 120);
            $t->string('TrangThai', 30)->default('Hoạt động');
            $t->unique(['KhoID', 'MaViTri']);
        });
        Schema::create('NhaCungCap', function (Blueprint $t) {
            $t->id('NCCID'); $t->string('MaNCC', 30)->unique(); $t->string('TenNCC', 180);
            $t->string('DiaChi')->nullable(); $t->string('DienThoai', 30)->nullable();
            $t->string('Email')->nullable(); $t->string('TrangThai', 30)->default('Hoạt động');
        });
        Schema::create('YeuCauNhap', function (Blueprint $t) {
            $t->id('YCNID'); $t->string('MaYCN', 30)->unique(); $t->dateTime('NgayTao');
            $t->foreignId('NguoiTaoID')->constrained('TaiKhoan', 'TaiKhoanID');
            $t->string('NguonTao', 30)->default('Thủ công');
            $t->string('TrangThai', 30)->default('Nháp'); $t->text('GhiChu')->nullable();
        });
        Schema::create('DonDatHang', function (Blueprint $t) {
            $t->id('DDHID'); $t->string('MaDDH', 30)->unique();
            $t->foreignId('YCNID')->constrained('YeuCauNhap', 'YCNID');
            $t->foreignId('NCCID')->constrained('NhaCungCap', 'NCCID');
            $t->date('NgayDat'); $t->date('NgayDuKienNhan')->nullable();
            $t->string('TrangThai', 30)->default('Nháp');
        });
        Schema::create('CTDonDatHang', function (Blueprint $t) {
            $t->unsignedBigInteger('DDHID'); $t->string('SKU', 12); $t->unsignedInteger('SoLuongDat');
            $t->primary(['DDHID', 'SKU']);
            $t->foreign('DDHID')->references('DDHID')->on('DonDatHang');
            $t->foreign('SKU')->references('SKU')->on('HangHoa');
        });
        Schema::create('PhieuNhap', function (Blueprint $t) {
            $t->id('PhieuNhapID'); $t->string('MaPhieuNhap', 30)->unique();
            $t->foreignId('DDHID')->constrained('DonDatHang', 'DDHID');
            $t->foreignId('KhoID')->constrained('Kho', 'KhoID');
            $t->dateTime('NgayNhap');
            $t->foreignId('NguoiLapID')->constrained('TaiKhoan', 'TaiKhoanID');
            $t->foreignId('NguoiXacNhanID')->nullable()->constrained('TaiKhoan', 'TaiKhoanID');
            $t->string('TrangThai', 30)->default('Nháp');
        });
        Schema::create('CTPhieuNhap', function (Blueprint $t) {
            $t->unsignedBigInteger('PhieuNhapID'); $t->string('SKU', 12);
            $t->unsignedBigInteger('ViTriID'); $t->unsignedInteger('SoLuongNhap');
            $t->string('TinhTrangHang', 100)->nullable();
            $t->primary(['PhieuNhapID', 'SKU', 'ViTriID']);
            $t->foreign('PhieuNhapID')->references('PhieuNhapID')->on('PhieuNhap');
            $t->foreign('SKU')->references('SKU')->on('HangHoa');
            $t->foreign('ViTriID')->references('ViTriID')->on('ViTriLuuTru');
        });
        Schema::create('SaiLechNhap', function (Blueprint $t) {
            $t->id('SaiLechID'); $t->foreignId('PhieuNhapID')->constrained('PhieuNhap', 'PhieuNhapID');
            $t->string('SKU', 12); $t->foreign('SKU')->references('SKU')->on('HangHoa');
            $t->string('LoaiSaiLech', 70); $t->integer('SoLuongSaiLech');
            $t->text('NoiDung')->nullable(); $t->text('PhuongAnXuLy')->nullable();
            $t->string('TrangThai', 30)->default('Chờ xử lý');
        });
        Schema::create('YeuCauXuat', function (Blueprint $t) {
            $t->id('YCXID'); $t->string('MaYCX', 30)->unique(); $t->dateTime('NgayTao');
            $t->foreignId('NguoiTaoID')->constrained('TaiKhoan', 'TaiKhoanID');
            $t->string('TrangThai', 30)->default('Nháp'); $t->text('GhiChu')->nullable();
        });
        Schema::create('CTYeuCauXuat', function (Blueprint $t) {
            $t->unsignedBigInteger('YCXID'); $t->string('SKU', 12);
            $t->unsignedInteger('SoLuongYeuCau'); $t->unsignedInteger('SoLuongDaKiem')->default(0);
            $t->primary(['YCXID', 'SKU']);
            $t->foreign('YCXID')->references('YCXID')->on('YeuCauXuat');
            $t->foreign('SKU')->references('SKU')->on('HangHoa');
        });
        Schema::create('PhieuXuat', function (Blueprint $t) {
            $t->id('PhieuXuatID'); $t->string('MaPhieuXuat', 30)->unique();
            $t->foreignId('YCXID')->constrained('YeuCauXuat', 'YCXID');
            $t->foreignId('KhoID')->constrained('Kho', 'KhoID');
            $t->dateTime('NgayXuat');
            $t->foreignId('NguoiLapID')->constrained('TaiKhoan', 'TaiKhoanID');
            $t->foreignId('NguoiXacNhanID')->nullable()->constrained('TaiKhoan', 'TaiKhoanID');
            $t->string('TrangThai', 30)->default('Nháp');
        });
        Schema::create('CTPhieuXuat', function (Blueprint $t) {
            $t->unsignedBigInteger('PhieuXuatID'); $t->string('SKU', 12);
            $t->unsignedBigInteger('ViTriID'); $t->unsignedInteger('SoLuongXuat');
            $t->primary(['PhieuXuatID', 'SKU', 'ViTriID']);
            $t->foreign('PhieuXuatID')->references('PhieuXuatID')->on('PhieuXuat');
            $t->foreign('SKU')->references('SKU')->on('HangHoa');
            $t->foreign('ViTriID')->references('ViTriID')->on('ViTriLuuTru');
        });
        Schema::create('TonKho', function (Blueprint $t) {
            $t->string('SKU', 12); $t->unsignedBigInteger('ViTriID');
            $t->unsignedInteger('SoLuongTon')->default(0); $t->dateTime('NgayCapNhat');
            $t->primary(['SKU', 'ViTriID']);
            $t->foreign('SKU')->references('SKU')->on('HangHoa');
            $t->foreign('ViTriID')->references('ViTriID')->on('ViTriLuuTru');
        });
        Schema::create('BienDongTon', function (Blueprint $t) {
            $t->id('BienDongID'); $t->string('SKU', 12); $t->unsignedBigInteger('ViTriID');
            $t->string('LoaiBienDong', 40); $t->integer('SoLuongThayDoi');
            $t->unsignedInteger('SoLuongTruoc'); $t->unsignedInteger('SoLuongSau');
            $t->string('NguonPhatSinh', 50); $t->string('MaNguonPhatSinh', 50);
            $t->dateTime('ThoiGian');
            $t->foreign('SKU')->references('SKU')->on('HangHoa');
            $t->foreign('ViTriID')->references('ViTriID')->on('ViTriLuuTru');
        });
        Schema::create('DotKiemKe', function (Blueprint $t) {
            $t->id('DotKiemKeID'); $t->string('MaDot', 30)->unique();
            $t->foreignId('KhoID')->constrained('Kho', 'KhoID');
            $t->dateTime('NgayBatDau'); $t->dateTime('NgayKetThuc')->nullable();
            $t->foreignId('NguoiTaoID')->constrained('TaiKhoan', 'TaiKhoanID');
            $t->string('TrangThai', 30)->default('Nháp');
        });
        Schema::create('CTKiemKe', function (Blueprint $t) {
            $t->unsignedBigInteger('DotKiemKeID'); $t->string('SKU', 12); $t->unsignedBigInteger('ViTriID');
            $t->unsignedInteger('SoLuongHeThong'); $t->unsignedInteger('SoLuongThucTe')->nullable();
            $t->integer('ChenhLech')->nullable(); $t->string('TrangThaiXuLy', 30)->default('Chờ kiểm');
            $t->foreignId('NguoiPheDuyetID')->nullable()->constrained('TaiKhoan', 'TaiKhoanID');
            $t->primary(['DotKiemKeID', 'SKU', 'ViTriID']);
            $t->foreign('DotKiemKeID')->references('DotKiemKeID')->on('DotKiemKe');
            $t->foreign('SKU')->references('SKU')->on('HangHoa');
            $t->foreign('ViTriID')->references('ViTriID')->on('ViTriLuuTru');
        });
        Schema::create('PhanTichAI', function (Blueprint $t) {
            $t->id('PhanTichAIID'); $t->date('TuNgay'); $t->date('DenNgay');
            $t->string('KyDuBao', 40); $t->dateTime('ThoiGianPhanTich');
            $t->foreignId('NguoiYeuCauID')->constrained('TaiKhoan', 'TaiKhoanID');
            $t->string('TrangThai', 30)->default('Hoàn thành');
        });
        Schema::create('KetQuaAI', function (Blueprint $t) {
            $t->id('KetQuaAIID'); $t->foreignId('PhanTichAIID')->constrained('PhanTichAI', 'PhanTichAIID');
            $t->string('SKU', 12); $t->foreign('SKU')->references('SKU')->on('HangHoa');
            $t->foreignId('KhoID')->constrained('Kho', 'KhoID');
            $t->string('XuHuong', 30); $t->unsignedInteger('NhuCauDuBao');
            $t->unsignedInteger('TonHienTai'); $t->string('TinhTrangTon', 40);
            $t->unsignedInteger('SoLuongDeXuat')->default(0);
            $t->unique(['PhanTichAIID', 'SKU', 'KhoID'], 'ketquaai_phan_tich_sku_kho_unique');
        });
        Schema::create('QuyetDinhAI', function (Blueprint $t) {
            $t->id('QuyetDinhAIID'); $t->foreignId('KetQuaAIID')->constrained('KetQuaAI', 'KetQuaAIID');
            $t->foreignId('NguoiQuyetDinhID')->constrained('TaiKhoan', 'TaiKhoanID');
            $t->string('QuyetDinh', 30); $t->unsignedInteger('SoLuongDieuChinh')->nullable();
            $t->text('LyDo')->nullable(); $t->dateTime('ThoiGianQuyetDinh');
        });
        // CTYeuCauNhap tao sau QuyetDinhAI de FK nullable den QuyetDinhAI duoc thiet lap truc tiep.
        Schema::create('CTYeuCauNhap', function (Blueprint $t) {
            $t->unsignedBigInteger('YCNID'); $t->string('SKU', 12);
            $t->foreignId('QuyetDinhAIID')->nullable()->constrained('QuyetDinhAI', 'QuyetDinhAIID');
            $t->unsignedInteger('SoLuongYeuCau');
            $t->primary(['YCNID', 'SKU']);
            $t->foreign('YCNID')->references('YCNID')->on('YeuCauNhap');
            $t->foreign('SKU')->references('SKU')->on('HangHoa');
        });
        Schema::create('CanhBao', function (Blueprint $t) {
            $t->id('CanhBaoID'); $t->string('SKU', 12);
            $t->foreign('SKU')->references('SKU')->on('HangHoa');
            $t->foreignId('KhoID')->constrained('Kho', 'KhoID');
            $t->foreignId('KetQuaAIID')->nullable()->constrained('KetQuaAI', 'KetQuaAIID');
            $t->string('LoaiCanhBao', 50); $t->text('NoiDung');
            $t->string('MucDo', 30); $t->dateTime('ThoiGianTao');
            $t->string('TrangThai', 30)->default('Chưa xử lý');
        });
        Schema::create('NhatKyHoatDong', function (Blueprint $t) {
            $t->id('NhatKyID'); $t->foreignId('TaiKhoanID')->constrained('TaiKhoan', 'TaiKhoanID');
            $t->foreignId('ChucNangID')->constrained('ChucNang', 'ChucNangID');
            $t->dateTime('ThoiGian'); $t->string('HanhDong', 150);
            $t->string('DoiTuong', 100); $t->string('MaDoiTuong', 80)->nullable();
            $t->json('GiaTriTruoc')->nullable(); $t->json('GiaTriSau')->nullable();
        });
    }

    public function down(): void
    {
        foreach ([
            'NhatKyHoatDong', 'CanhBao', 'CTYeuCauNhap', 'QuyetDinhAI', 'KetQuaAI',
            'PhanTichAI', 'CTKiemKe', 'DotKiemKe', 'BienDongTon', 'TonKho',
            'CTPhieuXuat', 'PhieuXuat', 'CTYeuCauXuat', 'YeuCauXuat',
            'SaiLechNhap', 'CTPhieuNhap', 'PhieuNhap', 'CTDonDatHang',
            'DonDatHang', 'YeuCauNhap', 'NhaCungCap', 'ViTriLuuTru', 'Kho',
            'HangHoa', 'DonViTinh', 'NhomHang', 'TaiKhoan', 'VaiTro_ChucNang',
            'ChucNang', 'VaiTro',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
