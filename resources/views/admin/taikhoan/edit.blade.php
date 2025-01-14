@extends('admin.layout.index')
@section('content')
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="admin/taikhoan/"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="admin/taikhoan/">Quản Lý tài khoản</a></li>
                            <li class="breadcrumb-item"><a href="admin/taikhoan/sua/{{$taikhoan->ID}}">Sửa tài khoản</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-4 order-xl-2">
            <div class="card card-profile">
                <img src="public/images/image.png" id="anhtk" class="card-img-top" width="200px" height="250px">
            </div>
        </div>
        <div class="col-xl-8 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">Sửa tài khoản</h3>
                        </div>
                        <div class="col-4 text-right">
                            <a href="admin/taikhoan/" class="btn btn-sm btn-primary">Quay lại</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="admin/taikhoan/sua/{{$taikhoan->ID}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <h6 class="heading-small text-muted mb-4">Thông tin tài khoản</h6>
                        @if (session('thongbao'))
                        <div class="alert alert-success">
                            {{session('thongbao')}}
                        </div>
                        @endif
                        <div class="pl-lg-4">
                            <p id ="anh123" > </p>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">Họ và tên</label>
                                        <input type="text" id="HOTEN" name="HOTEN" class="form-control" value="{{$taikhoan->HOTEN}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-email">Số điện thoại</label>
                                        <input type="text" id="SODIENTHOAI" name="SODIENTHOAI" class="form-control"value="{{$taikhoan->SODIENTHOAI}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-first-name">Ngày sinh</label></br>
                                        <input type="date" id="NGAYSINH" name="NGAYSINH" class="form-control" value="{{ date('Y-m-d', strtotime($taikhoan->NGAYSINH)) }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-username">Giới tính</label></br>
                                        <input name="GIOITINH" type="radio" id="nam" value="1" style="vertical-align:middle; cursor: pointer;" @if($taikhoan->GIOITINH == true)
                                        {{"checked"}}
                                        @endif>
                                        <label>Nam</label><br>
                                        <input name="GIOITINH" type="radio" id="nu" value="0" style="vertical-align:middle; cursor: pointer;" @if($taikhoan->GIOITINH == false)
                                        {{"checked"}}
                                        @endif>
                                        <label>Nữ</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pl-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">Email</label>
                                <input type="email" id="EMAIL" name="EMAIL" class="form-control" value="{{$taikhoan->EMAIL}}">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Loại tài khoản</label>
                                <select name="LOAITK" id="LOAITK">
                                            @if ($taikhoan->LOAITK ==1)
                                            <option value="1">Học Viên</option>
                                            @else
                                            <option value="2">Giảng Viên</option>
                                            @endif

                                        </select>
                            </div>
                        </div>
                          
                        <div class="pl-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">Hình ảnh</label>
                                <input type="file" id="ANH" name="ANH" class="form-control" img src="public/images/{{$taikhoan->ANHDAIDIEN}}"></br>                                
                                
                            </div>
                            @if($taikhoan->LOAITK==2)
                            <div class="form-group">
                                <label class="form-control-label">Chi tiết cá nhân</label>
                                <textarea rows="4" name="CTCANHAN" class="form-control">{{$taikhoan->CTCANHAN}}</textarea>
                            </div>
                            @endif
                        </div>
                        
                        <div class="pl-lg-4">
                            <div class="form-group">
                                <label class="form-control-label" for="input-username">Trạng thái</label>
                                <input name="TRANGTHAI" type="radio" id="hoatdong" value="1" style="vertical-align:middle; cursor: pointer;" @if($taikhoan->TRANGTHAI == true)
                                        {{"checked"}}
                                        @endif>
                                <label>Hoạt động</label>
                                <input name="TRANGTHAI" type="radio" id="vohieu" value="0" style="vertical-align:middle; cursor: pointer;" @if($taikhoan->TRANGTHAI == false)
                                        {{"checked"}}
                                        @endif>
                                <label>Vô hiệu hóa</label>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-8">
                                <label id="lbl"></label>
                                <button type="submit" class="btn btn-default">Sửa</button>
                                @if($taikhoan->LOAITK==2)
                                <a href="admin/taikhoan/sua/chungchi/{{$taikhoan->ID}}" class="btn btn-primary">Thêm chứng chỉ</a>
                                @endif
                            </div>
                        </div>
                        @if($taikhoan->LOAITK==2)
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="tencc">Học vị</th>
                                <th scope="col" class="sort" data-sort="anhcc">Ảnh chứng chỉ</th>
                                <th scope="col" class="sort" data-sort=""></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($chungchi as $cc)
                                <tr>
                                    <th scope="row">
                                        {{$cc->HOCVI}}
                                    </th>
                                    <th scope="row">
                                    <div class="media align-items-center">
                                        <img src="public/images/{{$cc->ANHCHUNGCHI}}" Width="120px" Height="100px">
                                    </div>
                                    </th>
                                    <th scope="row">
                                    <a href="admin/taikhoan/sua/chungchi/sua/{{$taikhoan->ID}}&macc={{$cc->MACHUNGCHI}}" class="edit text-yellow" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a>
                                    <a href="admin/taikhoan/sua/chungchi/xoa/{{$taikhoan->ID}}&macc={{$cc->MACHUNGCHI}}" class="delete text-red" title="Delete" data-toggle="tooltip" onclick="return confirm('Bạn có muốn xóa tài khoản này?')"><i class="material-icons">&#xE872;</i></a>
                                    </th>
                                </tr>         
                            @endforeach
                        </tbody>
                    </table>
                </div>   
                @endif 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
