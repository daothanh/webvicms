<?php

namespace Modules\Tag\Http\Controllers\Admin;

use Modules\Tag\Entities\Tag;
use Modules\Tag\Http\Requests\CreateTagRequest;
use Modules\Tag\Http\Requests\UpdateTagRequest;
use Modules\Tag\Contracts\TagManager;
use Modules\Tag\Repositories\TagRepository;
use Modules\Core\Http\Controllers\BaseAdminController;

class TagController extends BaseAdminController
{
    /**
     * @var TagRepository
     */
    private $tag;
    /**
     * @var TagManager
     */
    private $tagManager;

    public function __construct(TagRepository $tag, TagManager $tagManager)
    {
        parent::__construct();

        $this->tag = $tag;
        $this->tagManager = $tagManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$tags = $this->tag->all();

        return $this->view('tag::admin.tags.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $namespaces = $this->formatNamespaces($this->tagManager->getNamespaces());

        return $this->view('tag::admin.tags.create', compact('namespaces'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateTagRequest $request
     * @return Response
     */
    public function store(CreateTagRequest $request)
    {
        $this->tag->create($request->all());

        return redirect()->route('admin.tag.index')
            ->withSuccess(trans('tag::tags.messages.resource created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Tag $tag
     * @return Response
     */
    public function edit(Tag $tag)
    {
        $namespaces = $this->formatNamespaces($this->tagManager->getNamespaces());

        return $this->view('tag::admin.tags.edit', compact('tag', 'namespaces'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Tag $tag
     * @param  UpdateTagRequest $request
     * @return Response
     */
    public function update(UpdateTagRequest $request)
    {
        $tag = $this->tag->find($request->get('id'));
        $this->tag->update($tag, $request->all());

        return redirect()->route('admin.tag.index')
            ->withSuccess(trans('tag::tags.messages.Tag was updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Tag $tag
     * @return Response
     */
    public function destroy(Tag $tag)
    {
        $this->tag->destroy($tag);

        return redirect()->route('admin.tag.index')
            ->withSuccess(trans('tag::tags.messages.Tag was deleted'));
    }

    private function formatNamespaces(array $namespaces)
    {
        $new = [];
        foreach ($namespaces as $namespace) {
            $new[$namespace] = $namespace;
        }

        return $new;
    }
}
