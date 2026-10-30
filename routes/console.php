<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('khoai:info', function () {
    $this->info('KhoAI – Laravel 13 / MySQL / WampServer');
    $this->line('Chạy php artisan migrate --seed để tạo dữ liệu ví dụ.');
})->purpose('Thông tin dự án KhoAI');
