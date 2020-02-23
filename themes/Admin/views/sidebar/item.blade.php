
<li class="nav-item @if($item->hasItems()) has-treeview  @if($active) menu-open @endif @endif @if($item->getItemClass()){{ $item->getItemClass() }}@endif">
    <a href=" @if($item->hasItems()) javascript:; @else {{ $item->getUrl() }} @endif" class="nav-link @if($active) active @endif"
       @if($item->getNewTab())target="_blank"@endif>
        <i class="{{ $item->getIcon() }}"></i>
        <p>{{ $item->getName() }}
        @if($item->hasItems())
            <i class="right fas fa-angle-left"></i>
        @endif
        </p>
    </a>
    @if($item->hasItems())
        <ul class="nav nav-treeview">
            @foreach($items as $item)
                {!! $item !!}
            @endforeach
        </ul>
    @endif
</li>
