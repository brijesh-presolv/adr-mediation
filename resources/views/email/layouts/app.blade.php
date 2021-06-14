<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    </head>
    <body style="background:#e2e2e2;padding: 0;margin: 0;">
        <table style="background:#e2e2e2;" border="0" width="100%">
            <tbody>
                <tr>
                    <td>
                        <table style="margin:auto;" border="0" width="70%">
                            <thead>
                                <tr>
                                    <td style="background-color: #fff;">
                                        <small>
                                            Please add {{config('email.info','info@appname.com')}} to your address book to ensure our emails are delivered to your mailbox.
                                        </small>
                                    </td>                   
                                </tr>
                                <tr style="background-color:#6aa0ff">
                                    <th style="text-align: right;padding:40px 5px; ">
                                        <a href="{{url("/")}}"><img src="{{url("/assert/img/logo_mediacje.png")}}" width="170px"></a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody style="background-color: #fff;">
                                <tr><td style="padding:5px;"> @yield('content')</td></tr>
                            </tbody>
                            <tfoot style="background-color: #3d3b3b;color:#d3d3d3">
                                <tr>
                                    <td style="padding:5px;">
                                        <small>
                                            Presolv360 is the property of Edgecraft Solutions Private Limited. Copyright ©  2017-2020 Edgecraft Solutions Private Limited. All rights reserved. 
                                        </small>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;padding:5px;">
                                        <a href="#" style="display:inline-block;background-color:rgb(126,121,121);padding:5px;"><img src="{{url("/assert/img/linkedin.png")}}" width="25px"></a>
                                        <a href="#" style="display:inline-block;background-color:rgb(126,121,121);padding:5px;"><img src="{{url("/assert/img/twitter.png")}}" width="25px"></a>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
