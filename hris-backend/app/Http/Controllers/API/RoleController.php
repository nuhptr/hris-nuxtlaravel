<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Role;
use App\Helpers\ResponseFormatter;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Exception;

class RoleController extends Controller
{
    // get all data teams
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);
        $withResponsibility = $request->input('with_responsibility', false);

        $roleQuery = Role::query();

        // get single data
        if ($id) {
            $roles = $roleQuery->with('responsibilities')->find($id);

            if ($roles) {
                return ResponseFormatter::success(
                    $roles,
                    'Role Found!'
                );
            }

            return ResponseFormatter::error(
                'Role not found',
                404
            );
        }

        // get multiple data
        $roles = $roleQuery->where('company_id', $request->company_id);

        // hris.com/api/company?name=takeshi
        if ($name) {
            $roles->where('name', 'like', '%' . $name . '%');
        }

        if ($withResponsibility) {
            $roles->with('responsibilities');
        }

        return ResponseFormatter::success(
            $roles->paginate($limit),
            'Roles found!'
        );
    }

    // create role
    public function create(CreateRoleRequest $request)
    {
        try {
            // create team
            $role = Role::create([
                'name' => $request->name,
                'company_id' => $request->company_id,
            ]);

            if (!$role) {
                throw new Exception('Failed to create role!');
            } // add user data to company response

            return ResponseFormatter::success(
                $role,
                'Role Created'
            );
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(),  500);
        }
    }

    // update role
    public function update(UpdateRoleRequest $request, $id)
    {
        try {
            // get role
            $role = Role::find($id);

            // check team if exist
            if (!$role) {
                throw new Exception('Team not found!');
            }

            // update team
            $role->update([
                'name' => $request->name,
                'company_id' => $request->company_id,
            ]);

            return ResponseFormatter::success(
                $role,
                'Role Updated!'
            );
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(),  500);
        }
    }

    // delete role
    public function destroy($id)
    {
        try {
            // get team
            $role = Role::find($id);

            // check if role exist
            if (!$role) {
                throw new Exception('Role not found');
            }

            // delete role
            $role->delete();

            return ResponseFormatter::success(
                'Role Deleted'
            );
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(),  500);
        }
    }
}
