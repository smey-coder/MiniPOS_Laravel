<x-app-layout>

<x-slot name="header">
<div class="flex justify-between items-center">

<h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200">
POS / Sales
</h2>

</div>
</x-slot>

<div class="py-6">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

<div class="grid grid-cols-12 gap-6">

<!-- PRODUCTS AREA -->
<div class="col-span-8">

<!-- Search -->
<div class="mb-4">
<input
type="text"
placeholder="Search product..."
class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
</div>

<!-- Product Grid -->
<div class="grid grid-cols-4 gap-4">

{{-- @foreach($products as $product) --}}

<div
class="bg-white rounded-lg shadow hover:shadow-lg cursor-pointer p-3"
onclick="addToCart({{ $product->id }},'{{ $product->name }}',{{ $product->sell_price }})">

<img
src="{{ asset('storage/'.$product->image) }}"
class="h-24 w-full object-contain mb-2">

<h3 class="text-sm font-semibold">
{{ $product->name }}
</h3>

<p class="text-green-600 font-bold">
${{ $product->sell_price }}
</p>

</div>

@endforeach

</div>

</div>


<!-- CART AREA -->
<div class="col-span-4">

<div class="bg-white rounded-lg shadow p-4">

<h3 class="font-semibold mb-4">
Cart
</h3>

<table class="w-full text-sm">

<thead>
<tr>
<th>Product</th>
<th>Qty</th>
<th>Price</th>
<th></th>
</tr>
</thead>

<tbody id="cart-items">

</tbody>

</table>

<hr class="my-4">

<!-- Customer -->
<div class="mb-3">

<select class="w-full border rounded-lg p-2">
<option>Select Customer</option>

@foreach($customers as $customer)

<option value="{{ $customer->id }}">
{{ $customer->name }}
</option>

@endforeach

</select>

</div>

<!-- Total -->
<div class="flex justify-between text-lg font-bold mb-4">

<span>Total</span>

<span id="total">
$0
</span>

</div>

<!-- Checkout -->
<button
class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg">

Checkout

</button>

</div>

</div>

</div>

</div>
</div>

<script>

let cart = []

function addToCart(id,name,price){

let found = cart.find(p => p.id === id)

if(found){
found.qty++
}else{
cart.push({
id:id,
name:name,
price:price,
qty:1
})
}

renderCart()

}

function renderCart(){

let html=''
let total=0

cart.forEach((item,index)=>{

total += item.qty * item.price

html += `
<tr>
<td>${item.name}</td>
<td>
<button onclick="changeQty(${index},-1)">-</button>
${item.qty}
<button onclick="changeQty(${index},1)">+</button>
</td>
<td>$${item.price}</td>
<td>
<button onclick="removeItem(${index})">x</button>
</td>
</tr>
`

})

document.getElementById('cart-items').innerHTML = html
document.getElementById('total').innerText = '$'+total

}

function changeQty(index,value){

cart[index].qty += value

if(cart[index].qty <=0){
cart.splice(index,1)
}

renderCart()

}

function removeItem(index){

cart.splice(index,1)
renderCart()

}

</script>

</x-app-layout>
```
