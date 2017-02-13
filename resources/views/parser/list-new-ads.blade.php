@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Список новых обьявлений</div>
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
                                    <a href="{{$ad->ad_url}}">{{$ad->ad_url}}</a>
                                </td>
                                {{--<td>
                                    <strong>{{$ad->template_id}}</strong>
                                </td>--}}
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
