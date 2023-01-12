@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Import Order</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form action="orders/import" method="POST" enctype="multipart/form-data"> <!-- Form bắt buộc phải có thuộc tính enctype thì mới up được file lên -->
                        {{ csrf_field() }}
                        <div class="form-group">
                            <p><label>Nhập từ file Excell</label></p>
                            <input type="file" class="form-control" name="file_excell">
                        </div>


                        <button type="submit" class="btn btn-default">Nhập</button>
                        <button type="reset" class="btn btn-default btn-mleft">Hủy</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Export Order</div>
                    <a href="/orders/xml">Download XML</a>
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>
</div>


@endsection
