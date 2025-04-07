@php
    $customer = session('customer');
@endphp


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <title>eCommerce Store</title>
</head>
<body x-data="cartStore()" class="font-light antialiased bg-gray-100" x-init="$watch('cart', value => console.log('Cart updated:', value))" class="relative">

    @include('filament.components.header')

        <!-- Button to Open Cart -->
        <button @click="openCart"
        class="fixed top-36 right-6 px-6 py-3 bg-red-600 text-white font-bold rounded-full shadow-lg hover:bg-red-700 transition duration-300 z-40">
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

    <!-- Hero Section with Video Background -->
    <section class="relative h-screen flex items-center justify-center text-white overflow-hidden">
        <video autoplay muted loop class="absolute inset-0 w-full h-full object-cover">
            <source src="{{ asset('storage/images/bg.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative z-10 text-center">
            <h1 class="text-6xl font-extrabold">Your Dream Shoes with Half-Price</h1>
            <p class="mt-4 text-xl">Shoes from brands like Nike, Adidas, Puma, and more.</p>
            <a href="javascript:void(0);" @click="scrollToFeaturedCategories" class="mt-6 inline-block px-5 py-3 bg-red-600 text-white rounded-full shadow-lg hover:bg-red-700 transition duration-300">Shop Now</a>
        </div>
    </section>

    <!-- Featured Categories -->
    <section class="container mx-auto py-6 px-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @foreach ([['Men\'s Fashion', '3.jpg', 'male'], ['Women\'s Fashion', '5.jpg', 'female'], ['Unisex Fashion', '4.avif', null]] as [$title, $image, $gender])
                <div class="relative group overflow-hidden rounded-lg shadow-lg transform hover:scale-105 transition duration-300 h-100 w-full">
                    <img src="{{ asset('storage/images/' . $image) }}" alt="{{ $title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-between items-center p-4">
                        <h3 class="text-xl font-bold text-white">{{ $title }}</h3>
                        <a href="{{ route('products.index') }}{{ $gender ? '?gender=' . $gender : '' }}"
                        class="bg-red-600 border border-danger text-white px-4 py-2 rounded-full shadow-lg font-bold hover:bg-transparent border border-transparent">
                            Shop Now
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Product Showcase -->
    <section id="featured-categories" class="container mx-auto py-20 px-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-left">Trending Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
            @foreach ($products->shuffle() as $product)
                <div class="bg-white p-6 rounded-lg shadow-lg text-center transform hover:scale-105 transition duration-300">
                    <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->name }}" class="mx-auto mb-4 h-48 object-cover rounded-lg">
                    <h3 class="text-lg font-bold">{{ $product->name }}</h3>
                    <p class="text-gray-600 text-lg font-semibold">RM {{ number_format($product->price, 2) }}</p>
                    <button
                    @click="addToCart($event, {
                        id: {{ $product->id }},
                        name: '{{ $product->name }}',
                        price: {{ $product->price }},
                        image: '{{ asset('storage/' . $product->product_image) }}'
                    })"
                            class="mt-3 inline-block px-6 py-2 bg-red-600 text-white font-bold rounded-full shadow hover:bg-red-700 transition duration-300">
                        Add to Cart
                    </button>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Latest Products -->
    <section class="container mx-auto py-20 px-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-left">Latest Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
            @foreach ($products->sortByDesc('created_at')->take(4) as $product)
                <div class="bg-white p-6 rounded-lg shadow-lg text-center transform hover:scale-105 transition duration-300">
                    <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover">
                    <h3 class="text-lg font-bold">{{ $product->name }}</h3>
                    <p class="text-gray-600 text-lg font-semibold">RM {{ number_format($product->price, 2) }}</p>
                    <button
                    @click="addToCart($event, {
                        id: {{ $product->id }},
                        name: '{{ $product->name }}',
                        price: {{ $product->price }},
                        image: '{{ asset('storage/' . $product->product_image) }}'
                    })"
                            class="mt-3 inline-block px-6 py-2 bg-red-600 text-white font-bold rounded-full shadow hover:bg-red-700 transition duration-300">
                        Add to Cart
                    </button>
                </div>
            @endforeach
        </div>
    </section>

<!-- Review Section - Carousel with Swiper.js -->
<section class="container mx-auto py-16 px-4">
    <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">What Our Customers Say</h2>

    <!-- Swiper Container -->
    <div class="swiper">
        <div class="swiper-wrapper">
            @foreach ([['image' => 'customer1.jpg', 'quote' => 'The best shopping experience ever!', 'name' => 'Syahid'],
                        ['image' => 'customer2.jpg', 'quote' => 'Great quality and fast delivery.', 'name' => 'Ilhan'],
                        ['image' => 'customer3.jpg', 'quote' => 'Amazing products and service!', 'name' => 'Anas'],
                        ['image' => 'customer3.jpg', 'quote' => 'Always satisfied with my purchases!', 'name' => 'Anas'],
                        ['image' => 'customer3.jpg', 'quote' => 'Always satisfied with my purchases!', 'name' => 'Anas']] as $review)
                <div class="swiper-slide bg-white p-6 rounded-lg shadow-lg text-center">
                    <img src="{{ asset('storage/images/' . $review['image']) }}" alt="Customer" class="w-24 h-24 rounded-full mx-auto mb-4">
                    <p class="italic text-gray-700 mb-2">"{{ $review['quote'] }}"</p>
                    <p class="text-right text-sm text-gray-500">by {{ $review['name'] }}</p>
                </div>
            @endforeach
        </div>

        <!-- Pagination and Navigation Controls -->
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</section>

        <!-- Banner Section -->
        <section id="banner" class="relative my-16">
            <div class="container mx-auto px-4 py-20 rounded-lg relative bg-cover bg-center" style="background-image: url('storage/images/banner.jpg');">
                <div class="absolute inset-0 bg-black opacity-40 rounded-lg"></div>
                <div class="relative flex flex-col items-center justify-center h-full text-center text-white py-20">
                    <h2 class="text-4xl font-bold mb-4">Welcome to Our Shop</h2>
                    <div class="flex space-x-4">
                        <a href="#" class="bg-red-600 hover:bg-transparent border border-danger text-white hover:text-primary border border-transparent font-semibold px-4 py-2 rounded-full">Shop Now</a>
                        <a href="#" class="bg-red-600 hover:bg-transparent border border-danger text-white hover:text-primary border border-transparent font-semibold px-4 py-2 rounded-full">New Arrivals</a>
                        <a href="#" class="bg-red-600 hover:bg-transparent border border-danger text-white hover:text-primary border border-transparent font-semibold px-4 py-2 rounded-full">Sale</a>
                    </div>
                </div>
            </div>
        </section>

    @include('filament.components.footer')

</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        new Swiper(".swiper", {
            loop: true,
            slidesPerView: 1,
            spaceBetween: 20,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        });
    });
</script>
<script>
function cartStore() {
    return {
        isCartOpen: false,
        cart: [],
        openCart() {
            this.isCartOpen = true;
        },
        closeCart() {
            this.isCartOpen = false;
        },
        addToCart(event, product) {
            const item = this.cart.find(i => i.id === product.id);
            if (!item) {
                this.cart.push(product);
            }
        },
        removeFromCart(index) {
            this.cart.splice(index, 1);
        },
        get cartTotal() {
            return this.cart.reduce((total, item) => total + item.price, 0);
        },
        viewItem(id) {
            window.location.href = `/products/${id}`;
        },
        // Method to scroll to Featured Categories section
        scrollToFeaturedCategories() {
            document.getElementById('featured-categories').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    };
}

    </script>


</html>
