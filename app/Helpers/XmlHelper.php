<?php

namespace App\Helpers;

use DOMDocument;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Language;
use App\Models\Subcategory;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use App\Models\ProductTranslation;
use Illuminate\Support\Facades\DB;
use App\Models\CategoryTranslation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Models\SubcategoryTranslation;

class XmlHelper
{
    public static function importProductXml(Command $command, $file_name)
    {
        $file_path = storage_path("app/public/xml/" . $file_name);
        if (!File::exists($file_path)) {
            $command->info("Arquivo não encontrado");
            return 1;
        }

        $content = File::get($file_path);
        try {
            $collection = collect(xml_decode($content)["Item"]);
        } catch (\Exception $e) {
            $command->info("Erro ao ler aquivo");
            Log::error($e->getMessage());
            return 1;
        }

        $command->info("Importando produtos");

        DB::transaction(function () use ($collection, $command) {
            $languages = Language::all();

            $progress = $command->output->createProgressBar($collection->count());

            $product_translations = [];
            $products = [];

            $category_translations = [];
            $subcategory_translations = [];

            foreach ($collection->chunk(500) as $items) {
                foreach ($items as $item) {
                    $brand = Brand::where("name", "LIKE", $item['Marca'])->first();
                    if (!$brand) {
                        $brand = new Brand;
                        $brand->name = $item['Marca'];
                        $brand->slug = Str::slug($item['Marca']);
                        $brand->save();
                    }

                    $category = Category::where("slug", "LIKE", Str::slug($item['Categoria']))->first();
                    if (!$category) {
                        $category = Category::create([
                            "slug" => Str::slug($item['Categoria'])
                        ]);
                    }

                    $subcategory = Subcategory::where("slug", "LIKE", Str::slug($item['SubCategoria']))->first();
                    if (!$subcategory) {
                        $subcategory = Subcategory::create([
                            'slug' => Str::slug($item['SubCategoria']),
                            'category_id' => $category->id
                        ]);
                    }

                    foreach ($languages as $language) {
                        $product_translation['locale'] = $language->locale;
                        $product_translation['name'] = $item['Nome'];
                        $product_translation["product_id"] = $item['Codigo'];
                        $product_translation["details"] = $item['Descricao'];

                        $category_translation["locale"] = $language->locale;
                        $category_translation["name"] = $item['Categoria'];
                        $category_translation["category_id"] = $category->id;

                        $subcategory_translation["locale"] = $language->locale;
                        $subcategory_translation["name"] = $item['SubCategoria'];
                        $subcategory_translation["subcategory_id"] = $subcategory->id;

                        $product_translations[] = $product_translation;
                        $category_translations[] = $category_translation;
                        $subcategory_translations[] = $subcategory_translation;
                    }

                    $product = new Product;
                    $product->id = $item['Codigo'];
                    $product->stores()->sync([1]);

                    $product = [];
                    $product['id'] = $item['Codigo'];
                    $product['sku'] = $item['Codigo'];
                    $product['ref_code'] = $item['Codigo'];
                    $product['ref_code_int'] = $item['Codigo'];
                    $product['external_name'] = $item['Nome'];
                    $product['slug'] = Str::slug($item['Nome']);
                    $product['price'] = $item['Valor'];
                    $product['stock'] = $item['Estoque'];
                    $product['category_id'] = $category->id;
                    $product['subcategory_id'] = $subcategory->id;
                    $product['brand_id'] = $brand->id;

                    $products[] = $product;

                    $progress->advance();
                }
            }

            $command->info("\nSalvando produtos");
            Product::upsert($products, ["id"]);

            $command->info("Salvando traduções");
            ProductTranslation::upsert($product_translations, ['id']);
            CategoryTranslation::upsert($category_translations, ['id']);
            SubcategoryTranslation::upsert($subcategory_translations, ['id']);

            $progress->finish();
            $command->info("Produtos importados com sucesso");
        });
    }

