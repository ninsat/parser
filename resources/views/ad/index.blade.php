@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Главная</a></li>
                <li><a href="/templates/">Мои запросы</a></li>
                <li><a href="/template/{{$template->id}}/ads">{{$template->name}}</a></li>
                <li>Обьявление ID {{$ad->id}}</li>
            </ol>
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Обьявление ID: {{$ad->id}}</strong> <small>от {{$ad->created_at}}</small></div>
                <div class="panel-body">
                    Ad Data
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
