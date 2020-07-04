<?php


namespace Modules\Commerce\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Commerce\Entities\CartBuyer;
use Modules\Commerce\Entities\CartDeliveryAddress;
use Modules\Commerce\Events\BookWasCompleted;
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

    public function index()
    {
        return $this->view('commerce::cart', ['cart' => $this->cart]);
    }

    public function book(Request $request)
    {
        if ($request->isMethod('POST')) {
            $user = $request->user();
            \DB::beginTransaction();
            $data = [
                'user_id' => $user ? $user->id : null,
                'subtotal' => $this->cart->subtotal(),
                'total' => $this->cart->total(),
                'vat' => $this->cart->vat(),
                'items' => $this->cart->toJson(),
                'status' => 'complete'
            ];
            $cart = $this->repository->create($data);
            if (\Arr::get($request->all(), 'delivery') !== null) {
                $deliveryData = array_merge($request->get('delivery'), ['user_id' => $user ? $user->id : null, 'cart_id' => $cart->id]);

                $rules = [
                    'cart_id',
                    'name' => 'required',
                    'phone' => 'required',
                    'email' => 'required|email',
                    'address' => 'required',
                    'province_id',
                    'district_id'
                ];
                $validator = \Validator::make($deliveryData, $rules);
                if ($validator->fails()) {
                    \DB::rollBack();
                    return back()->withInput()->withErrors($validator);
                }
                CartDeliveryAddress::create($deliveryData);
            }

            if (\Arr::get($request->all(), 'buyer') !== null) {
                $buyerData = array_merge($request->get('buyer'), ['user_id' => $user ? $user->id : null, 'cart_id' => $cart->id]);
                $rules = [
                    'name' => 'required',
                    'phone' => 'required',
//                    'user_id' => 'required',
                    'cart_id' => 'required',
                    'email' => 'required|email'
                ];
                $validator = \Validator::make($buyerData, $rules);
                if ($validator->fails()) {
                    \DB::rollBack();
                    return back()->withInput()->withErrors($validator);
                }
                CartBuyer::create($buyerData);
            }
            \DB::commit();
            $this->cart->clear();
            $request->session()->forget('cart');
            $cart->load(['buyer', 'deliveryAddress', 'user']);
            event(new BookWasCompleted($cart, $request->all()));
            return redirect()->route('cart.book.complete')->withSuccess(__('commerce::car.book_successfully'));
        }
        return $this->view('commerce::book_form', ['cart' => $this->cart]);
    }

    public function bookComplete()
    {
        return $this->view('commerce::book_completely');
    }

    public function addToCart(Request $request)
    {
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
