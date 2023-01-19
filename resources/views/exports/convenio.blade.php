<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Precio Normal</th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $i => $product)
        <tr style="background-color: #7ee9f2;">
            <td>{{ substr($product->product_id, 2) }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->price }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
