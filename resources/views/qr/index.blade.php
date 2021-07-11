@extends('app')

@section('content')
<div class="row margin-top-30">
    <div class="col-md-12 col-sm-12 text-right additional-link">
        <a href="{{action([$controller, 'create'])}}">
            <i class="fa fa-plus"></i>&nbsp;Создать запись
        </a>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <table class="table table-borderless qr-table">
            <thead class="thead-light">
                <tr>
                <th scope="col"><i class="fa fa-pencil"></i></th>
                <th scope="col">#</th>
                <th scope="col">Название QR кода</th>
                <th scope="col">Дата создания</th>
                <th scope="col">Набор параметров</th>
                <th scope="col">Кол-во переходов</th>
                <th scope="col">Удалить</th>
                <th scope="col">Получение QR кода</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr>
                    <td>
                        <a class="btn btn-success" href="{{action([$controller,'edit'],[$item->id])}}">
                            <i class="fa fa-pencil"></i>
                        </a>
                    </td>
                    <td>
                        {{ $loop->iteration }}
                    </td>
                    <td class="name-td">
                        {{ $item->name }}
                    </td>
                    <td>
                        {{ date_create($item->created_at)->format('d.m.Y') }}
                    </td>
                    <td class="options-td">
                        {{ implode('; ', array_map(
                            function ($v, $k) { return sprintf("%s:%s", $k, $v); },
                            $input = json_decode($item->options,true),
                            array_keys($input)
                        ))}}
                    </td>
                    <td class="count-td">
                        {{ $item->transition_count }}
                    </td>
                    <td>
                        <form action="{{ route('qr-codes.destroy' , $item->id)}}" method="POST">
                            <input type="hidden" name="_method" value="DELETE" />
                            @csrf
                            <button class="btn btn-danger" type="submit" name="submit" value="cancel"><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                    <td>
                        <a type="button" class="btn btn-link" href="{{action([$controller,'getQr'],[$item->id])}}">
                            Получить QR-code
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>    
@endsection