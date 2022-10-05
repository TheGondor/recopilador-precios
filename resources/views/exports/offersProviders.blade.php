<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>DESCRIPCIÓN</th>
        <th>Precio Normal</th>
        <th>{{ $provider->name}}</th>
        @foreach ($offersProviders as $offerProvider)
        <th>{{ $offerProvider->name}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($offersProduct as $i => $product)
        <tr style="background-color: #7ee9f2;">
            <td>{{ substr($product->product_id, 2) }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->providerPrice($provider) }}</td>
            @foreach ($offersProviders as $offerProvider)
            @if ($product->offersProviders()->contains('id', $offerProvider->id))
                <td>{{$product->offersProviders()->firstWhere('id', $offerProvider->id)->special }}</td>
            @else
                <td></td>
            @endif
            @endforeach

        </tr>
    @endforeach
    </tbody>
</table>
