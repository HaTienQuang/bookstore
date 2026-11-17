<?php

namespace App\Controllers;

use App\Models\Product;

use App\SessionGuard as Guard;

class HomeController extends Controller
{
	// public function __construct()
	// {
	// 	if (!Guard::isUserLoggedIn()) {
	// 		redirect('/login');
	// 	}

	// 	parent::__construct();
	// }

	public function index()
	{
		try {
			$products = Product::all();
			$productsSale = Product::where('khuyen_mai', '>', 0)->get();
		} catch (\Throwable $e) {
			$products = collect();
			$productsSale = collect();
			$_SESSION['db_warning'] = "Không thể kết nối cơ sở dữ liệu: " . $e->getMessage() . ". Hãy kiểm tra thông số kết nối MySQL (DB_HOST, DB_NAME, DB_USER, DB_PASS).";
		}
		$this->sendPage('home', ['product' => $products, 'product_sale' => $productsSale]);
	}

	public function about()
	{
		$this->sendPage('about/about');
	}

	public function search()
	{
		if(isset($_POST['search']) && $_POST['search'] != ''){
			$this->sendPage('layouts/search',['result'=>Product::where('ten_sach', 'like', '%' . $_POST['search']. '%')->get()]);
		}else {
			redirect('/home');
		}
	}
}
