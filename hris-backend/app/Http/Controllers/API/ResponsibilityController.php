<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Helpers\ResponseFormatter;
use App\Http\Requests\CreateResponsibilityRequest;
use App\Models\Responsibility;

class ResponsibilityController extends Controller
{
    // fetch all data responsibilities
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        $responsibilityQuery = Responsibility::query();

        // get single data
        if ($id) {
            $responsibilities = $responsibilityQuery->find($id);

            if ($responsibilities) {
                return ResponseFormatter::success(
                    $responsibilities,
                    'Responsibility Found'
                );
            }

            return ResponseFormatter::error(
                'Responsibility not found',
                404
            );
        }

        // get multiple data
        $responsibilities = $responsibilityQuery->where('role_id', $request->role_id);

        if ($name) {
            $responsibilities->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success(
            $responsibilities->paginate($limit),
            'Responsibilities found'
        );
    }

    // create responsibility
    public function create(CreateResponsibilityRequest $request)
    {
        try {
            // create responsibility
            $responsibility = Responsibility::create([
                'name' => $request->name,
                'role_id' => $request->role_id,
            ]);

            if (!$responsibility) {
                return ResponseFormatter::error(
                    'Responsibility failed to create',
                    400
                );
            }

            return ResponseFormatter::success(
                $responsibility,
                'Responsibility created'
            );
        } catch (Exception $error) {
            return ResponseFormatter::error(
                $error,
                'Responsibility failed to create'
            );
        }
    }


    // delete responsibilities
    public function destroy($id)
    {
        try {
            // get responsibility
            $responsibility = Responsibility::find($id);

            // check if responsibility exists
            if (!$responsibility) {
                return ResponseFormatter::error(
                    'Responsibility not found',
                    404
                );
            }

            // delete responsibility
            $responsibility->delete();

            return ResponseFormatter::success(
                'Responsibility deleted'
            );
        } catch (Exception $error) {
            return ResponseFormatter::error(
                $error,
                'Responsibility failed to delete'
            );
        }
    }
}
