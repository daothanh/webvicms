<?php


namespace Modules\Commerce\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Commerce\Repositories\CartRepository;
use Modules\Commerce\Support\Cart;

class CartController extends \Modules\Core\Http\Controllers\Controller
{
    /**
     * @var CartRepository
     */
    protected $repository;

    /**
     * @var Cart
     */
    protected $cart;

    public function __construct(CartRepository $cartRepository)
    {
        $this->repository = $cartRepository;
        $items = [];
        if (session()->has('cart')) {
            $items = session()->get("cart");
        } else {
            if (\Auth::check()) {
                $cart = $this->repository->newQueryBuilder()
                    ->where('user_id', '=', \Auth::user())
                    ->where('status', '=', 'new')
                    ->first();
                if ($cart && $cart->items) {
                    $items = unserialize($cart->items);
                }
            }
        }
        $this->cart = new Cart($items);
    }

    public function index() {
        return $this->view('commerce::cart', ['cart' => $this->cart]);
    }

    public function book() {
        return $this->view('commerce::book_form', ['cart' => $this->cart]);
    }

    public function bookComplete() {

    }

    public function addToCart(Request $request) {
        $data = $request->all();
        $rules = [
            'name' => 'required',
            'qty' => 'required|numeric'
        ];
        $validator = \Validator::make($data, $rules);
        $validator->setAttributeNames([
            'name' => 'Tên sản phẩm',
            'qty' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }
        $this->cart->addFromArray($request->only(['name', 'qty']));
        $request->session()->put('cart', $this->cart->getItems());
        return redirect()->route('cart');
    }
}
