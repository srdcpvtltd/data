<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Arr;
use Laracasts\Flash\Flash;
use App\Http\Controllers\Controller;
use App\Models\Term;
use App\Models\Service;
use App\Services\CategoriesService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminCategoryController extends Controller
{

    protected $data = [];

    public function __construct(Request $request, CategoriesService $CategoriesService )
    {
        $this->CategoriesService = $CategoriesService;

        $this->data['title'] = 'Categories';
        $this->data['categories'] = $this->paginate(CategoryList($request->input('s')), 15);
        $this->data['categoryArray'] = Arr::prepend(CategoryArray(), 'Select Parent', '0');
        $this->data['tags'] = Term::where('taxonomy', 'tag')->pluck('name', 'id');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.category.create', $this->data);
    }


    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request )
    {
        return view('admin.category.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $category = $this->CategoriesService->create($request->all());
        } catch (\Exception $ex) {
            return redirect()->back()
                    ->withInput()
                    ->withErrors($ex->getMessage());
        }

        if (!isset($category->id)) {
            \App::abort(500, 'The Category was not saved. Please try again!');
        }

        Flash::success('Category added successfully.');
        return redirect()->route('ch-admin.category.edit', [$category->id]);
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
        $category = Term::findOrFail($id);
        $this->data['title'] = 'Edit Category';
        $this->data['category'] = $category;
        return view('admin.category.edit', $this->data);
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
            'name' => 'required'
        ]);

        $this->CategoriesService->update($request->all(), $id);

        Flash::success('Category updated successfully.');

        return redirect()->route('ch-admin.category.edit', [$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if ( $this->CategoriesService->delete($id)  ) {

            Flash::success('Category deleted successfully.');

            return redirect()->route('ch-admin.category.index');

        } else {
            return redirect()->back()->withErrors('Operation failed. Please try again.');
        }

    }
}
