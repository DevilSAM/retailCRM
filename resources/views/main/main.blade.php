@extends('layouts.app')

@section('content')
    <div class="">
        <form method="post" action="/buy" enctype="multipart/form-data">
            @csrf

            <order-component></order-component>

        </form>
    </div>
@endsection