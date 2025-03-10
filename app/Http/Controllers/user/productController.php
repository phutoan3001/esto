<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\KhoaHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;

class productController extends Controller
{
    public function detailProduct($productId)
    {
        $cateCourse = DB::table('danhmuc')
            ->where('MADMCHA', 0)->orderby('MADM', 'desc')->get();
        $productDetail  = DB::table('khoahoc')
            ->join('danhmuc', 'danhmuc.MADM', '=', 'khoahoc.MADM')
            ->join('taikhoan', 'khoahoc.MAGV', '=', 'ID')->where('khoahoc.MAKH',  $productId)->get();

        $sectionCourse  = DB::table('chuonghoc')
            ->where('chuonghoc.MAKH', '=', $productId)->get();

        if (count($sectionCourse) > 0) {
            foreach ($sectionCourse as $sec) {
                $sections = $sec->MACHUONG;
            }
            $lessonCourse = DB::table('baihoc')
                ->where('baihoc.MACHUONG',  $sections)->get();
        } else {
            $lessonCourse = "";
        }

        foreach ($productDetail as $value) {
            $courseCate = $value->MADM;
        }
        $relatedCourse  = DB::table('khoahoc')
            ->join('taikhoan', 'khoahoc.MAGV', '=', 'ID')
            ->join('danhmuc', 'danhmuc.MADM', '=', 'khoahoc.MADM')
            ->where('danhmuc.MADM', $courseCate)
            ->whereNotIn('khoahoc.MAKH', [$productId])->get();

        return view('/user/courseDetail/index')->with('category', $cateCourse)->with('productDetail', $productDetail)->with('relatedCourse', $relatedCourse)->with('sectionCourse', $sectionCourse)->with('lessonCourse', $lessonCourse);
    }

    public function listCourse($courseCate)
    {
        $minPrice = KhoaHoc::min('DONGIA');
        $maxPrice = KhoaHoc::max('DONGIA');

        if (isset($_GET['sortBy'])) {
            $sortBy = $_GET['sortBy'];
            if ($sortBy == 'giamdan') {
                $cateById = KhoaHoc::with('rDanhMuc')
                    ->join('danhmuc', 'khoahoc.MADM', '=', 'danhmuc.MADM')
                    ->where('khoahoc.MADM', '=', $courseCate)
                    ->orWhere('danhmuc.MADMCHA', '=', $courseCate)
                    ->join('taikhoan', 'khoahoc.MAGV', '=', 'ID')
                    ->orderby('DONGIA', 'DESC')
                    ->paginate(12)
                    ->appends(request()->query());
            } elseif ($sortBy == 'tangdan') {
                $cateById = KhoaHoc::with('rDanhMuc')
                    ->join('danhmuc', 'khoahoc.MADM', '=', 'danhmuc.MADM')
                    ->where('khoahoc.MADM', '=', $courseCate)->orWhere('danhmuc.MADMCHA', '=', $courseCate)
                    ->join('taikhoan', 'khoahoc.MAGV', '=', 'ID')
                    ->orderby('DONGIA', 'ASC')
                    ->paginate(12)
                    ->appends(request()->query());
            } elseif ($sortBy == 'za') {
                $cateById = KhoaHoc::with('rDanhMuc')
                    ->join('danhmuc', 'khoahoc.MADM', '=', 'danhmuc.MADM')
                    ->where('khoahoc.MADM', '=', $courseCate)
                    ->orWhere('danhmuc.MADMCHA', '=', $courseCate)
                    ->join('taikhoan', 'khoahoc.MAGV', '=', 'ID')
                    ->orderby('TENKH', 'DESC')
                    ->paginate(12)
                    ->appends(request()->query());
            } elseif ($sortBy == 'az') {
                $cateById = KhoaHoc::with('rDanhMuc')
                    ->join('danhmuc', 'khoahoc.MADM', '=', 'danhmuc.MADM')
                    ->where('khoahoc.MADM', '=', $courseCate)
                    ->orWhere('danhmuc.MADMCHA', '=', $courseCate)
                    ->join('taikhoan', 'khoahoc.MAGV', '=', 'ID')
                    ->orderby('TENKH', 'ASC')
                    ->paginate(12)
                    ->appends(request()->query());
            }
        } elseif (isset($_GET['filtePrice']) && isset($_GET['endPrice'])) {
            $minPrice = $_GET['startPrice'];
            $maxPrice = $_GET['endPrice'];
            $cateById = KhoaHoc::with('rDanhMuc')
                ->join('danhmuc', 'khoahoc.MADM', '=', 'danhmuc.MADM')
                ->where('khoahoc.MADM', '=', $courseCate)
                ->orWhere('danhmuc.MADMCHA', '=', $courseCate)
                ->whereBetween('DONGIA', [$minPrice, $maxPrice])
                ->join('taikhoan', 'khoahoc.MAGV', '=', 'ID')
                ->orderby('DONGIA', 'ASC')
                ->paginate(12)
                ->appends(request()->query());
        } else {
            $cateById = DB::table('khoahoc')->join('danhmuc', 'khoahoc.MADM', '=', 'danhmuc.MADM')->where('khoahoc.MADM', '=', $courseCate)->orWhere('danhmuc.MADMCHA', '=', $courseCate)->join('taikhoan', 'khoahoc.MAGV', '=', 'ID')->paginate(12);
        }
        $cateName = DB::table('danhmuc')->where('danhmuc.MADM', $courseCate)->get();

        $listCate = DB::table('danhmuc')->where('danhmuc.MADMCHA', $courseCate)->get();

        return view('user.listCourse.index')
            ->with('cateById', $cateById)
            ->with('cateName', $cateName)
            ->with('listCate', $listCate)
            ->with('minPrice', $minPrice)
            ->with('maxPrice', $maxPrice);
    }

    public function addToCart(Request $request)
    {
        $product = KhoaHoc::find($request->id);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($product, $product->MAKH);
        $request->session()->put('cart', $cart);
        return response()->json([
            'status' => 200,
            'qty'    => Session::get('cart')->totalQty,
            'price'  => Session::get('cart')->totalPrice
        ]);
    }

    public function getCart()
    {
        return view('user.cart.index');
    }

    public function deleteItem($id)
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->deleteItem($id);
        if (count($cart->items) > 0) {
            Session::put('cart', $cart);
        } else {
            Session::forget('cart');
        }
        return redirect()->back();
    }

    public function increaseItem($id)
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->increaseItemByOne($id);
        Session::put('cart', $cart);
        return redirect()->back();
    }

    public function decreaseItem($id)
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->decreaseItemByOne($id);
        if (count($cart->items) > 0) {
            Session::put('cart', $cart);
        } else {
            Session::forget('cart');
        }
        return redirect()->back();
    }

    public function handleExam(Request $request)
    {
        $data = $request->all();
        // $data['code-to-test'];

    }
}