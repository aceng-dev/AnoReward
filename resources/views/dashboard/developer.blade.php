<div>
    <h1>Dashboard Developer</h1>
    <p>Halo {{ Auth::user()->name }}, Anda berhasil login sebagai Developer. (Silakan tim UI/UX melanjutkan desain di file ini)</p>
    
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Log Out</button>
    </form>
</div>