<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$owner->name}} - {{__('Wedding List')}}</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        a {
            text-decoration: none;
        }

        table {
            width: 100%;
        }

        th {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        td {
            padding-right: .5rem; 
            padding-left: .5rem; 
        }
    </style>
</head>
<body>
    {{now()->format('d/m/Y h:i:s')}}
    <table>
        <thead>
            <tr>
                <th style="width: 20%">{{__('Photo')}}</th>
                <th style="width: 60%">{{__('Name')}}</th>
                <th style="width: 20%">{{__('Brand')}}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td style="width: 20%;text-align: center;">
                        <img src="{{ $product->getAttributes()['photo']
                            ? public_path('storage/images/products/'. $product->getAttributes()['photo']) 
                            : public_path('assets/images/noimage.png') }}" width="100px"
                        >
                    </td>
                    <td style="width: 60%">
                        <a href="{{route('front.product', $product->slug)}}" target="_blank">
                            {{$product->name}}
                        </a>
                    </td>
                    <td style="width: 20%">
                        <h4>{{$product->brand->name}}</h4>
                    </td>
                </tr>
            @empty
                <h1>
                    {{__('No products added')}}
                </h1>
            @endforelse
        </tbody>
    </table>
</body>
</html>