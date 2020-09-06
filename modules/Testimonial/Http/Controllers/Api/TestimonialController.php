<?php
namespace Modules\Testimonial\Http\Controllers\Api;

use Modules\Testimonial\Entities\Testimonial;
use Modules\Testimonial\Repositories\TestimonialRepository;
use Modules\Core\Http\Controllers\ApiController;
use Modules\Testimonial\Transformers\FullApiTestimonialTransformer;
use Illuminate\Http\Request;

class TestimonialController extends ApiController
{
    protected $testimonialRepository;
    public function __construct(TestimonialRepository $testimonialRepository)
    {
        $this->testimonialRepository = $testimonialRepository;
    }

    public function index(Request $request)
    {
        if ($request->get('columns') !== null) {
            // For datatables.net
            $testimonials  = $this->testimonialRepository->serverPagingFor($this->convertDataTableParams($request));
            $output = [
                "draw"            => $request->get('draw'),
                "recordsTotal"    => $testimonials->total(),
                "recordsFiltered" => $testimonials->total(),
                'data'            => FullApiTestimonialTransformer::collection($testimonials),
            ];
            return response()->json($output);
        }
        return FullApiTestimonialTransformer::collection($this->testimonialRepository->serverPagingFor($request, ['user']));
    }

    public function delete(Testimonial $testimonial)
    {
        $this->testimonialRepository->destroy($testimonial);
        return response()->json(['error' => false]);
    }

    public function forceDelete($testimonialId)
    {
        $testimonial = $this->testimonialRepository->findDeletedTestimonial($testimonialId);
        $this->testimonialRepository->forceDestroy($testimonial);
        return response()->json(['error' => false]);
    }

    public function restore($testimonialId)
    {
        $testimonial = $this->testimonialRepository->findDeletedTestimonial($testimonialId);
        $testimonial->restore();
        return response()->json(['error' => false]);
    }
}
