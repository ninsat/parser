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
                                    <label class="required" for="queryName">Название запроса:</label>
                                    <input required type="text" class="form-control" id="queryName" name="queryName" placeholder="Например: Asus zenfone">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="required" for="mainUrl">Адрес стартовой страницы для парсинга:</label>
                                <input type="text" required class="form-control" id="mainUrl" name="mainUrl" placeholder="https://www.olx.ua/list/q-asus-zenfone/">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-8">
                            <label class="checkbox-inline"><input type="checkbox" checked value="1">Постраничный парсинг</label>
                            <label class="checkbox-inline"><input type="checkbox" value="">Получать контакты автора обьявления</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-7">
                        <h4>Поля для парсинга</h4>
                        <div class="panel panel-default">
                            <div class="panel-heading">Обязательные поля</div>
                            <div class="panel-body">
                                <div id="requiredFields">
                                    <div class="form-group fields" id="adId">
                                        <label class="required" for="adId">Номер объявления:</label>
                                        <input id="adId" readonly class="form-control input-sm" value=".offer-titlebox__details > em > small" name="adId" type="text">
                                        <span class="help-block">Селектор уникального ID обьявления.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">Постраничный парсинг</div>
                            <div class="panel-body">
                                <div id="paginationFields">
                                    <div class="form-group fields" id="urlField">
                                        <label class="required" for="adUrl">Адрес обьявлений:</label>
                                        <input id="adUrl" readonly class="form-control input-sm" value="h3.x-large a.detailsLink" name="adUrl" type="text">
                                        <span class="help-block">Селектор должен указывать на html тег 'a', т. к. парсер выбирает href атрибут.</span>
                                    </div>
                                    <div class="form-group fields" id="paginateField">
                                        <label class="required" for="paginate">Пагинация в запросе ( используется при постраничном парсинге ):</label>
                                        <input id="paginate" readonly class="form-control input-sm" value=".pager span a.lheight24" name="paginate" type="text">
                                        <span class="help-block">Селектор должен указывать на html тег 'a', Значение пагинации должно указывать на первую страницу.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Поля контактных данных</div>
                            <div class="panel-body">
                                <div id="contactsGroup">
                                    <div class="form-group fields" id="userIdField">
                                        <label class="required" for="userId">ID пользователя olx.ua:</label>
                                        <input id="userId" readonly class="form-control input-sm" value="#offeractions .user-offers" name="userId" type="text">
                                    </div>
                                    <div class="form-group fields" id="userNameField">
                                        <label class="required" for="userName">Имя пользователя:</label>
                                        <input id="userName" readonly class="form-control input-sm" value="#offeractions .offer-user__details h4" name="userName" type="text">
                                    </div>
                                    <div class="form-group fields" id="userTelField">
                                        <label class="required" for="userTel">Телефон пользователя:</label>
                                        <input id="userTel" readonly class="form-control input-sm" value="#contact_methods .link-phone" name="userTel" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <h4>Дополнительные поля</h4>
                        <div class="panel panel-default">
                            <div class="panel-heading">Конструктор поля</div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="sr-only" for="exampleInputEmail3">Email address</label>
                                    <input type="email" class="form-control input-sm" id="exampleInputEmail3" placeholder="Название поля (англ.)">
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="exampleInputPassword3">Password</label>
                                    <input type="password" class="form-control input-sm" id="exampleInputPassword3" placeholder="jQuery Селектор">
                                </div>

                                <button type="button" class="btn btn-info btn-sm">Создать поле</button>

                               {{-- <div class="selectField"><button type="button" class="btn btn-info" onclick="remoteIdFunction()">Описание</button></div>--}}
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Созданные поля</div>
                            <div class="panel-body">
                                <div class="creatorForm">
                                Пока нет созданных полей
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    {{ Form::submit('Сохранить запрос и перейти к парсингу', ['class' => 'btn btn-primary btn-sm center-block create-template']) }}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
