<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactPersonRequest;
use App\Models\ContactPerson;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ContactPersonsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

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
        return $contactPerson ? response()->json(["success", $contactPerson], ResponseAlias::HTTP_OK) : response()->json(["error"], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param ContactPerson $contactPerson
     * @return \Illuminate\Http\Response
     */
    public function show(ContactPerson $contactPerson)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ContactPerson $contactPerson
     * @return \Illuminate\Http\Response
     */
    public function edit(ContactPerson $contactPerson)
    {
        //
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
        return $contactPerson ? response()->json(["success", $contactPerson], ResponseAlias::HTTP_OK) : response()->json(["error"], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
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
     * @return Response
     */
    public function contactPerson()
    {
        return view("layouts.cms");
    }

    public function byUserId( $userId)
    {
        $contactPersons = ContactPerson::where("user_id", $userId)->orderBy("id", "DESC")->paginate(10);
        return response()->json(["success", $contactPersons], ResponseAlias::HTTP_OK);
    }
}
