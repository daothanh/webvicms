@if($items->isNotEmpty())
    <ol class="breadcrumb float-sm-right">
        @foreach($items as $item)
            <li class="breadcrumb-item {{ $item->getItemClass() }}">
                @if($item->link)
                    <a href="{{ $item->link }}">{{ $item->title }}</a>
                @else
                    {{ $item->title }}
                @endif
            </li>
        @endforeach
    </ol>
@endif