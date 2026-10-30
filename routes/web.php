<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// Prototype giao diện đã có từ đầu; hiện xử lý nghiệp vụ bằng localStorage.
Route::view('/', 'demo')->name('home');

// Kiểm tra cài đặt CSDL sau khi chạy php artisan migrate --seed.
// Chỉ dùng trên môi trường local: không xuất thông số kết nối hoặc lỗi chi tiết.
Route::get('/kiem-tra-csdl', function () {
    if (! app()->environment('local')) {
        abort(404);
    }

    try {
        return response()->json([
            'ket_noi' => 'thanh_cong',
            'so_sku' => DB::table('HangHoa')->count(),
            'so_ket_qua_ai' => DB::table('KetQuaAI')->count(),
            'ghi_chu' => 'Prototype trang chủ vẫn dùng dữ liệu localStorage để demo thao tác.',
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'ket_noi' => 'that_bai',
            'goi_y' => 'Kiểm tra .env, MySQL WampServer và chạy php artisan migrate --seed.',
        ], 503);
    }
})->name('db.check');
