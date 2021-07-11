@extends('app')

@section('content')
<div class="row margin-top-30">
    <div class="col-md-6 col-sm-6">
        <form action="{{route('qr-codes.update', $qr_row_id)}}" method="POST">
            <input type="hidden" name="_method" value="PUT" />
            @csrf
            
            <h4 >Название QR кода</h4>
            <div class="form-group col-md-6">
                <input type="text" class="form-control" name="name" placeholder="" value="{{$qr_row_name}}"/>
            </div>
            
            <h4>Параметры</h4>
            <div class="form-group col-md-6">
                <label for="city">City</label>
                <input type="text" class="form-control" name="city" placeholder="" value="{{$options->City}}"/>
            </div>
            <div class="form-group col-md-6">
                <label for="campaign">Campaign</label>
                <input type="text" class="form-control" name="campaign" placeholder="" value="{{$options->Campaign}}"/>
            </div>
            <div class="form-group col-md-6">
                <label for="source">Source</label>
                <input type="text" class="form-control" name="source" placeholder="Введите url ресурса" value=""/>
            </div>
            <div class="form-group col-md-6">
                <label for="product">Product</label>
                <input type="text" class="form-control" name="product" placeholder="" value="{{$options->Product}}"/>
            </div> 
            <button type="submit" name="submit" class="btn btn-danger" value="cancel">Отмена</button>
            <button type="submit" name="submit" class="btn btn-primary" value="update">Обновить</button>
        </form>
    </div>
</div>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@endsection