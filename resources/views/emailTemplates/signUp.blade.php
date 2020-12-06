<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Med24</title>
        <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Assistant:300,400,600,700,800&display=swap" rel="stylesheet">

    </head>
    <body style="margin: 0; padding: 0;">
        <table cellpadding="0" cellspacing="0" style="width: 600px; min-height: 800px; max-width: 600px;" >
            <thead style="margin-bottom: 20px;">
                <tr>
                    <td><img style="width: 150px;" src="{{ asset('public/assets/logo.png') }}" /></td>
                </tr>
            </thead>
            <tbody style="padding-left: 90px; display: block; padding-right: 20px;">
                <tr>
                    <td><h2 style="font-family: 'Lato', sans-serif; letter-spacing: 1.2px; color: #000000; font-size: 28px; line-height: 35px; font-weight: 800; margin-top: 80px;">You are almost there…</h2></td>
                </tr>
                <tr>
                    <td><p style="font-family: 'Assistant', sans-serif; font-size: 20px; line-height: 30px; letter-spacing: 0.72px; color: #101820;">Dear {{ $record['last_name'] }}, </p></td>
                </tr>
                <tr>
                    <td><p style="font-family: 'Assistant', sans-serif; font-size: 20px; line-height: 30px; letter-spacing: 0.72px; margin-bottom: 30px;  color: #101820; width: 700px; margin-top: 0px;">Welcome to Med24. Before we can get started, we need to quickly verify your email address. Please click the link below and sign in using your new Med24 ID: {{ $record['email'] }}</p></td>
                </tr>
                <tr>
                    <td><p style="font-family: 'Assistant', sans-serif; font-size: 20px; line-height: 30px; letter-spacing: 0.72px; margin-bottom: 30px;  color: #101820; margin-top: 0px;">You otp code is : {{ $record['otp'] }} </p></td>
                </tr>
                @if(isset($source))
                    @if($source == 'web')
                        <tr>
                            <td><p style="font-family: 'Assistant', sans-serif; font-size: 20px; line-height: 30px; letter-spacing: 0.72px; margin-bottom: 30px;  color: #101820; margin-top: 0px;">You can verify your account to click <a href="{{ url('verify-user/'.$record['id']) }}">here</a> </p></td>
                        </tr>
                        @endif
                @endif
                <tr>
                    <td><p style="font-family: 'Assistant', sans-serif; font-size: 20px; line-height: 30px; letter-spacing: 0.72px; margin-bottom: 30px;  color: #101820; margin-top: 0px;">Once your email is verified you can start using our app.</p></td>
                </tr>
                <tr>
                    <td><p style="font-family: 'Assistant', sans-serif; font-size: 20px; line-height: 30px; letter-spacing: 0.72px; margin-bottom: 30px;  color: #101820; margin-top: 0px;">Thank you for signing up, and good luck in your search to find the best hospital & doctors for your health.</p></td>
                </tr>
                <tr>
                    <td><p style="font-family: 'Assistant', sans-serif; font-size: 20px; line-height: 30px; letter-spacing: 0.72px; color: #101820;">Kind regards, <br>The Med24 team</p></td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
