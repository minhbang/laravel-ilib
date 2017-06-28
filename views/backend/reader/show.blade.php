@extends('backend.layouts.modal')
@section('content')
    <table class="table table-hover table-striped table-bordered table-detail">
        <tr>
            <td>ID</td>
            <td><strong>{{$reader->user_id}}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('ilib::reader.code') }}</td>
            <td><strong>{{$reader->code}}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('user::user.username') }}</td>
            <td><strong>{{$reader->user->username}}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('user::user.name') }}</td>
            <td><strong>{{$reader->user->name}}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('user::user.group_id') }}</td>
            <td><strong>{{$reader->user->group->full_name}}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('ilib::reader.security_id') }}</td>
            <td>{!! $reader->present()->security !!}</td>
        </tr>
    </table>
@stop