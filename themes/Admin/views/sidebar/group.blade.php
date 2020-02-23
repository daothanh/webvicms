@if($group->shouldShowHeading())
    <li class="nav-header text-uppercase">{{ $group->getName() }}</li>
@endif

@foreach($items as $item)
    {!! $item !!}
@endforeach
