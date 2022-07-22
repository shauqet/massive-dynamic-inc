<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $users = User::with("contactPersons")
            ->when(auth()->user()->role===User::SECRETARY, function ($query){
                $query->where("role",User::CLIENT);
            })
            ->orderByDesc("id")
            ->paginate(10);

        return response()->json(["success", $users],ResponseAlias::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(UserRequest $request)
    {
        $validated = $request->validated();
        $validated["create_user_id"] = auth()->user()->id;
        if($validated["role"]==User::CLIENT) {
            $validated["client_id"] = Str::random(6);
        }
        $validated["password"] = bcrypt($validated["password"]);
        $user = User::create($validated);
        if($user){
            return response()->json(["success", $user], ResponseAlias::HTTP_OK);
        }
        return response()->json(["error"], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user)
    {
        return response()->json(["success", $user],ResponseAlias::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UserRequest $request, User $user)
    {
        $validated = $request->validated();
        $validated["update_user_id"] = auth()->user()->id;
        if(isset($validated["password"])){
            $validated["password"] = bcrypt($validated["password"]);
        }
        $user->update($validated);
        return response()->json(["success", $user], ResponseAlias::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(["success"], ResponseAlias::HTTP_OK);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function user()
    {
        return view("layouts.cms");
    }

    /**
     * Display a listing of the resource.
     *
     * @param $searchKeword
     * @return JsonResponse
     */
    public function getSearched($searchKeword) {
        $users = User::search($searchKeword);
        return response()->json(["success", $users], ResponseAlias::HTTP_OK);
    }
}
