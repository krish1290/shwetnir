<!DOCTYPE html>
<html>



<body>
    <h4>{{ $mailData['user_name'] }}, </h4>


    <p> Please review and sign <strong>{{ $mailData['title'] }}</strong> </p>

    <p> <a href="{{ $mailData['link'] }}" target="_blank">Review Document</a></p>
    <p>Thank you</p>
</body>

</html>
