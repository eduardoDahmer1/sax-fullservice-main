<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\BackInStock;


class BackInStockController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** GET Request
    public function index()
    {
		if($this->storeSettings->is_back_in_stock == 0) return redirect()->route('admin.dashboard');
        return view('admin.backinstock.index');
    }

	//*** JSON Request
	public function datatables()
	{
		$datas = BackInStock::orderBy('product_id', 'DESC');
		//--- Integrating This Collection Into Datatables
		return Datatables::of($datas)
			->addColumn('product', function (BackInStock $data) {
				$name = mb_strlen(strip_tags($data->product->name), 'utf-8') > 50 ? mb_substr(strip_tags($data->product->name), 0, 50, 'utf-8') . '...' : strip_tags($data->product->name);
				$product = '<a href="' . route('admin-prod-edit', $data->product->id) . '" target="_blank">' . $name . '</a>';
				return $product;
			})
			->addColumn('email', function (BackInStock $data) {
				return $data->email;
			})
			->rawColumns(['product'])
			->toJson(); //--- Returning Json Data To Client Side
	}
}