@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Danh sách tài liệu</div>

                <div class="card-body">
                    <iframe src="https://drive.google.com/embeddedfolderview?id={{$id}}" style="width:100%; height:600px; border:0;" />
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
