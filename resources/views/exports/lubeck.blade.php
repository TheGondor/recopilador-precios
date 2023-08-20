<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Precio Normal</th>
        <th>Precio Oferta</th>
        <th>Precio Venture</th>
        <th>Precio Oferta Venture</th>
        <th>Precio Lubeck</th>
        <th>Precio Oferta Lubeck</th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $i => $product)
        <tr style="background-color: #7ee9f2;">
            <td>{{ substr($product->product_id, 2) }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->special == 0 ? 'Sin oferta' : $product->special  }}</td>
            <td>{{ $product->provider_price }}</td>
            <td>{{ $product->provider_special == 0 ? 'Sin oferta' : $product->provider_special  }}</td>
            <td>{{ $product->lubeck_price }}</td>
            <td>{{ $product->lubeck_special == 0 ? 'Sin oferta' : $product->lubeck_special  }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
