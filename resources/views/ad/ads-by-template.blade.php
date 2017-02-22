@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Главная</a></li>
                <li><a href="/templates/">Мои запросы</a></li>
                <li><a href="/template/{{$template->id}}/ads">{{$template->name}}</a></li>
                <li>Полученые обьявления</li>
            </ol>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>{{$template->name}}</strong> - cписок обработаных обьявлений
                    <strong>(всего {{$stat}})</strong>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><strong>ID</strong></th>
                                    <th><strong>Обьявление</strong></th>
                                    <th><strong>Дата получения</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($ads as $ad)
                            <tr>
                                <td>
                                    {{$ad->id}}
                                </td>
                                <td>
                                    <i class="fa fa-link" aria-hidden="true"></i>
                                    <a href="/template/{{$template->id}}/ads/{{$ad->id}}">{{$ad->ad_url}}</a>
                                </td>
                                <td>
                                    {{$ad->created_at}}
                                </td>
                            </tr>
                           @endforeach
                            </tbody>
                        </table>
                        {{ $ads->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
