@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            @if (Session::has('succes'))
                <div class="alert alert-success">
                    {!! Session::get('succes') !!}
                </div>
            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

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
                        <div class="selectField"><button type="button" class="btn btn-info" onclick="remoteIdFunction()">Описание</button></div>
                        <div class="selectField"><button type="button" class="btn btn-info" onclick="remoteIdFunction()">Обьявление от</button></div>
                        <div class="selectField"><button type="button" class="btn btn-info" onclick="emailFunction()">Изображения</button></div>
                        <div class="selectField"><button type="button" class="btn btn-info" onclick="contactFunction()">Дата обьявления</button></div>
                        <div class="selectField"><button type="button" class="btn btn-info" onclick="resetElements()">Область</button></div>
                        <div class="selectField"><button type="button" class="btn btn-info" onclick="textareaFunction()">Город</button></div>
                        <div class="selectField"><button type="button" class="btn btn-info" onclick="resetElements()">Район</button></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="panel panel-default">
                            <div class="panel-heading">Добавленые поля и настройка селекторов</div>
                            <div class="panel-body">
                                <div id="creatorForm">
                                    <div class="form-group newFields" id="adId">
                                        <label class="required" for="adId">Номер объявления:</label>
                                        <input id="adId" readonly class="form-control" value=".offer-titlebox__details > em > small" name="adId" type="text">
                                        <span class="help-block">Селектор уникального ID обьявления.</span>
                                    </div>
                                    <div class="form-group newFields" id="urlField">
                                        <label class="required" for="adUrl">Css селектор адреса обьявлений:</label>
                                        <input id="adUrl" readonly class="form-control" value="h3.x-large a.detailsLink" name="adUrl" type="text">
                                        <span class="help-block">Селектор должен указывать на html тег 'a', т. к. парсер выбирает href атрибут.</span>
                                    </div>
                                    <div class="form-group newFields" id="paginateField">
                                        <label class="required" for="paginate">Css селектор пагинации в запросе:</label>
                                        <input id="paginate" readonly class="form-control" value=".pager span a.lheight24" name="paginate" type="text">
                                        <span class="help-block">Селектор должен указывать на html тег 'a', Значение пагинации должно указывать на первую страницу.</span>
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
