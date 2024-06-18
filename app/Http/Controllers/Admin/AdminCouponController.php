<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coupon;
use App\Models\Form;
use App\Repositories\CouponRepository;
use App\Services\ProductService;
use Illuminate\Http\Request;
use App\Http\Requests;
use Laracasts\Flash\Flash;
use App\Http\Controllers\Controller;
use App\Services\FormService;


class AdminCouponController extends Controller
{

    protected $data = [];
    protected $couponRepository = [];
    /**
     * @var ProductService
     */
    private $productService;

    public function __construct(Request $request, CouponRepository $couponRepository, ProductService $productService)
    {
        $this->couponRepository = $couponRepository;

        $this->productService = $productService;

        $this->data['title'] = 'Discount Coupons';
        if ( $request->input('s') ) {
            $s = $request->input('s');
            $this->data['coupons'] = Coupon::latest('id')->where(function ($query) use ( $s ){
                $query->where('name', 'LIKE', '%'.$s.'%');
            })->paginate(15);
        } else {
            $this->data['coupons'] = Coupon::latest('id')->paginate(15);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.coupon.index', $this->data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request )
    {
        $this->data['products'] = $this->productService->all()->pluck('name', 'id');

        return view('admin.coupon.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|unique:coupons',
            'amount' => 'required|numeric|min:0|not_in:0',
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d',
            'max_uses' => 'nullable|numeric|min:0|not_in:0',
        ]);

        try {
            $coupon = $this->couponRepository->create($request->except('products'), $request->only('products'));
        } catch (\Exception $ex) {
            return redirect()->back()
                ->withInput()
                ->withErrors($ex->getMessage());
        }

        Flash::success('Coupon added successfully.');

        return redirect()->route('ch-admin.coupon.edit', [$coupon->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->data['title'] = 'Edit Coupon';
        $this->data['coupon'] = $this->couponRepository->getById($id);
        $this->data['products'] = $this->productService->all()->pluck('title', 'id');

        return view('admin.coupon.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|unique:coupons,code,'.$id,
            'amount' => 'required|numeric|min:0|not_in:0',
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d',
            'max_uses' => 'nullable|numeric|min:0|not_in:0',
        ]);

        $this->couponRepository->update($request->except('_method', '_token', 'products'), $request->only('products'), $id);

        Flash::success('Coupon updated successfully.');

        return redirect()->route('ch-admin.coupon.edit', [$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->couponRepository->deleteById($id);

        Flash::success('Coupon deleted successfully.');

        return redirect()->route('ch-admin.coupon.index');
    }
}
