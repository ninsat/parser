@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Главная</a></li>
                <li><a href="/templates/all">Мои запросы</a></li>
                <li><a href="/template/{{$template->id}}/ads-queue">{{$template->name}}</a></li>
                <li>Проигнорированные объявления</li>
            </ol>
            <div class="panel panel-default">
                <div class="panel-body">
                    Обьявления могут быть проигнорированы по разным причинам, одна из них - недоступность страницы с обьявлением для парсера. Также автор мог удалить
                    объявление, либо перевести его в архив т. к. оно более не актуально, в данном случае парсер не сможет забирать содержимое объявления и будет его игнорировать.
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>{{$template->name}}</strong> - Список проигнорированых обьявлений при обработке
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
