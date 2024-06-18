<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Requests;
use Laracasts\Flash\Flash;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Country;
use App\Models\State;
use App\Services\UserService;
use App\Http\Requests\User\UserCreateRequest;

class AdminUserController extends Controller {

    protected $data;
    
    private $userService;

    public function __construct( Request $request, UserService $userService ) {

        $this->userService = $userService;
        
        $this->data['title'] = 'Users';
        $this->data['roles'] = Role::all()->pluck('display_name', 'id')->all();
        if ( $request->input('s') ) {
            $s = $request->input('s');
            $this->data['users'] = User::latest('id')->where(function ($query) use ( $s ){
                $query->where('username', 'LIKE', '%'.$s.'%')
                    ->orWhere('email', 'LIKE', '%'.$s.'%');
            })->paginate(2);
        } else {
            $this->data['users'] = User::latest('id')->paginate(15);
        }
        $this->data['states'] = [];
        $this->data['countries'] = Country::all()->pluck( 'name', 'id')->toArray();

    }

    public function index() {
        return view('admin.user.index', $this->data);
    }

    public function create(Request $request) {
        $this->data['title']        = 'Add new User';
        return view('admin.user.create', $this->data);
    }

    public function store(UserCreateRequest $request) 
    {
        try {
            $user = $this->userService->create($request->all());
        } catch (\Exception $ex) {
            return redirect()->back()
                    ->withInput()
                    ->withErrors($ex->getMessage());
        }
        
        Flash::success('User added successfully.');
        
        return redirect()->route('ch-admin.user.edit', [$user->id]);
    }



    public function edit(Request $request, $id) 
    {
        $this->data['title'] = 'Edit User';
        $this->data['user'] = $this->userService->find($id);
        
        if (isset($this->data['user']->billing_country)) {
            $this->data['states'] = $this->data['user']->billing_country->states->pluck('name', 'id');
        }
        
        if (old('usermeta.billing_country')) {
            $this->data['states'] = State::where('country_id', old('usermeta.billing_country'))->pluck('name', 'id');
        }
        
        return view('admin.user.edit', $this->data);
    }



    public function update(UserUpdateRequest $request, $id) {
        
        try {
            $this->userService->update($request->all(), $id);
        } catch (\Exception $ex) {
            return redirect()->back()
                    ->withInput()
                    ->withErrors($ex->getMessage());
        }
        
        Flash::success('User updated successfully.');

        return redirect()->route('ch-admin.user.edit', [$id]);
    }



    public function destroy(User $user )
    {
        if ( $this->userService->delete($user->id) ) {
            Flash::message('User#'.$user->id.' deleted successfully.');

            return redirect()->route('ch-admin.user.index');
        } else {
            return redirect()->back()->withErrors('Operation failed. Please try again.');
        }
    }
    
}
