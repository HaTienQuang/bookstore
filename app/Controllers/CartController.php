<?php

namespace App\Controllers;

use App\Models\User;
use App\Controllers\Controller;
use App\Models\Cart;
use App\Models\BillDetail;
use App\Models\Bill;
use App\Models\Product;
use Carbon\Carbon;
use App\SessionGuard as Guard;

class CartController extends Controller
{
	public function addCart()
	{
		if (!Guard::isUserLoggedIn()) {
			redirect('/login');
		}

		$user = Guard::user();
		if (!$user) {
			redirect('/login');
		}

		$user_id = $user->id;
		$masp = $_POST['masp'] ?? '';
		$so_luong = (int)($_POST['so-luong'] ?? 1);

		if (empty($masp)) {
			redirect('/cart');
		}

		$existing = Cart::where('ma_sach', $masp)->where('id', $user_id)->first();
		$product = Product::where('ma_sach', $masp)->first();
		$maxQty = $product ? (int)$product->so_luong : 999;

		if ($existing) {
			$newQty = $existing->so_luong_sach + $so_luong;
			if ($newQty <= $maxQty) {
				Cart::where('ma_sach', $masp)->where('id', $user_id)->update([
					'so_luong_sach' => $newQty
				]);
			}
		} else {
			try {
				Cart::create([
					'ma_sach' => $masp,
					'id' => $user_id,
					'so_luong_sach' => min($so_luong, $maxQty)
				]);
			} catch (\Throwable $e) {
				// Fallback update if record exists
				Cart::where('ma_sach', $masp)->where('id', $user_id)->update([
					'so_luong_sach' => min($so_luong, $maxQty)
				]);
			}
		}

		redirect('/cart');
	}

	public function del()
	{
		if (!Guard::isUserLoggedIn()) {
			redirect('/login');
		}

		$user = Guard::user();
		if (!$user) {
			redirect('/login');
		}

		$user_id = $user->id;
		$masp = $_POST['masp'] ?? '';
		$so_luong = (int)($_POST['so-luong'] ?? 1);

		$cartItem = Cart::where('ma_sach', $masp)->where('id', $user_id)->first();
		if ($cartItem && $cartItem->so_luong_sach > 1) {
			Cart::where('ma_sach', $masp)->where('id', $user_id)->update([
				'so_luong_sach' => max(1, $cartItem->so_luong_sach - $so_luong)
			]);
		}

		redirect('/cart');
	}

	public function cart()
	{
		if (!Guard::isUserLoggedIn()) {
			redirect('/login');
		}

		$user = Guard::user();
		if (!$user) {
			redirect('/login');
		}

		$carts = Cart::join('sach', 'sach.ma_sach', '=', 'giohang.ma_sach')
			->where('giohang.id', $user->id)
			->get();

		$this->sendPage('cart/cart', ['carts' => $carts]);
	}

	public function delCart()
	{
		if (!Guard::isUserLoggedIn()) {
			redirect('/login');
		}

		$user = Guard::user();
		if (!$user) {
			redirect('/login');
		}

		$masp = $_GET['masp'] ?? '';
		if ($masp) {
			Cart::where('ma_sach', $masp)->where('id', $user->id)->delete();
		}

		redirect('/cart');
	}

	public function pay()
	{
		if (!Guard::isUserLoggedIn()) {
			redirect('/login');
		}

		$khach = Guard::user();
		if (!$khach) {
			redirect('/login');
		}

		$gio = Cart::join('sach', 'sach.ma_sach', '=', 'giohang.ma_sach')
			->where('giohang.id', $khach->id)
			->get();

		foreach ($gio as $gh) {
			$prd = Product::where('ma_sach', $gh->ma_sach)->first();
			if ($prd) {
				$prd->update([
					'so_luong' => max(0, $prd->so_luong - $gh->so_luong_sach),
					'sold' => ($prd->sold + $gh->so_luong_sach)
				]);
			}
		}

		$tongTien = $_POST['tong-tien'] ?? 0;
		$phone = $_POST['phone'] ?? $khach->phone;
		$address = $_POST['address'] ?? $khach->address;

		$hoadon = Bill::create([
			'ngay_lap' => Carbon::now(),
			'tong_tien' => $tongTien,
			'trang_thai' => 'processing',
			'trang_thai_thanh_toan' => 'Chưa thanh toán',
			'id' => $khach->id,
			'sdt' => $phone,
			'ten_khach_hang' => $khach->name,
			'dia_chi' => $address,
			'ma_khuyen_mai' => ''
		]);

		foreach (Cart::where('id', $khach->id)->get() as $cart) {
			BillDetail::create([
				'ma_hoa_don' => $hoadon->ma_hoa_don,
				'ma_sach' => $cart->ma_sach,
				'so_luong_sp' => $cart->so_luong_sach
			]);

			Cart::where('ma_sach', $cart->ma_sach)->where('id', $cart->id)->delete();
		}

		$_SESSION['subtotal'] = 0;

		$this->sendPage('cart/pay', [
			'bill' => BillDetail::join('sach', 'sach.ma_sach', '=', 'chitiethoadon.ma_sach')
				->where('chitiethoadon.ma_hoa_don', $hoadon->ma_hoa_don)
				->get(),
			'billdetail' => Bill::where('ma_hoa_don', $hoadon->ma_hoa_don)->get()
		]);
	}
}
