@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Главная</a></li>
                <li>Мои запросы</li>
            </ol>
            <div class="panel panel-default">
                <div class="panel-body fixHeight">
                    <div class="text-center">
                        <p>У вас пока нет активных запросов. <a href="/templates/create">Создайте первый</a></p>
                    </div>
                    <br>
                    <div class="text-center">
                        <p><img src="/img/big-logo.png" height="200px" alt="Parser hard work!"/></p>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
