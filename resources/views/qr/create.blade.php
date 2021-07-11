@extends('app')

@section('content')
<div class="row margin-top-30">
    <div class="col-md-6 col-sm-6">
        <form action="{{route('qr-codes.store')}}" method="POST">
            @csrf
            <h4 >Название QR кода</h4>
                <div class="form-group col-md-6">
                    <input type="text" class="form-control" name="name" placeholder="">
                </div>
            <h4>Параметры</h4>
                <div class="form-group col-md-6">
                    <label for="city">City</label>
                    <input type="text" class="form-control" name="city" placeholder="">
                </div>
                <div class="form-group col-md-6">
                    <label for="campaign">Campaign</label>
                    <input type="text" class="form-control" name="campaign" placeholder="">
                </div>
                <div class="form-group col-md-6">
                    <label for="source">Source</label>
                    <input type="text" class="form-control" name="source" placeholder="">
                </div>
                <div class="form-group col-md-6">
                    <label for="product">Product</label>
                    <input type="text" class="form-control" name="product" placeholder="">
                </div>  
                <button type="submit" name="submit" class="btn btn-danger" value="cancel">Отмена</button>
                <button type="submit" name="submit" class="btn btn-primary" value="save">Сохранить</button>
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