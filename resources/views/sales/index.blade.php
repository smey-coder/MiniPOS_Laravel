<x-app-layout>
    <div class="flex h-screen bg-gray-100">

    <!-- PRODUCTS -->
    <div class="w-3/4 p-6 overflow-y-auto">

    <input type="text" id="search"
        placeholder="Search product..."
        onkeyup="searchProduct()"
        class="border px-4 py-2 mb-4 w-full rounded">

    <div class="grid grid-cols-4 gap-4">

    @foreach($products as $product)
    <div class="product-card bg-white p-4 rounded shadow cursor-pointer"
        data-name="{{ $product->name }}"
        onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->sell_price }})">

        <img src="{{ asset('storage/products/'.$product->image) }}"
            class="h-24 w-full object-contain" alt="No Image">
        <h3>{{ $product->name }}</h3>
        <p class="text-green-600">${{ $product->sell_price }}</p>

        <button class="bg-teal-500 text-white w-full mt-2 py-1 rounded">
            Add
        </button>
    </div>
    @endforeach

    </div>

    </div>

    <!-- CART -->
    <div class="w-1/4 bg-white p-4 border-l">

    <h2 class="font-bold text-lg mb-3">Cart</h2>

    <select id="customer" class="w-full mb-3 border p-2">
    <option value="">Select Customer</option>
    @foreach($customers as $c)
    <option value="{{ $c->id }}">{{ $c->name }}</option>
    @endforeach
    </select>

    <table class="w-full text-sm">
    <tbody id="cart-items"></tbody>
    </table>

    <div class="mt-4">
    <p>Subtotal: <span id="subtotal">$0</span></p>
    <p>Total: <span id="total">$0</span></p>
    </div>

    <button onclick="checkout()"
        class="bg-green-600 text-white w-full py-2 mt-4 rounded">
        Checkout
    </button>

    </div>

    </div>

    <script>

    let cart = []

    function addToCart(id,name,price){
        let item = cart.find(p => p.id === id)

        if(item){
            item.qty++
        }else{
            cart.push({id,name,price,qty:1})
        }

        renderCart()
    }

    function renderCart(){
        let html=''
        let total=0

        cart.forEach((item,index)=>{

            let itemTotal = item.qty * item.price
            total += itemTotal

            html += `
            <tr>
            <td>${item.name}</td>
            <td>${item.qty}</td>
            <td>$${itemTotal}</td>
            <td onclick="removeItem(${index})">❌</td>
            </tr>
            `
        })

        document.getElementById('cart-items').innerHTML = html
        document.getElementById('subtotal').innerText = '$'+total
        document.getElementById('total').innerText = '$'+total
    }

    function removeItem(i){
        cart.splice(i,1)
        renderCart()
    }

    function searchProduct(){
        let input = document.getElementById('search').value.toLowerCase()
        let cards = document.querySelectorAll('.product-card')

        cards.forEach(card=>{
            let name = card.dataset.name.toLowerCase()
            card.style.display = name.includes(input) ? '' : 'none'
        })
    }

    function checkout(){

        if(cart.length === 0){
            alert("Cart is empty!")
            return
        }

        if(!document.getElementById('customer').value){
            alert("Please select a customer!")
            return
        }

        fetch("{{ route('pos.checkout') }}",{
            method:'POST',
            headers:{
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
            },
            body: JSON.stringify({
                customer_id: document.getElementById('customer').value,
                items: cart,
                memo: "POS Sale"
            })
        })
        .then(res=>res.json())
        .then(data=>{
            if(data.success){
                alert("Invoice #" + data.invoice_id + " Completed")
                cart=[]
                renderCart()
                // window.open('/invoice/'+data.invoice_id)
                window.location.href = '/invoice/' + data.invoice_id + '/thermal';
            }else{
                alert("Checkout failed: " + (data.message || "Unknown error"))
            }
        })
        .catch(error=>{
            console.error('Checkout error:', error)
            alert("Checkout failed. Please check console for details.")
        })
    }
    </script>
</x-app-layout>