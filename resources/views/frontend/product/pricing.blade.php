@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>{{ $product->name }}</h1>
            <p class="text-muted">{{ $product->short }}</p>
            
            <!-- Base Price -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Pricing Information</h5>
                    <p class="card-text">
                        <strong>Base Price:</strong> ${{ number_format($product->price, 2) }}
                    </p>
                    
                    @if($product->strike_price > 0)
                        <p class="card-text">
                            <strong>Strike Price:</strong> 
                            <span class="text-decoration-line-through">${{ number_format($product->strike_price, 2) }}</span>
                        </p>
                    @endif
                </div>
            </div>
            
            <!-- Tiered Pricing -->
            @if(!empty($priceTiers))
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Volume Pricing</h5>
                    <p class="text-muted small">Buy more and save! Prices automatically adjust based on quantity.</p>
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Minimum Quantity</th>
                                    <th>Price Per Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($priceTiers as $tier)
                                <tr>
                                    <td>{{ $tier['min_quantity'] }}+ units</td>
                                    <td>${{ $tier['price'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Price Calculator -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Price Calculator</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="quantity" class="form-label">Quantity:</label>
                            <input type="number" id="quantity" class="form-control" value="1" min="1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Total Price:</label>
                            <h4 id="total-price" class="text-primary">${{ number_format($product->price, 2) }}</h4>
                            <small id="unit-price" class="text-muted">(${{ number_format($product->price, 2) }} per unit)</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Example Prices -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Example Orders</h5>
                    <div class="row">
                        @foreach($examplePrices as $qty => $total)
                        <div class="col-md-3 text-center mb-2">
                            <div class="border rounded p-2">
                                <strong>{{ $qty }} units</strong><br>
                                <span class="text-success">${{ number_format($total, 2) }}</span><br>
                                <small class="text-muted">
                                    (${{ number_format($total / $qty, 2) }} each)
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Product Image -->
            @if($product->image)
            <img src="{{ $product->image }}" class="img-fluid mb-3" alt="{{ $product->name }}">
            @endif
            
            <!-- Product Details -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Product Details</h5>
                    <p><strong>Code:</strong> {{ $product->code }}</p>
                    <p><strong>Brand:</strong> {{ $product->brand->name ?? 'N/A' }}</p>
                    <p><strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}</p>
                    
                    @if($product->stock)
                    <p><span class="badge bg-success">In Stock</span></p>
                    @else
                    <p><span class="badge bg-danger">Out of Stock</span></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Dynamic price calculator
document.getElementById('quantity').addEventListener('input', function() {
    const quantity = parseInt(this.value) || 1;
    const productId = {{ $product->id }};
    
    fetch(`/api/products/${productId}/price?quantity=${quantity}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-price').textContent = data.formatted_total_price;
            document.getElementById('unit-price').textContent = `(${data.formatted_unit_price} per unit)`;
        })
        .catch(error => {
            console.error('Error fetching price:', error);
        });
});
</script>
@endsection
