<!DOCTYPE html>
<html>

<body>
    <p>Welcome to {{ config('app.name') }} </p>

    Name: {{ $data['name'] }}<br />
    Username: {{ $data['username'] }}<br />
    Password: {{ $data['password'] }} <br />

    Thanks,<br />
    {{ config('app.name') }}

</body>

</html>
