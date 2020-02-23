{{ Form::open(['route' => 'admin.role.store', 'method' => 'post']) }}
<div class="tab-container">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active @if($errors->has('role.name') || $errors->has('role.guard'))is-invalid @endif"
               data-toggle="tab" href="#role" role="tab"
               aria-controls="pills-home" aria-selected="true">{{ __('Role information') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#permission" role="tab"
               aria-controls="permistion-tab">{{ __('Permission') }}</a>
        </li>
    </ul>
    <div class="tab-content pt-3 pb-3" id="pills-tabContent">
        <div class="tab-pane fade show active" id="role" role="tabpanel" aria-labelledby="role-tab">
            @if(!empty($role))
                {{ Form::hidden('role[id]', $role->id) }}
            @endif
            {{ Form::hidden('role[guard_name]', 'web') }}
            <div class="form-group @if($errors->has("role.name")) has-danger @endif">
                <label>{{ __('Role Name') }}</label>
                {{ Form::text('role[name]', old('role.name', !empty($role) ? $role->name : null), ['class' => 'form-control m-input']) }}
                <i class="form-group__bar"></i>
                @if($errors->has('role.name'))
                    <div class="form-control-feedback">
                        {{ $errors->first('role.name') }}
                    </div>
                @endif
            </div>
            <?php
            $availableLocales = collect(languages())->pluck('native', 'code');
            ?>
            @foreach($availableLocales as $locale => $language)
                <div class="form-group @if($errors->has("role.$locale.name")) has-danger @endif">
                    <label>{{ __('Title') }} ({{ $language}})</label>
                    {{ Form::text('role['.$locale.'][title]', old('role.'.$locale.'.name', !empty($role) && $role->translate($locale) ? $role->translate($locale)->title : null), ['class' => 'form-control m-input']) }}
                    <i class="form-group__bar"></i>
                    @if($errors->has('role.'.$locale.'.name'))
                        <div class="form-control-feedback">
                            {{ $errors->first('role.'.$locale.'.name') }}
                        </div>
                    @endif
                </div>
            @endforeach

        </div>
        <div class="tab-pane fade" id="permission" role="tabpanel" aria-labelledby="permission-tab">
            <div class="form-group">
                @foreach(collect(config('auth.guards'))->keys() as $guard)
                    <?php $permissions = \Modules\User\Entities\Permission::where('guard_name', '=', 'web')->get(); ?>
                    @if($permissions && $permissions->isNotEmpty())
                        <div class="guard guard-{{ $guard }}"
                             @if(old('role.guard_name', !empty($role)? $role->guard_name : 'web') !== $guard) style="display: none"@endif>
                            {{--<h3>{{ $guard }}</h3>--}}
                            @foreach($permissions as $permission)
                                <div>
                                    <label class="m-checkbox" for="permission-{{$permission->id}}">
                                        <input type="checkbox" name="permissions[{{$permission->id}}]"
                                               value="{{ $permission->id }}" id="permission-{{$permission->id}}"
                                               @if(old('permissions.'.$permission->id, !empty($role) && $role->guard_name == $guard && $role->hasPermissionTo($permission))) checked="checked" @endif>
                                        {{ __($permission->title ?? $permission->name) }}
                                        <span></span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="text-center">
            <button class="btn btn-primary"><i class="la la-save"></i> {{ __('Save') }}</button>
        </div>
    </div>
</div>
{{ Form::close() }}
@push('js-stack')
    <script>
      $(function () {
        $('select#guard_name').change(function (event) {
          let guard = $(this).val();
          $('.guard').find('input').prop('checked', false);
          ;
          $('.guard').hide();
          $('.guard-' + guard).show();
        });
      })
    </script>
@endpush

