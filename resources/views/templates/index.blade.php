@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Главная</a></li>
                <li><a class="active" href="/templates">Мои запросы</a></li>
            </ol>
            @foreach($templates as $template)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>
                            <a href="/template/{{$template->id}}/ads">{{$template->name}}</a>
                        </strong>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <a class="list-badge" href="#">Обьявлений обработано: <span class="badge">{{$template->adsProcessed}}</span></a><br>
                                <a class="list-badge" href="#">Обьявлений в очереди: <span class="badge">{{$template->adsNotProcessed}}</span></a><br>
                                <a class="list-badge" href="#">Обьявлений удалено: <span class="badge">2</span></a>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::open(['route' => 'auth::ads-fetch']) !!}
                                    <button name="template_id" value="{{$template->id}}" class="btn btn-primary btn-sm" type="submit">Запустить запрос в работу</button>
                                    {!! Form::close() !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['auth::templates.destroy', $template->id]]) !!}
                                    <button name="delete" value="{{$template->id}}" class="btn btn-default btn-sm" type="submit">Удалить запрос</button>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button name="" value="" class="btn btn-info btn-sm" type="button">Копировать запрос</button>
                                <button name="" value="" class="btn btn-info btn-sm" type="button">Статистика</button>
                                <button name="" data-toggle="collapse" data-target="#subscribe_{{$template->id}}" class="btn btn-info btn-sm" type="button">Подписаться</button>
                                <div id="subscribe_{{$template->id}}" class="collapse">
                                    <p>
                                        <div class="form-inline">
                                            <input id="subscribe" name="subscribe" placeholder="example@email.com" class="form-control input-sm" type="email">
                                            <button name="" value="" class="btn btn-default btn-sm" type="submit">Ok</button>
                                        </div>
                                    </p>
                                </div>
                            </div>
                            </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-default btn-sm" data-toggle="collapse" data-target="#export_{{$template->id}}">Экспорт обработаных обьявлений</button>

                                <div id="export_{{$template->id}}" class="collapse">
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Полностью обьявления в xml</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Только контакты в xml</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                <div>
            @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
