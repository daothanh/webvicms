<?php

namespace Modules\Tag\Http\Controllers\Api;

use Modules\Tag\Repositories\TagRepository;
use Modules\Core\Http\Controllers\ApiController;
use Modules\Tag\Http\Requests\CreateTagRequest;
use Modules\Tag\Transformers\TagTransformer;
use Illuminate\Http\Request;

class TagController extends ApiController
{
    protected $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function index(Request $request)
    {
        if ($request->get('columns') !== null) {
            // For datatables.net
            $tags = $this->tagRepository->serverPagingFor($this->convertDataTableParams($request));
            $output = [
                "draw" => $request->get('draw'),
                "recordsTotal" => $tags->total(),
                "recordsFiltered" => $tags->total(),
                'data' => TagTransformer::collection($tags),
            ];
            return response()->json($output);
        }
        return TagTransformer::collection($this->tagRepository->serverPagingFor($request, ['user']));
    }

    public function create(CreateTagRequest $request)
    {
        $data = $request->all();
        $data['namespace'] = str_replace('/', '\\', $data['namespace']);
        $tag = $this->tagRepository->create($data);

        return new TagTransformer($tag);
    }

    public function delete($id)
    {
        $tag = $this->tagRepository->find($id);
        $this->tagRepository->destroy($tag);
        return response()->json(['error' => false]);
    }
}
