<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            
           .title {
            font-family: Dejavu Sans, sans-serif;
        }
        </style>
    </head>
    <body>
        {{-- Form này chỉ có chức năng làm view để gửi email (Mail::send(view, data, function)) --}}
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    {{-- {!! Form::open(array('route' => 'front.fb', 'class' => '')) !!}
                    <div>
                        <label  class="email">Your name</label>
                            {!! Form::text('name', null, ['class' => 'input-text', 'placeholder'=>"Your name"]) !!}
                    </div><div>
                        <label  class="email">Your email</label>
                            {!! Form::text('email', null, ['class' => 'input-text', 'placeholder'=>"Your email"]) !!}
                    </div><div>
                        <label class="email">Comments</label>
                            {!! Form::textarea('comment', null, ['class' => 'tarea', 'rows'=>"5"]) !!}
                    </div><div class="send">
                        {!! Form::submit('Send', ['class' => 'button']) !!}
                    </div>
                    {!! Form::close() !!} --}}
                </div>
            </div>
        </div>
    </body>
</html>