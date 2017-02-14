@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Список запросов</div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                            @foreach($templates as $template)
                            <tr>
                                <td>
                                    <strong>{{$template->name}}</strong>
                                </td>
                                <td>
                                    {!! Form::open(['route' => 'auth::ads-fetch']) !!}
                                        <button name="template_id" value="{{$template->id}}" class="btn btn-default btn-xs" type="submit">Получить список новых обьявлений</button>
                                    {!! Form::close() !!}
                                </td>
                                <td>
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['auth::templates.destroy', $template->id]]) !!}
                                        <button name="delete" value="{{$template->id}}" class="btn btn-default btn-xs" type="submit">Удалить запрос</button>
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
