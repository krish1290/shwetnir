<!DOCTYPE html>
<html>

<body>

    Dear {{ $data['name'] }}<br />
    New Purchase Added. Reference No : {{$data['purchase_id']}}<br />
    Purchase Status : {{ $data['status'] }} <br />

    Please Login to the web and approve purchase. <br>

    Thanks,<br />
    {{ config('app.name') }}

</body>

</html>
