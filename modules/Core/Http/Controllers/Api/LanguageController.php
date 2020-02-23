<?php


namespace Modules\Core\Http\Controllers\Api;


use Illuminate\Http\Request;
use Modules\Core\Entities\Language;
use Modules\Core\Http\Controllers\ApiController;
use Modules\Core\Transformers\LanguageTransformer;

class LanguageController extends ApiController
{
    public function index(Request $request)
    {
        $request = $this->convertDataTableParams($request);
        $languages = Language::query()->orderBy('default', 'desc')->orderBy('status', 'asc');
        if ($request->get('search') !== null) {
            // For datatables.net
            if ($request->get('search')) {
                $languages->where('name', 'LIKE', "%{$request->get('search')}%");
            }
            $languages = $languages->paginate($request->get('per_page', 25));
            $output = [
                "draw"            => $request->get('draw'),
                "recordsTotal"    => $languages->total(),
                "recordsFiltered" => $languages->total(),
                'data'            => LanguageTransformer::collection($languages),
            ];
            return response()->json($output);
        }
        $languages = $languages->paginate($request->get('per_page', 20));
        return LanguageTransformer::collection($languages);
    }

    public function toggleStatus($id)
    {
        $language = Language::find($id);

        if (!$language) {
            abort(404);
        }
        if ($language->default) {
            $language->status = 'Active';
        } else {
            $language->status = $language->status === 'Active' ? 'Inactive' : 'Active';
        }
        $language->save();
        \Cache::clear();
        return new LanguageTransformer($language);
    }

    public function makeDefault($id)
    {
        $language = Language::find($id);

        if (!$language) {
            abort(404);
        }

        $default = Language::where('default', '=', 1)->first();
        $default->default = 0;
        $default->save();

        $language->default = 1;
        $language->status = 'Active';
        $language->save();

        \Cache::clear();
        return new LanguageTransformer($language);
    }
}
