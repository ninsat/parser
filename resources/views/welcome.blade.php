<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>OlxParser</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            @import url(http://fonts.googleapis.com/css?family=Open+Sans:400,700,400italic,700italic&subset=cyrillic-ext,latin);
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: "Open Sans","Nimbus Sans L",sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
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
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
                font-family: 'Raleway', sans-serif;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/templates/all') }}">Секция парсинга</a>
                    @else
                        <a href="{{ url('/login') }}">Вход</a>
                        <a href="{{ url('/register') }}">Регистрация</a>
                    @endif
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    OlxParser
                </div>

                <div class="links">
                    <a href="/documentation">Документация</a>
                    <a href="#">Примеры</a>
                    <a href="#">Экспорт и API</a>
                    <a href="#">Контакты</a>
                </div>
            </div>
        </div>
    </body>
</html>
