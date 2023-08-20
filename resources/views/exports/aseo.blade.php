<table>
    <thead>
    <tr>
        <th>ID General</th>
        <th>ID por Region</th>
        <th>Nombre</th>
        <th>Precio Normal</th>
        <th>Precio Oferta</th>
        <th>Precio Proveedor</th>
        <th>Precio Oferta Proveedor</th>
        <th>Estado</th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $i => $product)
        <tr style="background-color: #7ee9f2;">
            <td>{{ substr($product->product_id, 2) }}</td>
            <td>{{ substr($product->region_id, 2) }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->special == 0 ? 'Sin oferta' : $product->special  }}</td>
            <td>{{ $product->provider_price }}</td>
            <td>{{ $product->provider_special == 0 ? 'Sin oferta' : $product->provider_special  }}</td>
            @if ($product->provider_status == 1)
            <td>Stock</td>
            @endif
            @if ($product->provider_status == 2)
            <td>Sin Stock</td>
            @endif
            @if ($product->provider_status == 3)
            <td>Fuera dispersión</td>
            @endif
            @if ($product->provider_status == 4)
            <td>Sin Stock, fuera dispersión</td>
            @endif

        </tr>
    @endforeach
    </tbody>
</table>
