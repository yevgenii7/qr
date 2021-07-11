@extends('app')

@section('content')


<div class="row margin-top-30">
    <div class="col-md-6 col-sm-6">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <h2>{{$qr_name}}</h2>
                {!! QrCode::encoding('UTF-8')->size(300)->generate($qr_content); !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 qr-link">
            @foreach ($files as $key=>$file_name)
                <a  href="{{action([$controller,'downloadQr'],[$file_name])}}" type="button" class="btn btn-link">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    Скачать QR-код в формате .{{$key}}
                </a>
            @endforeach
            </div>
        </div>
    </div>
</div>
@endsection