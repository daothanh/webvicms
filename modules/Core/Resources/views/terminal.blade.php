@extends($themeName."::layouts.master")
@section('content')
    <div class="contact-page p-mt">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-50 mt-50">
                        {{ Form::open(['route' => 'terminal', 'method' => 'post']) }}
                        <div class="form-group">
                            <label for="">Command</label>
                            <input type="text" name="cmd" class="form-control" value="{{ old('cmd', $cmd ?? null) }}">
                        </div>
                        {{ Form::close() }}
                        @if($rs)
                            <h2 class="title">Results of  "{{ $cmd }}" comand</h2>
                            <pre>
                                {!! $rs !!}
                            </pre>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
