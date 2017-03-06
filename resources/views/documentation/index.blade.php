@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Главная</a></li>
                <li>Документация</li>
            </ol>
            <div class="panel panel-default">
                <div class="panel-heading">
                   <h4>Документация</h4>
                </div>
                <div class="panel-body">
                    <h4>Установка и настройка</h4>
                    <p>
                        После установки необходимо создать все необходимые таблицы в БД командой:
                    </p>
                    <pre>php artisan migrate</pre>
                    <p>
                        Также наполнить таблицу пользователей. Данная команда создаст нового администратора "root" пароль "123qwe"
                    </p>
                    <pre>php artisan db:seed --class=UsersTableSeeder</pre>
                    <p>
                        Для корректной работы парсера необходимо настроить крон на запуск скрипта каждую минуту.
                        Пример для linux ubuntu ниже.
                    </p>
                    <pre>* * * * * php /path_to_parser/artisan schedule:run >> /dev/null 2>&1</pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