    public static function exportOrderXml(Command $command, int $total): int
    {
        $orders = Order::query()->latest()->take($total)->get();

        if (!$orders) {
            $command->info("No orders found.");
            return 1;
        }

        $command->info("Exporting the last {$total} orders");

        $progress = $command->output->createProgressBar($orders->count());

        $xml = new DOMDocument("1.0", "UTF-8");

        $pedidos = $xml->createElement('Pedidos');

        $orders->each(function (Order $order) use ($xml, $pedidos, $progress) {
            $item = $xml->createElement('Item');
            $detalhes = $xml->createElement('Detalhes');

            $numeroFatura = $xml->createElement('NumeroFatura', sprintf("%'.08d", $order->id));
            $data = $xml->createElement('Data', $order->created_at->format('Y-m-d h:i:s'));
            $idPedido = $xml->createElement('IdPedido', $order->order_number);
            $envio = $xml->createElement('Envio', $order->shipping == 3 ? 'Retirar no local' : 'Enviar para o endereço');
            $tipoEnvio = $xml->createElement('TipoEnvio', $order->shipping_type);
            if($order->shipping == 3) {
                $locadRetirada = $xml->createElement('LocalRetirada', $order->pickup_location);
            }
            $tipoEmbalagem = $xml->createElement('TipoEmbalagem', $order->packing_type);
            $metodoPagamento = $xml->createElement('MetodoPagamento', $order->method);

            $detalhes->append($numeroFatura);
            $detalhes->append($data);
            $detalhes->append($idPedido);
            $detalhes->append($envio);
            $detalhes->append($tipoEnvio);
            if($order->shipping == 3) {
                $detalhes->append($locadRetirada);
            }
            $detalhes->append($tipoEmbalagem);
            $detalhes->append($metodoPagamento);

            $cliente = $xml->createElement('Cliente');

            $nome = $xml->createElement('Nome', $order->customer_name);
            $email = $xml->createElement('Email', $order->customer_email);
            $cpfCnpj = $xml->createElement('CpfCnpj', $order->customer_document);
            $telefone = $xml->createElement('Telefone', $order->customer_phone);

            $endereco = $xml->createElement('Endereco');

            $rua = $xml->createElement('Rua', $order->customer_address);
            $numero = $xml->createElement('Numero', $order->customer_address_number);
            $complemento = $xml->createElement('Complemento', $order->customer_complement);
            $bairro = $xml->createElement('Bairro', $order->customer_district);
            $cidade = $xml->createElement('Cidade', $order->customer_city);
            $estado = $xml->createElement('Estado', $order->customer_state);
            $pais = $xml->createElement('Pais', $order->customer_country);
            $cep = $xml->createElement('Cep', $order->customer_zip);

            $endereco->append($rua);
            $endereco->append($numero);
            $endereco->append($complemento);
            $endereco->append($bairro);
            $endereco->append($cidade);
            $endereco->append($estado);
            $endereco->append($pais);
            $endereco->append($cep);

            $cliente->append($nome);
            $cliente->append($email);
            $cliente->append($cpfCnpj);
            $cliente->append($telefone);
            $cliente->append($endereco);

            $carrinho = $xml->createElement('Carrinho');

            $total = $xml->createElement('Total', number_format($order->cart['totalPrice'], 2, ',', '.'));
            $desconto = $xml->createElement('Desconto', number_format($order->coupon_discount, 2, ',', '.'));

            $produtos = $xml->createElement('Produtos');

            collect($order->cart['items'])->each(function ($product) use ($produtos, $xml) {
                $item = $xml->createElement('Item');

                $codigo = $xml->createElement('Codigo', $product['item']['sku']);
                $nome = $xml->createElement('Nome', $product['item']['name']);
                $quantidade = $xml->createElement('Quantidade', $product['qty']);
                $valorUnitario = $xml->createElement('ValorUnitario', number_format($product['item']['price'], 2, ',', '.'));
                $valorTotal = $xml->createElement('ValorTotal', number_format($product['item']['price'] * $product['qty'], 2, ',', '.'));

                $item->append($codigo);
                $item->append($nome);
                $item->append($quantidade);
                $item->append($valorUnitario);
                $item->append($valorTotal);

                $produtos->append($item);
            });

            $carrinho->append($total);
            $carrinho->append($desconto);
            $carrinho->append($produtos);

            $item->append($detalhes);
            $item->append($cliente);
            $item->append($carrinho);

            $pedidos->append($item);
            $progress->advance();
        });

        $xml->append($pedidos);

        $xml->save(storage_path('app/public/xml/OrdersExport.xml'));

        $command->info("\nOrders exported successfuly and available at " . asset('storage/xml/OrdersExport.xml'));

        return 0;
    }
}
