<h2 style="text-align:center; margin-top:50px;">
    Welcome to Dashboard

    <form action="{{ route('logout') }}" method="POST" style="position:absolute; top:20px; right:20px;">
        @csrf
        <button type="submit" class="btn btn-danger">
            Logout
        </button>
    </form>
</h2>
