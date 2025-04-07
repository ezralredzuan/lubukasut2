<form id="loginSubmit" method="POST" action="{{ route('customer.login') }}">
    @csrf
    <div class="mb-4">
        <input type="text" name="username" placeholder="Username" class="w-full border px-4 py-2 rounded" required>
    </div>
    <div class="mb-4 relative">
        <input type="password" name="password" id="loginPassword" placeholder="Password" class="w-full border px-4 py-2 rounded pr-10" required>
        <!-- Eye Icon for Show Password -->
        <button type="button" id="toggleLoginPassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-600">
            <svg id="eyeLoginIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                <!-- Closed Eye Icon (default state) -->
                <path id="eyeLoginPath" stroke-linecap="round" stroke-linejoin="round" d="M15.75 8.25a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 19.5a7.5 7.5 0 0115 0"></path>
            </svg>
        </button>
    </div>
    <button type="submit" class="w-full bg-black text-white py-2 rounded hover:bg-yellow-500">Login</button>
    <div id="authError" class="text-red-500 hidden mt-2"></div>
</form>

<script>
    // Toggle password visibility for login form
    document.getElementById('toggleLoginPassword').addEventListener('click', function () {
        var passwordField = document.getElementById('loginPassword');
        var eyeIcon = document.getElementById('eyeLoginIcon');
        var eyePath = document.getElementById('eyeLoginPath');

        // Toggle the password visibility and icon
        if (passwordField.type === 'password') {
            passwordField.setAttribute('type', 'text');  // Show password
            eyePath.setAttribute('d', 'M12 3C7.029 3 3.001 6.803 3.001 9c0 2.197 2.627 5.331 6.553 7.166a8.936 8.936 0 010-2.332C6.627 11.669 4 9 4 9s2.627-5.331 6.553-7.166a8.936 8.936 0 010 2.332C9.626 8.331 12 9 12 9c3.971 0 7.999 3.803 7.999 6s-3.999 6-7.999 6z');  // Open Eye Icon
        } else {
            passwordField.setAttribute('type', 'password');  // Hide password
            eyePath.setAttribute('d', 'M15.75 8.25a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 19.5a7.5 7.5 0 0115 0');  // Closed Eye Icon
        }
    });
</script>
