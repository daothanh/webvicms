@extends($themeName.'::layouts.master')

@section('content')
    <div class="contact-page p-mt">
        <div id="map">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.67740418197!2d105.82154441483922!3d21.005564686010935!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ac815b8f92fb%3A0xa76b2640793a9043!2sTay+Son+Mipec+Tower!5e0!3m2!1sen!2s!4v1564386815175!5m2!1sen!2s"
                width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>
        <div class="container">

            <div class="row">
                <div class="col-md-9">
                    <h1 class="title-2">Contact Us</h1>
                    @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    {{ Form::open(['url' => route('contact.send'), 'method' => 'post']) }}
                    <?php $locale = locale(); ?>
                    <div class="company">
                        <p style="text-transform: uppercase"><i class="fa fa-building-o"></i> {{ $company['name'][$locale] }}</p>
                        <p><i class="fa fa-location-arrow"></i> {{ $company['address'][$locale] }}</p>
                        <p><i class="fa fa-phone"></i> {{ $company['hotline'][$locale] }}
                        </p><p><i class="icon-envelope"></i><a href="mailto:{{ $company['email'][$locale] }}">{{ $company['email'][$locale] }}</a></p>
                        <p><i class="icon-globe"></i><a href="{{ $company['website'][$locale] }}">{{ $company['webiste'][$locale] }}</a></p>
                    </div>
                    <div class="form-group">
                        <label for="name">{{ __('core::core.labels.Name') }}</label>
                        {{ Form::text('name', old('name'), ['class' => 'form-control '.($errors->has('name') ? 'is-invalid' : '')]) }}
                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="name">{{ __('core::core.labels.Email') }}</label>
                        {{ Form::text('email', old('email'), ['class' => 'form-control '.($errors->has('email') ? 'is-invalid' : '')]) }}
                        @if($errors->has('email'))
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="name">{{ __('core::core.labels.Subject') }}</label>
                        {{ Form::text('subject', old('subject'), ['class' => 'form-control '.($errors->has('subject') ? 'is-invalid' : '')]) }}
                        @if($errors->has('subject'))
                            <div class="invalid-feedback">
                                {{ $errors->first('subject') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="content">{{ __('core::core.labels.Subject') }}</label>
                        {{ Form::textarea('content', old('content'), ['class' => 'form-control '.($errors->has('subject') ? 'is-invalid' : '')]) }}
                        @if($errors->has('content'))
                            <div class="invalid-feedback">
                                {{ $errors->first('content') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">Send</button>
                    </div>
                    {{ Form::close() }}
                </div>
                <div class="col-md-3">

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js-stack')
@endpush
