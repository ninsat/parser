@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            {!! Form::open(['route' => 'auth::templates.store']) !!}
                <div class="jumbotron">
                    <p>Создание парсинга</p>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-group">
                                    <input required type="text" class="form-control" name="queryName" id="queryName" placeholder="Название запроса">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" id="mainUrl" name="mainUrl" placeholder="Адрес стартовой страницы для парсинга">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">Доступные поля</div>
                            <div class="panel-body">
                        <div class="selectField"><button type="button" class="btn btn-info" onclick="remoteIdFunction()">Remote ID</button></div>
                        <div class="selectField"><button type="button" class="btn btn-info" onclick="emailFunction()">Email</button></div>
                        <div class="selectField"><button type="button" class="btn btn-info" onclick="contactFunction()">Contact</button></div>
                        <div class="selectField"><button type="button" class="btn btn-info" onclick="textareaFunction()">Message</button></div>
                        <div class="selectField"><button type="button" class="btn btn-info" onclick="resetElements()">Reset</button></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="panel panel-default">
                            <div class="panel-heading">Добавленые поля и настройка селекторов</div>
                            <div class="panel-body">
                                <div id="creatorForm">
                                    <div class="input-group newFields" id="urlField">
                                        <label class="required" for="additionalUrl">Адрес дочерних страниц (Url):</label>
                                        <input id="additionalUrl" class="form-control" value="h3.x-large" name="additionalUrl" type="text">
                                        <span class="input-group-btn">
                                            <button class="btn btn-asterisk" type="button">
                                                <i class="fa fa-asterisk" aria-hidden="true"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                    </div>
                    <div class="col-md-4">
                        {{ Form::submit('Сохранить запрос и перейти к парсингу', ['class' => 'btn btn-default']) }}
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
