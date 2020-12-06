<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
        <title>Med24</title>
    </head>
    <body style="font-family: 'Roboto', sans-serif; color: #6e6e6e;">
        <div style="padding: 30px 60px; background: white; margin: 0 auto; width: 75%;">
            <table border="0" cellpadding="15" cellspacing="0" width="100%" align="center" style="background: white; padding: 15px;">
                <tr height="150">
                    <td align="center" colspan="2">
                        <img src="{{ asset('public/assets/logo.png') }}" alt="">
                    </td>
                </tr>
                <tr height="60">
                    <td align="center" colspan="2">
                        <img src="{{ asset('public/uploads/thanks.png') }}" alt="" style="width: 200px;">
                    </td>
                </tr>
                <tr height="2">
                    <td align="left" colspan="2">Hi {{ ($record->patient->first_name ?? '').' '.($record->patient->last_name) }},</td>
                </tr>
                <tr height="2">
                    <td align="left" colspan="2" style="padding-bottom:30px;">Your appointment has been schedule successfully. You'll find all the details about your appointment below.</td>
                </tr>
                <tr>
                    <td align="left" style="border-bottom: 1px solid #e8e7e7; width: 15%; color: #8f278f;"><b>Prefered Date {{ $record->type == 'appointment' && $record->appointment_date_2 != null && $record->appointment_time_2 != null ? '1' : '' }}</b></td>
                    <td align="left" style="border-bottom: 1px solid #e8e7e7; width: 15%; color: #8f278f;"><b>Prefered Time {{ $record->type == 'appointment' && $record->appointment_date_2 != null && $record->appointment_time_2 != null ? '1' : '' }}</b></td>
                    @if($record->type == 'appointment' && $record->appointment_date_2 != null && $record->appointment_time_2 != null)
                    <td align="left" style="border-bottom: 1px solid #e8e7e7; width: 15%; color: #8f278f;"><b>Prefered Date {{ $record->type == 'appointment' && $record->appointment_date_2 != null && $record->appointment_time_2 != null ? '2' : '' }}</b></td>
                    <td align="left" style="border-bottom: 1px solid #e8e7e7; width: 15%; color: #8f278f;"><b>Prefered Time {{ $record->type == 'appointment' && $record->appointment_date_2 != null && $record->appointment_time_2 != null ? '2' : '' }}</b></td>
                    @endif
                    <td align="left" style="border-bottom: 1px solid #e8e7e7; width: 20%; color: #8f278f;"><b>Hospital</b></td>
                    <td align="left" style="border-bottom: 1px solid #e8e7e7; width: 20%; color: #8f278f;"><b>Doctor</b></td>
                    <td align="left" style="border-bottom: 1px solid #e8e7e7; width: 20%; color: #8f278f;"><b>Patient</b></td>
                </tr>
                <tr>
                    <td align="left">{{ date('d M Y', strtotime($record->appointment_date)) }}</td>
                    <td align="left">{{ date('g:i A', strtotime($record->appointment_time)) }}</td>
                    @if($record->type == 'appointment' && $record->appointment_date_2 != null && $record->appointment_time_2 != null)
                    <td align="left">{{ date('d M Y', strtotime($record->appointment_date)) }}</td>
                    <td align="left">{{ date('g:i A', strtotime($record->appointment_time)) }}</td>
                    @endif
                    <td align="left">{{ $record->hospital->name }}</td>
                    <td align="left">{{ ($record->doctor->first_name ?? '').' '.($record->doctor->last_name ?? '') }}</td>
                    <td align="left">{{ (@$record->patient->first_name).' '.(@$record->patient->last_name)  }}</td>
                </tr>
                <tr>
                    <td align="left" colspan="2">
                        <table border="0" cellpadding="5" cellspacing="0" width="100%">
                            <tr>
                                <td colspan="4" align="left" style="padding-top: 40px;">
                                   Kind Regards,
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" align="left">
                                   The Med24 Team
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr height="10">
                    <td align="left" colspan="2" style="border-bottom: 2px solid #8f278f;">&nbsp;</td>
                </tr>
            </table>
            <p style="text-align:center; color:#8f278f; margin-top: 2%;">Copyright 2020. All rights reserved</p>
            <p style="text-align:center; color:#8f278f;">help@med24.com <img src="{{ asset('public/uploads/Oval.png') }}" alt="" style="padding: 0px 8px;">  +9167-904-0309</p>
            <ul style="display:flex;padding:0px;width:250px;margin:20px auto; justify-content: center;">
                <li style="list-style:none;padding-right:20px"><a href="#"><img src="{{ asset('public/uploads/fb-icon.png') }}" class=""></a></li>
                <li style="list-style:none;padding-right:20px"><a href="#"><img src="{{ asset('public/uploads/ig-icon.png') }}" class=""></a></li>
                <li style="padding-right:0px!important;list-style:none"><a href="#"><img src="{{ asset('public/uploads/twitter-icon.png') }}" class=""></a></li>
            </ul>
        </div>
    </body>
</html>