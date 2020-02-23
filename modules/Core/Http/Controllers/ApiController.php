<?php
namespace Modules\Core\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Get the token guard
     *
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return \Auth::guard('api');
    }

    /**
     * Convert all filter params of DataTables.net to normal params
     *
     * @param Request $request
     * @return Request
     */
    protected function convertDataTableParams(Request $request)
    {
        $cols = $request->get('columns');
        $data = $request->except(['columns']);
        if (!empty($cols)) {
            $start = $request->get('start', 0);
            $length = $request->get('length', 25);
            $page = ($start / $length) + 1;
            $search = $request->get('search');
            $requestData = [
                'per_page' => $length,
                'page' => $page,
                'draw' => $request->get('draw'),
                'order_by' => $cols[$request->get('order')[0]['column']]['data'],
                'order' => $request->get('order')[0]['dir'] === 'asc' ? 'ascending' : 'descending',
                'search' => is_array($search) ? $search['value'] : $search
            ];
            foreach ($cols as $col) {
                if ($col['searchable'] && isset($col['search']['value'])) {
                    $requestData[$col['data']] = $col['search']['value'];
                }
            }
            $data = array_merge($data, $requestData);
            $request->replace($data);
        }
        return $request;
    }
}
