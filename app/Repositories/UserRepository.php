<?php

namespace App\Repositories;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use \Illuminate\Database\QueryException;

class UserRepository {


    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function create(array $data)
    {
        try {
            $user = new User([
                    'email' => $data['email'],
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'password' => bcrypt($data['password']),
            ]);

            $user->email_verified_at = now();

            $user->save();

            $user->roles()->attach(Role::where('id', $data['roles'])->first());

            if (isset($data['usermeta']) && !empty($data['usermeta'])) {
                $user->meta()->delete();

                $this->syncMeta($data['usermeta'], $user);
            }

            event(new Registered($user));

            return $user;

        } catch (QueryException $e) {
            throw $e;
        }

    }


    public function find($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            throw $ex;
        }
    }

    public function update(array $data, $id)
    {
        $user = $this->find($id);

        try {
            $user->update($data);

            // if request is comming from frontend.
            if (isset($data['roles'])) {
                $user->roles()->detach();

                $user->roles()->attach(Role::where('id', $data['roles'])->first());
            }

            if (isset($data['usermeta']) && !empty($data['usermeta'])) {
                $user->meta()->delete();

                $this->syncMeta($data['usermeta'], $user);
            }

            return $user;

        } catch (\Exception $exc) {
            throw $exc;
        }
    }

    public function delete($id)
    {
        $user = $this->find($id);

        return $user->delete();
    }


    private function syncMeta($data, $user) {
        // Loop through all the meta keys we're looking for
        foreach ($data as $key => $value) {
            if (empty($value)) {
                continue;
            }

            $newMeta = new \App\Models\UserMeta(['key' => $key]);
            $meta = $newMeta->user()->associate($user);

            if (is_array($value)) {
                $value = serialize($value);
            }

            $meta->value = $value;
            $meta->save();
        }
    }

}
