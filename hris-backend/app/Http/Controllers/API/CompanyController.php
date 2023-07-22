<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Helpers\ResponseFormatter;
use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanRequest;
use App\Models\User;
use App\Models\Company;

class CompanyController extends Controller
{
    // get all data companies
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        $companyQuery =
            Company::with(['users'])->whereHas('users', function ($query) {
                $query->where('user_id', Auth::id());
            });

        // get single data
        if ($id) {
            $company = $companyQuery->find($id);

            if ($company) {
                return ResponseFormatter::success(
                    $company,
                    'Company Found'
                );
            }

            return ResponseFormatter::error(
                'Company not found',
                404
            );
        }

        // get multiple data
        $companies = $companyQuery;

        // hris.com/api/company?name=takeshi
        if ($name) {
            $companies->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success(
            $companies->paginate($limit),
            'Companies found'
        );
    }

    // create company
    public function create(CreateCompanyRequest $request)
    {
        try {
            // upload foto
            if ($request->hasFile('logo')) {
                $requestLogo = $request->file('logo')->getClientOriginalName();
                $originalLogo = Str::random(6) . '_' . $requestLogo;

                $path = $request->file('logo')->storeAs('public/logos', $originalLogo);
            }

            // create company
            $company = Company::create([
                'name' => $request->name,
                'logo' => isset($path) ? $path : '',
            ]);

            if (!$company) {
                throw new Exception('Failed to create company');
            }

            // attach company to user
            $user = User::find(Auth::id());
            $user->companies()->attach($company->id);

            $company->load('users'); // add user data to company response

            return ResponseFormatter::success(
                $company,
                'Company Created'
            );
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(),  500);
        }
    }

    // update company
    public function update(UpdateCompanRequest $request, $id)
    {
        try {
            // get company
            $company = Company::find($id);

            // check company if exist
            if (!$company) {
                throw new Exception('Company not found');
            }

            // upload foto
            if ($request->hasFile('logo')) {
                $requestLogo = $request->file('logo')->getClientOriginalName();
                $originalLogo = Str::random(6) . '_' . $requestLogo;

                $path = $request->file('logo')->storeAs('public/logos', $originalLogo);

                // delete previous logo
                Storage::delete('public/logos/', $originalLogo);
            }

            // update company
            $company->update([
                'name' => $request->name,
                // check if logo exist
                'logo' => isset($path) ? $path : $company->logo,
            ]);
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(),  500);
        }
    }
}
