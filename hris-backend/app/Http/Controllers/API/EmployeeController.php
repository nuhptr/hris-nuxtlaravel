<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use Exception;

class EmployeeController extends Controller
{
    // fetch employee
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $email = $request->input('email');
        $age = $request->input('age');
        $phone = $request->input('phone');
        $teamId = $request->input('team_id');
        $roleId = $request->input('role_id');
        $companyId = $request->input('company_id');
        $limit = $request->input('limit', 10);

        $employeeQuery = Employee::query();

        // get single data
        if ($id) {
            $employee = $employeeQuery->with(['team', 'role'])->find($id);

            if ($employee) {
                return ResponseFormatter::success(
                    $employee,
                    'Employee Found'
                );
            }

            return ResponseFormatter::error(
                'Employee with team and role not found',
                404
            );
        }

        // get multiple data
        $employees = $employeeQuery;

        if ($name) {
            $employees->where('name', 'like', '%' . $name . '%');
        }

        if ($email) {
            $employees->where('email', $email);
        }

        if ($age) {
            $employees->where('age', $age);
        }

        if ($phone) {
            $employees->where('phone', 'like', '%' . $phone . '%');
        }

        if ($roleId) {
            $employees->where('role_id', $roleId);
        }

        if ($teamId) {
            $employees->where('team_id', $teamId);
        }

        if ($companyId) {
            // get the company id from the team relationship
            $employees->whereHas('team', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            });
        }

        return ResponseFormatter::success(
            $employees->paginate($limit),
            'Employees found'
        );
    }

    // create employee
    public function create(CreateEmployeeRequest $request)
    {
        try {
            // upload foto
            if ($request->hasFile('photo')) {
                $requestPhoto = $request->file('photo')->getClientOriginalName();
                $originalPhoto = Str::random(6) . '_' . $requestPhoto;

                $path = $request->file('photo')->storeAs('public/photos', $originalPhoto);

                // delete previous photo
                Storage::delete('public/photos/', $originalPhoto);
            }

            // create employee
            $employee = Employee::create([
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'age' => $request->age,
                'phone' => $request->phone,
                'photo' => isset($path) ? $path : '',
                'team_id' => $request->team_id,
                'role_id' => $request->role_id,
            ]);

            return ResponseFormatter::success(
                $employee,
                'Employee created'
            );
        } catch (Exception $error) {
            return ResponseFormatter::error(
                $error,
                'Employee failed to create'
            );
        }
    }

    // update employee
    public function update(UpdateEmployeeRequest $request, $id)
    {
        try {
            // get employee
            $employee = Employee::find($id);

            // check employee exist
            if (!$employee) {
                throw new Exception('Employee not found');
            }

            // upload foto
            if ($request->hasFile('photo')) {
                $requestPhoto = $request->file('photo')->getClientOriginalName();
                $originalPhoto = Str::random(6) . '_' . $requestPhoto;

                $path = $request->file('photo')->storeAs('public/photos', $originalPhoto);

                // delete previous photo
                Storage::delete('public/photos/', $originalPhoto);
            }

            // update employee
            $employee->update([
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'age' => $request->age,
                'phone' => $request->phone,
                'photo' => isset($path) ? $path : $employee->photo,
                'team_id' => $request->team_id,
                'role_id' => $request->role_id,
            ]);

            return ResponseFormatter::success(
                $employee,
                'Employee updated'
            );
        } catch (Exception $error) {
            return ResponseFormatter::error(
                $error->getMessage(),
                'Employee failed to update'
            );
        }
    }

    // delete employee
    public function destroy($id)
    {
        try {
            // get employee
            $employee = Employee::find($id);

            // check employee exist
            if (!$employee) {
                throw new Exception('Employee not found');
            }

            // delete employee
            $employee->delete();

            return ResponseFormatter::success(
                'Employee deleted'
            );
        } catch (Exception $error) {
            return ResponseFormatter::error(
                $error->getMessage(),
                'Employee failed to delete'
            );
        }
    }
}
