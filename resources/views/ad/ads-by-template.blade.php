@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Главная</a></li>
                <li><a href="/templates/all">Мои запросы</a></li>
                <li><a href="/template/{{$template->id}}/ads">{{$template->name}}</a></li>
                <li>Полученые обьявления</li>
            </ol>
            @if (Session::has('succes'))
                <div class="alert alert-success">
                    {!! Session::get('succes') !!}
                </div>
            @endif
            @if (Session::has('errors'))
                <div class="alert alert-warning">
                    {!! Session::get('errors') !!}
                </div>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Экспорт данных</strong>
                </div>
                <div class="panel-body">
                    {!! Form::open(['route' => 'auth::ads.export']) !!}
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Сущность:</strong>
                            <div class="radio">
                                <label><input type="radio" value="ads" name="object" checked>Объявления</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" value="contacts" name="object">Контакты</label>
                            </div>
                            <strong>Количество:</strong>
                            <div class="radio">
                                <label class="radio-inline"><input id="count-export-all" type="radio" value="all" name="count" checked>Все</label>
                                <label class="radio-inline"><input id="count-export-manual" type="radio" value="manual" name="count">Указать</label>
                            </div>
                            <div id="count-export" class="count-input space-bottom">
                                <a class="incr-btn" data-action="decrease" href="#">–</a>
                                <input type="number" class="quantity" min="1" max="4" id="export-quantity"  name="quantity" value="1"/>
                                <a class="incr-btn" data-action="increase" href="#">&plus;</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <strong>Формат:</strong>
                            <div class="radio">
                                <label><input type="radio" value="json" name="type">JSON</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" value="csv" name="type">CSV</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" value="xml" name="type" checked>XML</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" value="yaml" name="type">YAML</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <strong>Дополнительно:</strong>
                            <div class="radio">
                                <label><input type="radio" value="save" name="additional" checked>Скачать файл на компьютер</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" value="output" name="additional">Вывести на экран</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" value="link" name="additional" disabled>Получить прямую ссылку</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" value="send" name="additional" disabled>Отправить на Email</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <button name="export_template" value="{{$template->id}}" class="btn btn-primary btn-sm export-button create-template" type="submit">Экспорт</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>{{$template->name}}</strong> - cписок обработаных обьявлений
                    <strong>(всего <span id="statAds">{{$stat}}</span>)</strong>
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
