{{ Form::open(['route' => 'admin.user.store', 'method' => 'post', 'class' => 'm-form']) }}
<div class="tab-container mt-3 mb-3">
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="user" role="tabpanel" aria-labelledby="user-tab">
            @if(!empty($user))
                {{ Form::hidden('user[id]', $user->id) }}
            @endif
            <div class="form-group">
                @mediaSingle('picture', $user ?? null, __('Picture'))
                @if($errors->has('medias_single.picture'))
                    <div class="invalid-feedback">
                        {{ $errors->first('single_media.picture') }}
                    </div>
                @endif
            </div>
            <div class="form-group @if($errors->has("user.name")) has-danger @endif">
                <label>{{ __('Name') }}</label>
                {{ Form::text('user[name]', old('user.name', !empty($user) ? $user->name : null), ['class' => 'form-control m-input']) }}
                @if($errors->has('user.name'))
                    <div class="form-control-feedback">
                        {{ $errors->first('user.name') }}
                    </div>
                @endif
            </div>
            <div class="form-group @if($errors->has("user.email")) has-danger @endif">
                <label>{{__('E-Mail Address')}}</label>
                {{ Form::email('user[email]', old('user.email', !empty($user) ? $user->email : null), ['class' => 'form-control m-input']) }}
                @if($errors->has('user.email'))
                    <div class="form-control-feedback">
                        {{ $errors->first('user.email') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label>{{ __('Phone') }}</label>
                {{ Form::text('user[phone]', old('user.phone', !empty($user) ? $user->phone : null), ['class' => 'form-control m-input']) }}
            </div>
            <div class="form-group">
                <label>{{ __('Gender') }}</label>
                {{ Form::select('user[gender]', ['male' => __('Male'), 'female' => 'Female'], old('user.gender', !empty($user)? $user->gender : null), ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                <div class="m-checkbox-list">
                    <label class="m-checkbox" for="activated">
                        <input type="checkbox" name="user[activated]" value="1" id="activated"
                               @if(old('user.activated', !empty($user) && $user->activated)) checked="checked" @endif>
                        {{__('Activated')}}
                        <span></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="role" role="tabpanel" aria-labelledby="role-tab">
            <?php
            $roleLabels = [
                'admin' => 'Admin role',
                'user' => 'User role',
                'customer' => 'Customer role'
            ];
            ?>
            <div class="form-group">
                @foreach(\Modules\User\Entities\Role::all() as $role)
                    <div class="m-checkbox-list">
                        <label class="m-checkbox" for="role-{{$role->id}}">
                            <input type="checkbox" name="roles[{{$role->id}}]" value="{{ $role->name }}"
                                   id="role-{{$role->id}}"
                                   @if(old('roles.'.$role->id, !empty($user) && $user->hasRole($role->name))) checked="checked" @endif>
                            {{ !empty($roleLabels[$role->name]) ? __($roleLabels[$role->name]) : $role->name}}
                            <span></span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="tab-pane fade" id="new-password" role="tabpanel" aria-labelledby="new-password-tab">
            <div class="form-group @if($errors->has("password")) has-danger @endif">
                <label>{{ __('New password') }}</label>
                {{ Form::password('password', ['class' => 'form-control m-input']) }}
                @if($errors->has('password'))
                    <div class="form-control-feedback">
                        {{ $errors->first('password') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label>{{ __('New password confirmation') }}</label>
                {{ Form::password('password_confirmation', ['class' => 'form-control m-input']) }}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="text-center mt-3">
            <button class="btn btn-primary"><i class="la la-save"></i> {{ __('Save') }}</button>
        </div>
    </div>
</div>
{{ Form::close() }}
