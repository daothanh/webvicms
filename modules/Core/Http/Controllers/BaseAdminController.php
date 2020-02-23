<?php
namespace Modules\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Modules\Core\Support\Breadcrumb;

class BaseAdminController extends Controller
{
    use SEOToolsTrait;

    /** @var \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard|mixed  */
    protected $guard;

    protected $breadcrumb;

    public function __construct()
    {
        $this->breadcrumb = new Breadcrumb();
        $this->breadcrumb->addItem(__('Home'), route('admin'));
        $this->guard = $this->guard();
        $this->middleware(['admin']);
    }

    public function view($name, $data = [], $mergeData = [])
    {
        $namespace = \Settings::get('website', 'backend_theme', 'admin');
        $themeView = "$namespace::";

        if (strpos($name, '::')) {
            $themeView .= "modules.".str_replace("::", ".", $name);
        } else {
            $themeView .= $name;
        }

        \View::share('themeName', $namespace);

        $data['breadcrumb'] = $this->breadcrumb->render();

        if (\View::exists($themeView)) {
            return view($themeView, $data, $mergeData);
        }

        return view($name, $data, $mergeData);
    }

    /**
     * @param string|null $guard
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard|mixed
     */
    protected function guard($guard = null)
    {
        return Auth::guard($guard);
    }

    protected function convertDataTableParams(Request $request)
    {
        $cols = $request->get('columns');
        if (!empty($cols)) {
            $start = $request->get('start', 0);
            $length = $request->get('length', 25);
            $page = ($start / $length) + 1;

            $requestData = [
                'per_page' => $length,
                'page' => $page,
                'draw' => $request->get('draw'),
                'order_by' => $cols[$request->get('order')[0]['column']]['data'],
                'order' => $request->get('order')[0]['dir'] === 'asc' ? 'ascending' : 'descending',
                'search' => $request->get('search')['value']
            ];
            foreach ($cols as $col) {
                if ($col['searchable'] && isset($col['search']['value'])) {
                    $requestData[$col['data']] = $col['search']['value'];
                }
            }
            $request->merge($requestData);
        }
        return $request;
    }
}
