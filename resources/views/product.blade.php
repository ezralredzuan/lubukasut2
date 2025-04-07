<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>Shopping Cart</title>
    <style>
        .flying-item {
            position: absolute;
            transition: transform 0.5s ease-in-out, opacity 0.5s ease-in-out;
            z-index: 100;
        }
    </style>
</head>
<body class="bg-gray-100 relative" x-data="cartStore()">

    @include('filament.components.header')

    <div class="text-center py-6">
        <h1 class="text-5xl py-7 font-bold text-gray-800">All Products</h1>
    </div>

    <!-- Button to Open Cart -->
    <button @click="openCart"
        class="fixed top-36 right-6 px-6 py-3 bg-red-600 text-white font-bold rounded-full shadow-lg hover:bg-red-700 transition duration-300 z-50">
    Cart (<span x-text="cart.length"></span>)
    </button>

    <!-- Overlay and Cart Slideover -->
    <div x-show="isCartOpen" class="fixed inset-0 z-50 flex justify-end" @click.outside="closeCart">
        <div class="absolute inset-0 bg-black bg-opacity-50" @click="closeCart"></div>
        <div class="bg-white w-80 h-full shadow-lg p-4 flex flex-col relative z-10 transform transition-transform duration-300 ease-in-out"
             x-show="isCartOpen"
             x-transition:enter="translate-x-full"
             x-transition:leave="translate-x-full"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">

            <!-- Close Button -->
            <button @click="closeCart" class="text-gray-600 hover:text-gray-900 self-end text-2xl">&times;</button>

            <!-- Cart Title -->
            <h2 class="text-xl font-bold">Cart</h2>
            <p class="text-gray-500">Item</p>
            <hr class="my-2">

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto">
                <template x-for="(item, index) in cart" :key="index">
                    <div class="flex items-center justify-between py-2 border-b">
                        <img :src="item.image" alt="Product Image" class="w-12 h-12 object-cover rounded">
                        <div class="flex-1 ml-2">
                            <span class="block" x-text="item.name"></span>
                            <span class="text-gray-500">RM <span x-text="item.price.toFixed(2)"></span></span>
                        </div>
                        <button @click="removeFromCart(index)" class="text-red-500 text-lg">&times;</button>
                    </div>
                </template>
            </div>

            <hr class="my-2">

            <!-- Total Price -->
            <div class="flex justify-between font-bold text-lg mb-3">
                <span>Total:</span>
                <span>RM <span x-text="cartTotal.toFixed(2)"></span></span>
            </div>

            <!-- Checkout Button -->
            <form x-ref="cartForm" action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <input type="hidden" name="cart_items" x-model="JSON.stringify(cart)">
                <button type="submit"
                class="w-full px-6 py-3 bg-white text-red-600 border border-red-600 font-bold rounded-full shadow hover:bg-red-50 transition duration-300">
                    Proceed to Checkout
                </button>
            </form>

        </div>
    </div>

    <div class="mt-1 w-70 px-12 py-1 flex">
        <!-- Filter Sidebar -->
        <div class="w-1/4 bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-4">Filters</h3>



            <!-- Search -->
            <form action="{{ route('products.index') }}" method="GET">
                <div class="mb-4">
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" name="search" id="search" class="w-full p-2 border rounded" placeholder="Search products" value="{{ request()->search }}">
                </div>

                <!-- Price Range -->
                <div class="mb-4">
                    <label for="price" class="block text-sm font-medium text-gray-700">Price Range</label>
                    <div class="flex space-x-2">
                        <input type="number" name="min_price" id="min_price" class="w-1/2 p-2 border rounded" placeholder="Min Price" value="{{ request()->min_price }}">
                        <input type="number" name="max_price" id="max_price" class="w-1/2 p-2 border rounded" placeholder="Max Price" value="{{ request()->max_price }}">
                    </div>
                </div>

                <!-- Size Filter -->
                <div class="mb-4">
                    <label for="size" class="block text-sm font-medium text-gray-700">Size</label>
                    <select name="size" id="size" class="w-full p-2 border rounded">
                        <option value="all">All Sizes</option>
                        <option value="3" {{ request()->size == '3' ? 'selected' : '' }}>3</option>
                        <option value="3.5" {{ request()->size == '3.5' ? 'selected' : '' }}>3.5</option>
                        <option value="4" {{ request()->size == '4' ? 'selected' : '' }}>4</option>
                        <option value="4.5" {{ request()->size == '4.5' ? 'selected' : '' }}>4.5</option>
                        <option value="5" {{ request()->size == '5' ? 'selected' : '' }}>5</option>
                        <option value="5.5" {{ request()->size == '5.5' ? 'selected' : '' }}>5.5</option>
                        <option value="6" {{ request()->size == '6' ? 'selected' : '' }}>6</option>
                        <option value="6.5" {{ request()->size == '6.5' ? 'selected' : '' }}>6.5</option>
                        <option value="7" {{ request()->size == '7' ? 'selected' : '' }}>7</option>
                        <option value="7.5" {{ request()->size == '7.5' ? 'selected' : '' }}>7.5</option>
                        <option value="8" {{ request()->size == '8' ? 'selected' : '' }}>8</option>
                        <option value="8.5" {{ request()->size == '8.5' ? 'selected' : '' }}>8.5</option>
                        <option value="9" {{ request()->size == '9' ? 'selected' : '' }}>9</option>
                        <option value="9.5" {{ request()->size == '9.5' ? 'selected' : '' }}>9.5</option>
                        <option value="10" {{ request()->size == '10' ? 'selected' : '' }}>10</option>
                        <option value="10.5" {{ request()->size == '10.5' ? 'selected' : '' }}>10.5</option>
                        <option value="11" {{ request()->size == '11' ? 'selected' : '' }}>11</option>
                        <option value="11.5" {{ request()->size == '11.5' ? 'selected' : '' }}>11.5</option>
                        <option value="12" {{ request()->size == '12' ? 'selected' : '' }}>12</option>
                    </select>
                </div>

                <!-- Brand Filter -->
                <div class="mb-4">
                    <label for="brand" class="block text-sm font-medium text-gray-700">Brand</label>
                    <select name="brand" id="brand" class="w-full p-2 border rounded">
                        <option value="all">All Brands</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request()->brand == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Gender Filter -->
                <div class="mb-4">
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                    <select name="gender" id="gender" class="w-full p-2 border rounded">
                        <option value="all">All Genders</option>
                        <option value="male" {{ request()->gender == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ request()->gender == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-red-600 text-white p-3 rounded-full hover:bg-red-700 transition duration-300">Apply Filters</button>
            </form>
        </div>

        @if ($products->isEmpty())
        <div class="text-center p-4">
            <p class="text-lg font-semibold text-red-500">Sorry, but what you want to find doesn't exist or is out of stock.</p>
        </div>
        @else
        <!-- Product Grid -->
        <div class="w-3/4 ml-8 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($products as $product)
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->name }}" class="mx-auto mb-4 h-40 object-cover rounded">
                    <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                    <p class="text-gray-500">RM {{ number_format($product->price, 2) }}</p>
                    <button @click="viewItem({{ $product->id }})"
                        class="mt-3 inline-block px-6 py-2 bg-white text-red-600 border border-red-600 font-bold rounded-full shadow hover:bg-red-50 transition duration-300">
                    View Item
                    </button>
                    <button @click="addToCart($event, '{{ $product->name }}', {{ $product->price }}, '{{ asset('storage/' . $product->product_image) }}')"
                            class="mt-3 inline-block px-6 py-2 bg-red-600 text-white font-bold rounded-full shadow hover:bg-red-700 transition duration-300">
                        Add to Cart
                    </button>
                </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Pagination -->
    <div class="mt-1 px-5 py-10 ">
        {{ $products->appends(request()->query())->links() }}
    </div>


    <!-- Product Modal -->
    <div x-show="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        @click.away="closeModal" x-transition>
        <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl relative p-6"
            x-show="isModalOpen" x-transition>

            <!-- Close Button -->
            <button @click="closeModal"
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-2xl">
                &times;
            </button>

            <div class="flex flex-col md:flex-row gap-6">
                <!-- Product Image -->
                <div class="flex-shrink-0 w-full md:w-1/2">
                    <img
                    :src="modalProduct.image"
                    alt="Product Image"
                    class="w-full h-auto rounded shadow"
                />                </div>

                <!-- Product Info -->
                <div class="flex flex-col justify-center md:w-1/2 text-left space-y-4">
                    <h3 class="text-2xl font-bold" x-text="modalProduct.name"></h3>
                    <p class="text-sm text-gray-500" x-text="'Gender: ' + modalProduct.gender"></p>
                    <p class="text-sm text-gray-500" x-text="'Size: ' + modalProduct.size"></p>
                    <p class="text-xl font-semibold text-red-600" x-text="'RM ' + modalProduct.price.toFixed(2)"></p>
                    <p class="text-gray-700" x-text="modalProduct.description"></p>
                    <button @click="addToCart($event, '{{ $product->name }}', {{ $product->price }}, '{{ asset('storage/' . $product->product_image) }}')"
                        class="mt-2 inline-block px-6 py-2 bg-red-600 text-white font-bold rounded-full shadow hover:bg-red-700 transition duration-300">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </div>


    @include('filament.components.footer')

    <script>
        function cartStore() {
            return {
                cart: [],
                isCartOpen: false,
                isModalOpen: false,
                modalProduct: {},
                openCart() { this.isCartOpen = true; },
                closeCart() { this.isCartOpen = false; },
                openModal() { this.isModalOpen = true; },
                closeModal() { this.isModalOpen = false; },
                viewItem(productId) {
                    fetch(`/product/${productId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.image = data.product_image ? `/storage/${data.product_image}` : '/images/no-image.png';

                            // Parse the price to a number so .toFixed works
                            data.price = parseFloat(data.price);

                            this.modalProduct = data;
                            this.openModal();
                        });
                },
                addToCart(event, name, price, image) {
                    const button = event.target;
                    const rect = button.getBoundingClientRect();
                    const clone = document.createElement("img");

                    clone.src = image;
                    clone.classList.add("flying-item");
                    document.body.appendChild(clone);

                    clone.style.left = `${rect.left}px`;
                    clone.style.top = `${rect.top}px`;
                    clone.style.width = "50px";
                    clone.style.height = "50px";

                    setTimeout(() => {
                        clone.style.transform = `translate(${window.innerWidth - 100}px, 20px) scale(0)`;
                        clone.style.opacity = "0";
                    }, 10);

                    setTimeout(() => {
                        document.body.removeChild(clone);
                        this.cart.push({ name, price, image });
                    }, 500);
                },
                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },
                get cartTotal() {
                    return this.cart.reduce((total, item) => total + item.price, 0);
                }


            };
        }

    </script>
</body>
</html>
