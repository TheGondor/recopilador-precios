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
            <td>{{ $product->providerPriceAseo($provider, $region) }}</td>
            @foreach ($offersProviders as $offerProvider)
            @if ($product->offersProvidersAseo($region)->contains('id', $offerProvider->id))
                <td>{{$product->offersProvidersAseo($region)->firstWhere('id', $offerProvider->id)->special }}</td>
            @else
                <td></td>
            @endif
            @endforeach

        </tr>
    @endforeach
    </tbody>
</table>
