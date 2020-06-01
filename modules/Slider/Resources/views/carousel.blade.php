@if($slider->items && $slider->items->isNotEmpty())
	<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
	    <div class="carousel-inner">
	        @foreach($slider->items as $index => $item)
	        	@if($item->image)
		            <div class="carousel-item @if($index === 0) active @endif">
		                <img class="d-block w-100"
		                     src="{{ $item->image->path->getUrl() }}?auto=yes&bg=777&fg=555&text={{ $item->title }}" alt="{{ $item->title }}">
		            </div>
	            @endif
	        @endforeach
	    </div>
	    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
	        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
	        <span class="sr-only">Previous</span>
	    </a>
	    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
	        <span class="carousel-control-next-icon" aria-hidden="true"></span>
	        <span class="sr-only">Next</span>
	    </a>
	</div>
@endif