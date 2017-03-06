@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="/">Главная</a></li>
                <li>Мои запросы</li>
            </ol>

            @if (Session::has('succes'))
                <div class="alert alert-success">
                    {!! Session::get('succes') !!}
                </div>
            @endif

            @foreach($templates as $template)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>
                            {{$template->name}}
                            {{--<a href="/template/{{$template->id}}/ads">{{$template->name}}</a>--}}
                        </strong>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">

                                @if($template->countAdsParsed === 0)
                                    Обьявлений обработано: <span class="badge">{{$template->countAdsParsed}}</span>
                                @else
                                    <a class="list-badge" href="/template/{{$template->id}}/ads">Обьявлений обработано: <span class="badge">{{$template->countAdsParsed}}</span></a><br>
                                @endif

                                @if($template->countAdsFetched === 0)
                                    Обьявлений в очереди: <span class="badge">{{$template->countAdsFetched}}</span><br>
                                @else
                                    <a class="list-badge" href="/template/{{$template->id}}/ads-queue">Обьявлений в очереди: <span class="badge">{{$template->countAdsFetched}}</span></a><br>
                                @endif

                                @if($template->countAdsIgnored === 0)
                                    Проигнорировано: <span class="badge">{{$template->countAdsIgnored}}</span>
                                @else
                                    <a class="list-badge" href="/template/{{$template->id}}/ads-ignored">Проигнорировано: <span class="badge">{{$template->countAdsIgnored}}</span></a>
                                @endif

                                <div class="last-update">
                                    <p>Проверка на новые: <span class="badge">{{$template->lastUpdateDateFetched}}</span></p>
                                    <p>Последний парсинг: <span class="badge">{{$template->lastUpdateDateParsed}}</span></p>
                                </div>
                            </div>
                            <div class="col-md-5">
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    @if ($template->work === 0)
                                        {!! Form::open(['route' => 'auth::templates.control']) !!}
                                            <button name="templateStart" value="{{$template->id}}" class="btn btn-primary btn-sm" type="submit">Запустить запрос в работу</button>
                                        {!! Form::close() !!}
                                    @else
                                        {!! Form::open(['route' => 'auth::templates.control']) !!}
                                        <button name="templateStop" value="{{$template->id}}" class="btn btn-primary btn-sm" type="submit">Остановить работу запроса</button>
                                        {!! Form::close() !!}
                                    @endif
                                </div>
                                <div class="form-group">
                                    <button name="" data-toggle="collapse" data-target="#settings_{{$template->id}}" class="btn btn-default btn-sm" type="button">Изменить настройки</button>
                                    <div id="settings_{{$template->id}}" class="collapse">
                                        <p>
                                        <div class="form-inline">
                                            <input id="subscribe" name="subscribe" placeholder="Email для подписки" class="form-control input-sm" type="email">
                                            <button name="" value="" class="btn btn-default btn-sm" type="submit">Ok</button>
                                        </div>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['auth::templates.destroy', $template->id]]) !!}
                                    <button name="delete" value="{{$template->id}}" class="btn btn-default btn-sm" type="submit">Удалить запрос</button>
                                    {!! Form::close() !!}
                                </div>
                            </div>
{{--                            <div class="col-md-4">
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
                            </div>--}}
                        </div>
                        {{--<div class="row">
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
                        </div>--}}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
