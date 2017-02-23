@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Главная</a></li>
                <li><a href="/templates/">Мои запросы</a></li>
                <li><a href="/template/{{$template->id}}/ads">{{$template->name}}</a></li>
                <li>Обьявление ID {{$ad->id}} <small>от {{$ad->created_at}}</small></li>
            </ol>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Источник:
                        <small>
                            <a target="_blank" href="{{$ad->ad_url}}">{{$ad->ad_url}}</a>
                        </small>
                    </h4>
                </div>
                <div class="panel-body">
                    @foreach($ad->fields as $field)
                        <h4>{{$field['field']->name}}</h4>
                        <div class="ad-data">{{$field['data']->body}}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
