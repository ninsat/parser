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
                            <strong>Формат:</strong>
                            <div class="radio">
                                <label><input type="radio" value="json" name="type" disabled>JSON</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" value="csv" name="type" checked>CSV</label>
                            </div>
                            <div class="radio disabled">
                                <label><input type="radio" value="xml" name="type" disabled>XML</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <strong>Разбивка:</strong>
                            <div class="radio">
                                <label><input type="radio" value="all" name="paginate" checked>Все в одном файле</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" value="two_files" name="paginate" disabled>Сформировать 2 файла</label>
                            </div>
                            <div class="radio disabled">
                                <label><input type="radio" value="four_files" name="paginate" disabled>Сформировать 4 файла</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <strong>Сущность:</strong>
                            <div class="radio">
                                <label><input type="radio" value="ads" name="object" checked>Объявления</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" value="contacts" name="object">Контакты</label>
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
