<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiHoc extends Model
{
    protected $table = "baihoc";
    protected $primaryKey = 'MABH';

     // public function rKhoaHoc()
    // {
    //     return $this->belongsTo('App\Models\KhoaHoc', 'MAKH');
    // }

    public function rLopHoc_BaiHoc()
    {
        return $this->hasMany('App\Models\LopHoc_BaiHoc', 'MABH');
    }

    public function rChuongHoc()
    {
        return $this->belongsTo('App\Models\ChuongHoc', 'MACHUONG');
    }
}