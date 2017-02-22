@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Главная</a></li>
                <li><a href="/templates/">Мои запросы</a></li>
                <li><a href="/template/{{$template->id}}/ads-queue">{{$template->name}}</a></li>
                <li>Обьявления в очереди на обработку</li>
            </ol>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>{{$template->name}}</strong> - Список обьявлений поставленных в очередь на обработку
                    <strong>(Всего: {{$stat}})</strong>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><strong>ID</strong></th>
                                    <th><strong>URL</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($ads as $ad)
                            <tr>
                                <td>
                                    {{$ad->id}}
                                </td>
                                <td>
                                    <i class="fa fa-external-link" aria-hidden="true"></i>
                                    <a target="_blank" href="{{$ad->ad_url}}">{{$ad->ad_url}}</a>
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
