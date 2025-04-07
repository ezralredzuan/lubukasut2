<!-- Include Alpine.js for interactivity -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<php?
Session::put('customer', $customer);

?>
<header class="bg-black shadow-md py-4 px-6 flex justify-between items-center relative z-50">
    <!-- Logo -->
    <div class="text-gray-800 font-semibold text-2xl ml-4">
        <img src="{{ asset('storage/images/logo.jpeg') }}" alt="lubukasut logo" class="h-14 w-auto">
    </div>

    <!-- Navigation Links -->
    <nav>
        <ul class="flex space-x-10 mr-10">
            <li><a href="{{ route('home') }}" class="text-white hover:text-yellow-500">Home</a></li>
            <li><a href="{{ route('product') }}" class="text-white hover:text-yellow-500">Shop</a></li>
            <li><a href="{{ route('about') }}" class="text-white hover:text-yellow-500">About</a></li>
            <li><a href="{{ route('faq') }}" class="text-white hover:text-yellow-500">Contact</a></li>
        </ul>
    </nav>

    <!-- User Icon with Login/Register Popup -->
    <div class="relative z-50" id="auth-wrapper">
        <!-- User Icon Button -->
        <button id="authToggle" class="relative w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-white hover:bg-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 8.25a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 19.5a7.5 7.5 0 0115 0"></path>
            </svg>
        </button>

        @if(Session::has('customer'))
        <!-- Logged-in Dropdown -->
        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
            <div class="text-gray-800 px-4 py-3 border-b">👋 Hi, {{ Session::get('customer')->full_name }}</div>
            <ul class="text-sm text-gray-700">
                <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Account</a></li>
                <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Orders</a></li>
                <li>
                    <form method="POST" action="{{ route('customer.logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    @else

        <!-- Login/Register Popup -->
        <div id="authPopup" class="hidden absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
            <div class="flex justify-around bg-gray-100 text-gray-700 font-semibold">
                <button id="loginTab" class="w-1/2 py-2 hover:text-yellow-500 border-b-2 border-transparent active-tab">Login</button>
                <button id="registerTab" class="w-1/2 py-2 hover:text-yellow-500 border-b-2 border-transparent">Register</button>
            </div>
            <div id="authError" class="hidden text-red-600 text-sm text-center py-2 bg-red-100 border border-red-300 mx-4 mt-2 rounded">
            </div>

            <!-- Forms Wrapper -->
            <div class="relative h-[500px] overflow-hidden">
                <!-- Login Form -->
                <div id="loginForm" class="absolute w-full transition-all duration-500 left-0 px-6 pt-4">
                    @include('login-form')
                </div>

                <!-- Register Form -->
                <div id="registerForm" class="absolute w-full transition-all duration-500 left-full px-6 pt-4">
                    @include('register-form')
                </div>

                <!-- Success Slider -->
                <div id="successSlide" class="hidden absolute top-0 left-0 w-full h-full flex flex-col items-center justify-center bg-white text-xl font-bold text-gray-800">
                    <div id="successMessage" class="animate-bounce text-center mb-4"></div>
                </div>
            </div>
        </div>
    @endif
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function() {
        $('#authToggle').on('click', function () {
            @if(Session::has('customer'))
                $('#userDropdown').toggleClass('hidden');
            @else
                $('#authPopup').toggleClass('hidden');
            @endif
        });

        $('#loginTab').on('click', function () {
            $('#loginForm').css('left', '0');
            $('#registerForm').css('left', '100%');
            $(this).addClass('text-yellow-500 border-yellow-500').removeClass('text-gray-700');
            $('#registerTab').removeClass('text-yellow-500 border-yellow-500').addClass('text-gray-700');
            $('#authError').addClass('hidden').text('');
        });

        $('#registerTab').on('click', function () {
            $('#loginForm').css('left', '-100%');
            $('#registerForm').css('left', '0');
            $(this).addClass('text-yellow-500 border-yellow-500').removeClass('text-gray-700');
            $('#loginTab').removeClass('text-yellow-500 border-yellow-500').addClass('text-gray-700');
            $('#authError').addClass('hidden').text('');
        });

        $('#loginSubmit').on('submit', function (e) {
            e.preventDefault();
            $('#authError').addClass('hidden').text('');

            $.ajax({
                url: '{{ route("customer.login") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    if (response.role === 'admin') {
                        $('#successMessage').text('Welcome Admin!');
                    } else {
                        $('#successMessage').text('Welcome Back Thrifter');
                    }

                    $('#successSlide').removeClass('hidden');

                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 1500);
                },
                error: function (xhr) {
                    let msg = 'An error occurred';
                    if (xhr.status === 401) {
                        msg = 'Invalid username or password.';
                    } else if (xhr.responseJSON?.message) {
                        msg = xhr.responseJSON.message;
                    }
                    $('#authError').removeClass('hidden').text(msg);
                }
            });
        });

        $('#registerSubmit').on('submit', function (e) {
            e.preventDefault();
            $('#authError').addClass('hidden').text('');

            $.ajax({
                url: '{{ route("customer.register") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function () {
                    $('#successMessage').text('Come On Aboard Thrifter');
                    $('#successSlide').removeClass('hidden');
                    setTimeout(() => location.reload(), 2000);
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON?.errors;
                    let msg = 'Registration failed.';

                    if (errors) {
                        msg = Object.values(errors).flat().join(' ');
                    } else if (xhr.responseJSON?.message) {
                        msg = xhr.responseJSON.message;
                    }

                    $('#authError').removeClass('hidden').text(msg);
                }
            });
        });
    });
    </script>



</header>
