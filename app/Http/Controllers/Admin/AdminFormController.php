<?php

namespace App\Http\Controllers\Admin;

use App\Models\Form;
use Illuminate\Http\Request;
use App\Http\Requests;
use Laracasts\Flash\Flash;
use App\Http\Controllers\Controller;
use App\Services\FormService;


class AdminFormController extends Controller
{

    protected $data = [];
    protected $formService = [];

    public function __construct(Request $request, FormService $formService)
    {
        $this->formService = $formService;

        $this->data['title'] = 'Forms';
        if ( $request->input('s') ) {
            $s = $request->input('s');
            $this->data['forms'] = Form::latest('id')->where(function ($query) use ( $s ){
                $query->where('name', 'LIKE', '%'.$s.'%');
            })->paginate(15);
        } else {
            $this->data['forms'] = Form::latest('id')->paginate(15);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.form.index', $this->data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request )
    {
        return view('admin.form.create', $this->data);
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
            'name' => 'required|string'
        ]);

        try {
            $form = $this->formService->create($request->only('name', 'content', 'raw_content'));
        } catch (\Exception $ex) {
            return redirect()->back()
                    ->withInput()
                    ->withErrors($ex->getMessage());
        }

        if (!isset($form->id)) {
            \App::abort(500, 'The Form was not saved. Please try again!');
        }

        Flash::success('Form added successfully.');
        return redirect()->route('ch-admin.form.edit', [$form->id]);
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
        $form = Form::findOrFail($id);
        $this->data['title'] = 'Edit Form';
        $this->data['form'] = $form;
        return view('admin.form.edit', $this->data);
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
            'name' => 'required|string'
        ]);

        $form = $this->formService->update($request->only('name', 'content', 'raw_content'), $id);

        Flash::success('Form updated successfully.');

        return redirect()->route('ch-admin.form.edit', [$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if ( $this->formService->delete($id)  ) {

            Flash::success('Form deleted successfully.');

            return redirect()->route('ch-admin.form.index');

        } else {
            return redirect()->back()->withErrors('Operation failed. Please try again.');
        }

    }
}
