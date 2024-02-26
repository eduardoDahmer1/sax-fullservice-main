<?php

namespace App\Http\Controllers\Admin;

use App\Facades\MercadoLivre as FacadesMercadoLivre;
use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\MercadoLivre;
use App\Models\Pickup;
use App\Models\Product;
use App\Models\Shipping;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MercadoLivreController extends Controller
{

    private $meli;

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->meli = MercadoLivre::first();
        parent::__construct();
    }

    public function index()
    {
        $meli = $this->meli;
        return view('admin.mercadolivre.index', compact('meli'));
    }

    public function dashboard()
    {
        $meli = $this->meli;
        $pending_orders = Order::MercadoLivreOrdersPending()->count();
        $processing_orders = Order::MercadoLivreOrdersProcessing()->count();
        $completed_orders = Order::MercadoLivreOrdersCompleted()->count();
        $days = "";
        $sales = "";
        for($i = 0; $i < 30; $i++) {
            $days .= "'".date("d M", strtotime('-'. $i .' days'))."',";

            $sales .=  "'".Order::MercadoLivreOrdersCompleted()->whereDate('created_at', '=', date("Y-m-d", strtotime('-'. $i .' days')))->count()."',";
        }
        $products_total = Product::MercadoLivreProducts()->count();
        $customers_total = Order::MercadoLivreOrders()->distinct('customer_email')->count();
        $last_month_customers = Order::MercadoLivreOrdersCompleted()->where( 'created_at', '>',now()->subDays(30))->distinct('customer_email')->count();
        $last_month_orders = Order::MercadoLivreOrdersCompleted()->where( 'created_at', '>', now()->subDays(30))->count();
        $recent_orders = Order::MercadoLivreOrders()->orderBy('id','desc')->take(5)->get();
        $recent_customers = Order::MercadoLivreOrders()->orderBy('id','desc')->distinct('customer_email')->take(5)->get();
        $popular_products = Product::MercadoLivreProducts()->orderBy('views','desc')->take(5)->get();
        $recent_products = Product::MercadoLivreProducts()->orderBy('id','desc');
        $data = array(
            "pending_orders" => $pending_orders,
            "processing_orders" => $processing_orders,
            "completed_orders" => $completed_orders,
            "products_total" => $products_total,
            "customers_total" => $customers_total,
            "last_month_customers" => $last_month_customers,
            "last_month_orders" => $last_month_orders,
            "recent_orders" => $recent_orders,
            "recent_customers" => $recent_customers,
            "popular_products" => $popular_products,
            "recent_products" => $recent_products
        );
        return view('admin.mercadolivre.dashboard', compact('meli','data','days','sales'));
    }

    /* Mercado Livre credentials configuration */
    public function update(Request $request)
    {
        $this->validate($request, [
            'app_id'             => 'required',
            'client_secret'      => 'required',
        ]);

        $this->meli->app_id             = $request->app_id;
        $this->meli->client_secret      = $request->client_secret;

        if($request->redirect_uri) {
            $this->meli->redirect_uri = $request->redirect_uri;
        }
        
        if($request->authorization_code) {
            $this->meli->authorization_code = $request->authorization_code;
        }

        $this->meli->update();

        $msg = __('Mercado Livre credentials updated with success.');
        return response()->json($msg);
    }


    /* Lists a Product on Mercado Livre */
    public function sendItem($id)
    {
        // Get current credentials for Meli
        $meli = $this->meli;
        // Prepare data for Request
        $product = Product::find($id);

        if(!$product->mercadolivre_name) {
            return redirect()->route('admin-prod-index')->with('error', 'Nome para o Mercado Livre não encontrado. Por favor cadastre um nome.');
        }

        if(!$product->stock)
        return redirect()->route('admin-prod-index')->with('error', 'O Produto não possui estoque. Para enviar anúncios, o estoque precisa ser pelo menos 0.');

        if($this->productExists($product)) {
            // Update existing Meli product instead of create a new one.
            // return error for now
            return redirect()->route('admin-prod-index')->with('error', __('This Product is already listed on Mercado Livre.'));
        }

        $url = config('mercadolivre.api_base_url') . "items";
        
        $headers = [
            "Authorization: Bearer ". $meli->access_token,
        ];
        
        $category = $this->searchCategory($product);
        $data = FacadesMercadoLivre::productBusinessLogic($product, $category);

        $resp = FacadesMercadoLivre::curlPost($url, $headers, $data);

        // if errors are thrown
        if(array_key_exists('status', $resp) && $resp['status'] == 400) {
            $errors = [];
            foreach($resp['cause'] as $key => $error) {
                array_push($errors, $error->message);
            }
            return redirect()->route('admin-prod-index')->with('errors', $errors);
        }

        if(array_key_exists('status', $resp) && $resp['status'] == 403) {
            if ($resp['message'] == "seller.unable_to_list"){
                $specific_errors = [];
                foreach($resp['cause'] as $key => $error) {
                    array_push($specific_errors, $error);
                }
                $all_errors = implode(",",$specific_errors);
                $errors[0] = "As seguintes informações estão faltando nas configurações do usuário no Mercado Livre: " . $all_errors;
                return redirect()->route('admin-prod-index')->with('errors', $errors);
            }
            if($resp['message'] == "access_token.invalid"){
                return redirect()->route('admin-prod-index')->with('error', 'Chaves incorretas ou faltando, por favor verifique suas chaves do mercado livre.');
            }
        }

        if(array_key_exists('status', $resp) && $resp['status'] == 401) {
            if ($resp['message'] == "expired_token"){
                return redirect()->route('admin-prod-index')->with('error', 'Chaves do mercado livre estão expiradas');
            }
        }

        if($resp['id']) {
            $product->mercadolivre_id = $resp['id'];
            $product->update();
        }

        // Create Product Description after creating the product listing
        $this->createDescription($product);
        return redirect()->route('admin-prod-index')->with('success', 'Produto enviado com sucesso ao Mercado Livre - ID: ' . $resp['id']);
    }

    /* Lists a Product on Mercado Livre */
    public function updateItem($id)
    {
        // Get current credentials for Meli
        $meli = $this->meli;
        // Prepare data for Request
        $product = Product::find($id);

        if(!$product->mercadolivre_name) {
            return redirect()->route('admin-prod-index')->with('error', 'Nome para o Mercado Livre não encontrado. Por favor cadastre um nome..');
        }

        if(!$this->productExists($product)) {
            // Update existing Meli product instead of create a new one.
            // return error for now
            return redirect()->route('admin-prod-index')->with('error', __('The Product does not exist on Mercado Livre!'));
        }

        $url = config('mercadolivre.api_base_url') . "items/".$product->mercadolivre_id;
        
        $headers = [
            "Authorization: Bearer ". $meli->access_token,
        ];
        
        $category = $this->searchCategory($product);
        $data = FacadesMercadoLivre::productUpdateBusinessLogic($product, $category);

        $resp = FacadesMercadoLivre::curlPut($url, $headers, $data);

        // if errors are thrown
        if(array_key_exists('status', $resp) && $resp['status'] == 400) {
            $errors = [];
            foreach($resp['cause'] as $key => $error) {
                array_push($errors, $error->message);
            }
            return redirect()->route('admin-prod-index')->with('errors', $errors);
        }

        if(array_key_exists('status', $resp) && $resp['status'] == 403) {
            if ($resp['message'] == "seller.unable_to_list"){
                $specific_errors = [];
                foreach($resp['cause'] as $key => $error) {
                    array_push($specific_errors, $error);
                }
                $all_errors = implode(",",$specific_errors);
                $errors[0] = "As seguintes informações estão faltando nas configurações do usuário no Mercado Livre: " . $all_errors;
                return redirect()->route('admin-prod-index')->with('errors', $errors);
            }
            if($resp['message'] == "access_token.invalid"){
                return redirect()->route('admin-prod-index')->with('error', 'Chaves incorretas ou faltando, por favor verifique suas chaves do mercado livre.');
            }
        }

        if(array_key_exists('status', $resp) && $resp['status'] == 401) {
            if ($resp['message'] == "expired_token"){
                return redirect()->route('admin-prod-index')->with('error', 'Chaves do mercado livre estão expiradas');
            }
        }

        if($resp['id']) {
            $product->mercadolivre_id = $resp['id'];
            $product->update();
        }

        // Create Product Description after creating the product listing
        $this->createDescription($product);

        return redirect()->route('admin-prod-index')->with('success', 'Produto atualizado com sucesso no Mercado Livre - ID: ' . $resp['id']);
    }

    /* Checks if given Product is already listed on Mercado Livre */
    public function productExists(Product $product): bool
    {
        // Get current credentials for Meli
        $meli = $this->meli;

        $url = config('mercadolivre.api_base_url') . "items/" . $product->mercadolivre_id;

        $headers = [
            "Authorization: Bearer ". $meli->access_token,
        ];

        $resp = FacadesMercadoLivre::curlGet($url, $headers);

        if(array_key_exists('error', $resp)) {
            return false;
        }
        return true;
    }

    /*
    * Search Meli product category since a product is given.
    */
    public function searchCategory(Product $product)
    {
        // Get current credentials for Meli
        $meli = $this->meli;
        // Prepare data for Request
        $url = config('mercadolivre.api_base_url') . "sites/MLB/domain_discovery/search?limit=1&q=" . urlencode($product->mercadolivre_name);

        $headers = [
            "Authorization: Bearer ". $meli->access_token
        ];

        $resp = FacadesMercadoLivre::curlGet($url, $headers);

        $this->category_id = null;

        foreach($resp as $category) 
        {
            $this->category_id = $category->category_id;
            break;
        }

        return [
            'category_id' => $this->category_id,
        ];
    }

    /* Creates product description after Product is posted */
    public function createDescription(Product $product)
    {
        // Get current credentials for Meli
        $meli = $this->meli;

        $url = config('mercadolivre.api_base_url') . "items/".$product->mercadolivre_id."/description";
        
        $headers = [
            "Authorization: Bearer ". $meli->access_token,
        ];
        
        $data = [
            'plain_text' => $product->mercadolivre_description
        ];

        $resp = FacadesMercadoLivre::curlPost($url, $headers, $data);

        return true;
    }
    
}
