<div>
    <h1>Dashboard Admin</h1>
    <p>Halo {{ Auth::user()->name }} Anda berhasil login sebagai Admin. (Silakan tim UI/UX melanjutkan desain di file ini)</p>
    
    <!-- Tombol Logout Bawaan Breeze agar Anda bisa testing pindah user -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Log Out</button>
    </form>
</div>