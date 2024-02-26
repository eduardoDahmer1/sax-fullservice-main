<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ __("Products list") }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
        }

        table {
            width: 100%;
            max-width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            line-height: 1.42857143;
        }

        th {
            text-align: left;
            font-size: 10pt
        }

        .float-right {
            float: right;
        }

        .float-left {
            float: left;
        }

        .titulo {
            font-size: 10t;
        }
    </style>
</head>

<body>
    <div>
        <div class="float-left titulo">
            {{$admstore->title}} | {{$admstore->domain}}
        </div>
        <div class="float-right titulo">
            {{ __("Products list") }} | {{ date('d-m-Y') }}
        </div>
    </div>
    <br><br><br>
    <div>
        <table style="font-size: 10px;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __("SKU") }}</th>
                    <th>{{ __("Name") }}</th>
                    <th>Foto</th>
                    <th>{{ __("Price") . ' ' . $currency->sign}}</th>
                    <th>{{ __("Brand") }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $index => $product)
                <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->name }}</td>
                    <td> <img width="100px"
                            src="{{ ($product->photo) ? asset('storage/images/products/'.$product->photo) : asset('assets/images/noimage.png') }}"
                            alt=""> </td>
                    <td>{{
                        number_format(
                        $product->price * $currency->value,
                        $currency->decimal_digits,
                        $currency->decimal_separator,
                        $currency->thousands_separator)
                        }}
                    </td>
                    <td>{{ $product->brand->name != __("Deleted") ? $product->brand->name : '' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
