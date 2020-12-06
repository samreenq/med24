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
                    <td><h2 style="font-family: 'Lato', sans-serif; letter-spacing: 1.2px; color: #000000; font-size: 28px; line-height: 35px; font-weight: 800; margin-top: 80px;">Contact Us</h2></td>
                </tr>
                <tr>
                    <td><p style="font-family: 'Assistant', sans-serif; font-size: 20px; line-height: 30px; letter-spacing: 0.72px; color: #101820;">Dear Admin </p></td>
                </tr>
                <tr>
                    <td><p>
                            The following is the detail of user that contact you<br /><br> />
                            Full name: {!! $data['first_name'].' '.$data['last_name'] !!}<br />
                            Email: {!! $data['email'] !!}<br />
                            Subject: {!! $data['subject'] !!}<br />
                            Message: {!! $data['description'] !!}<br />
                        </p>
                    </td>
                </tr>
                <tr>
                    <td><p style="font-family: 'Assistant', sans-serif; font-size: 20px; line-height: 30px; letter-spacing: 0.72px; color: #101820;">Kind regards, <br>The Med24 team</p></td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
