<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactPersonRequest;
use App\Models\ContactPerson;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ContactPersonsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param ContactPersonRequest $request
     * @return JsonResponse
     */
    public function store(ContactPersonRequest $request)
    {
        $validated = $request->validated();
        $validated["create_user_id"] = auth()->user()->id;
        $contactPerson = ContactPerson::create($validated);
        if($contactPerson){
            return response()->json(["success", $contactPerson], ResponseAlias::HTTP_OK);
        }
        return response()->json(["error"], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ContactPersonRequest $request
     * @param ContactPerson $contactPerson
     * @return JsonResponse
     */
    public function update(ContactPersonRequest $request, ContactPerson $contactPerson)
    {
        $validated = $request->validated();
        $validated["update_user_id"] = auth()->user()->id;
        $contactPerson->update($validated);
        return response()->json(["success", $contactPerson], ResponseAlias::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ContactPerson $contactPerson
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(ContactPerson $contactPerson)
    {
        $contactPerson->delete();
        return response()->json(["success"], ResponseAlias::HTTP_OK);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function contactPerson()
    {
        return view("layouts.cms");
    }

    public function byUserId( $userId)
    {
        $contactPersons = ContactPerson::where("user_id", $userId)->orderByDesc("id")->paginate(10);
        return response()->json(["success", $contactPersons], ResponseAlias::HTTP_OK);
    }
}
