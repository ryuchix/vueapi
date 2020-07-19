<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Weekly</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            
            <form action="{{ url('api/saveWeeklies') }}" method="post">

            {{ csrf_field() }}
            <input placeholder="et" type="text" id="et" name="et" style="padding: 20px; width: 80%" autocomplete="off">
            <input placeholder="etmini" type="text" id="etmini" name="et" style="padding: 20px; width: 80%" autocomplete="off">
            <input placeholder="vr" type="text" id="vr" name="vr" style="padding: 20px; width: 80%" autocomplete="off">
            <input placeholder="weeklies" type="text" name="weeklies" style="padding: 20px; width: 80%" autocomplete="off">
            <input placeholder="boc" type="text" name="boc" style="padding: 20px; width: 80%" autocomplete="off">

            <button type="submit">Submit</button>
            
            </form>

            {{ $item != null ? $item : '' }}
        </div>
    </body>
</html>