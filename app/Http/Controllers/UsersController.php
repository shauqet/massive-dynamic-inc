<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $users = User::with("contactPersons")->orderBy("id", "DESC");
        if(auth()->user()->role===User::SECRETARY){
            $users = $users->where("role",User::CLIENT);
        }
        $users = $users->paginate(10);
        return response()->json(["success", $users], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
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
        $validated["password"] = bcrypt("password");
        $user = User::create($validated);
        return $user ? response()->json(["success", $user], 200) : response()->json(["error"], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function edit(User $user)
    {
        //
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
        $user->update($validated);
        return $user ? response()->json(["success", $user], 200) : response()->json(["error"], 500);
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
        return response()->json(["success"], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
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
        $users = User::where(function ($query) use ($searchKeword) {
            $query->where("name", "LIKE", "%$searchKeword%")
                ->orWhere("username", "LIKE", "%$searchKeword%")
                ->orWhere("email", "LIKE", "%$searchKeword%");
        });
        if(auth()->user()->role===User::SECRETARY){
            $users = $users->where(function ($query) {
                $query->where("role",User::CLIENT);
            });
        }
        $users = $users->orderBy("id", "DESC")->paginate(10);
        return $users ? response()->json(["success", $users], 200) : response()->json(["error"], 500);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function allUsers()
    {
        $users = User::all();
        return response()->json(["success", $users], 200);
    }
}
