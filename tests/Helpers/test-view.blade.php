<form method="post" action="{{ route('honeypot.post') }}">
    @csrf
    <x-honeypot::honeypot css-class="test"/>
    <input name="username" type="text" value="" autocomplete="off">
    <input name="password" type="password" value="" autocomplete="off">
</form>
