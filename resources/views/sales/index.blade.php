<x-app-layout>
<div class="flex h-screen bg-gray-100">
        <!-- PRODUCTS -->
        <div class="w-3/4 p-6 overflow-y-auto">
                
                <div class="flex gap-3 mb-4">
            <input type="text"
                id="search"
                placeholder="Search product..."
                onkeyup="filterProducts()"
                class="border px-4 py-2 w-full rounded">

            <select id="productTypeFilter"
                onchange="filterProducts()"
                class="border px-4 py-2 rounded">

                <option value="">All Categories</option>

                @foreach($productTypes as $type)
                    <option value="{{ $type->id }}">
                        {{ $type->name }}
                    </option>
                @endforeach

            </select>
        </div>

        <div class="grid grid-cols-4 gap-4">
            @foreach($products as $product)
            <div class="product-card bg-white p-4 rounded shadow cursor-pointer"
                data-name="{{ strtolower($product->name) }}"
                data-type="{{ $product->product_type_id }}"
                onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->sell_price }})">

                <img src="{{ asset('storage/products/'.$product->image) }}"
                    class="h-24 w-full object-contain" alt="No Image">

                <h3 class="font-semibold">{{ $product->name }}</h3>
                <p class="text-green-600">${{ $product->sell_price }}</p>
                <p class="text-red-600">Qty: {{ $product->quantity }}</p>
                <button type="button" class="bg-teal-500 text-white w-full mt-2 py-1 rounded"
                    onclick="event.stopPropagation(); addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->sell_price }})">
                    Add
                </button>
            </div>
            @endforeach
        </div>
    </div>
    <!-- CART -->
    <div class="w-1/4 bg-white p-4 border-l">
        <h2 class="font-bold text-lg mb-3">Cart</h2>
        <!-- CUSTOMER SELECT -->
        <select id="customer" class="w-full mb-3 border p-2" onchange="updateDiscount()">
            <option value="" data-discount="0">Select Customer</option>
            @foreach($customers as $c)
            <option value="{{ $c->id }}"
                data-discount="{{ $c->customer_type->discount_rate ?? 0 }}">
                {{ $c->name }}
                @if($c->customer_type->discount_rate)
                    ({{ $c->customer_type->discount_rate }}%)
                @endif
            </option>
            @endforeach
        </select>
        <!-- CART TABLE -->
        <table class="w-full text-sm">
            <tbody id="cart-items"></tbody>
        </table>
        <!-- TOTAL -->
        <div class="mt-4">
            <p>Subtotal: <span id="subtotal">$0.00</span></p>
            <p>Discount: <span id="discount">0%</span></p>
            <p class="font-bold">Total: <span id="total">$0.00</span></p>
        </div>

        <!-- CHECKOUT -->
        <button onclick="checkout()"
            class="bg-green-600 text-white w-full py-2 mt-4 rounded">
            Checkout
        </button>

    </div>

</div>

<script>
let cart = [];
let discountPercent = 0;
// ADD TO CART
function addToCart(id, name, price){
    let item = cart.find(p => p.id === id)
    if(item){
        item.qty++
    } else {
        cart.push({id, name, price, qty: 1})
    }
    renderCart()
}
// RENDER CART
function renderCart(){
    let html = ''
    let subtotal = 0
    cart.forEach((item, index) => {
        let itemTotal = item.qty * item.price
        subtotal += itemTotal

        html += `
        <tr class="border-b">
            <td>${item.name}</td>
            <td>${item.qty}</td>
            <td>$${itemTotal.toFixed(2)}</td>
            <td class="text-red-500 cursor-pointer" onclick="removeItem(${index})">❌</td>
        </tr>
        `
    })
    document.getElementById('cart-items').innerHTML = html
    document.getElementById('subtotal').innerText = '$' + subtotal.toFixed(2)

    //Calculate
    let discountAmount = subtotal * discountPercent / 100
    let total = subtotal - discountAmount

    document.getElementById('discount').innerText = discountPercent + '%'
    document.getElementById('total').innerText = '$' + total.toFixed(2)
}
// REMOVE ITEM
function removeItem(i){
    cart.splice(i, 1)
    renderCart()
}
// SEARCH PRODUCT
function filterProducts() {

    let keyword = document.getElementById('search').value.toLowerCase();
    let productType = document.getElementById('productTypeFilter').value;

    let cards = document.querySelectorAll('.product-card');

    cards.forEach(card => {

        let name = card.dataset.name;
        let type = card.dataset.type;

        let matchName = name.includes(keyword);

        let matchType =
            productType === '' ||
            type === productType;

        card.style.display =
            (matchName && matchType)
            ? ''
            : 'none';
    });
}
// UPDATE DISCOUNT FROM CUSTOMER
function updateDiscount(){
    let select = document.getElementById('customer')
    let selected = select.options[select.selectedIndex]
    discountPercent = parseFloat(selected.dataset.discount || 0)
    renderCart()
}

// CHECKOUT
function checkout(){
    if(cart.length === 0){
        alert("Cart is empty!")
        return
    }
    if(!document.getElementById('customer').value){
        alert("Please select a customer!")
        return
    }
    fetch("{{ route('pos.checkout') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            customer_id: document.getElementById('customer').value,
            items: cart,
            memo: "POS Sale"
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            alert("Invoice #" + data.invoice_id + " Completed")
            cart = []
            discountPercent = 0
            renderCart()
            window.location.href = '/invoice/' + data.invoice_id + '/thermal'
        } else {
            alert("Checkout failed: " + (data.message || "Unknown error"))
        }
    })
    .catch(err => {
        console.error(err)
        alert("Checkout error!")
    })
}
</script>

</x-app-layout>