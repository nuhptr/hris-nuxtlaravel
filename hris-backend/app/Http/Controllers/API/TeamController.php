<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\Helpers\ResponseFormatter;
use App\Http\Requests\CreateTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Models\Team;

class TeamController extends Controller
{
    // get all data teams
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        $teamQuery = Team::withCount('employees');

        // get single data
        if ($id) {
            $teams = $teamQuery->find($id);

            if ($teams) {
                return ResponseFormatter::success(
                    $teams,
                    'Teams Found'
                );
            }

            return ResponseFormatter::error(
                'Teams not found',
                404
            );
        }

        // get multiple data
        $teams = $teamQuery->where('company_id', $request->company_id);

        // hris.com/api/company?name=takeshi
        if ($name) {
            $teams->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success(
            $teams->paginate($limit),
            'teams found'
        );
    }

    // create team
    public function create(CreateTeamRequest $request)
    {
        try {
            // upload foto
            if ($request->hasFile('icon')) {
                $requestIcon = $request->file('icon')->getClientOriginalName();
                $originalIcon = Str::random(6) . '_' . $requestIcon;

                $path = $request->file('icon')->storeAs('public/icons', $originalIcon);
            }

            // create team
            $team = Team::create([
                'name' => $request->name,
                'icon' => $path,
                'company_id' => $request->company_id,
            ]);

            if (!$team) {
                throw new Exception('Failed to create team');
            } // add user data to company response

            return ResponseFormatter::success(
                $team,
                'Team Created'
            );
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(),  500);
        }
    }

    // update team
    public function update(UpdateTeamRequest $request, $id)
    {
        try {
            // get team
            $team = Team::find($id);

            // check team if exist
            if (!$team) {
                throw new Exception('Team not found');
            }

            // upload foto
            if ($request->hasFile('icon')) {
                $requestIcon = $request->file('icon')->getClientOriginalName();
                $originalIcon = Str::random(6) . '_' . $requestIcon;

                $path = $request->file('icon')->storeAs('public/icons', $originalIcon);

                // delete previous icon
                Storage::delete('public/icons/', $originalIcon);
            }

            // update team
            $team->update([
                'name' => $request->name,
                // check if path exist
                'icon' => isset($path) ? $path : $team->icon,
                'company_id' => $request->company_id,
            ]);

            return ResponseFormatter::success(
                $team,
                'Team Updated'
            );
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(),  500);
        }
    }

    // delete team
    public function destroy($id)
    {
        try {
            // get team
            $team = Team::find($id);

            // check if team exist
            if (!$team) {
                throw new Exception('Team not found');
            }

            // delete team
            $team->delete();

            return ResponseFormatter::success(
                'Team Deleted'
            );
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(),  500);
        }
    }
}
