<?php

namespace App\Http\Controllers;

use App\Helpers\FilesHelper;
use App\Http\Requests\DocumentRequest;
use App\Models\Document;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class DocumentsController extends Controller
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
     * @param Request $request
     * @return JsonResponse
     */
    public function store(DocumentRequest $request)
    {
        $validated = $request->validated();
        $validated["create_user_id"] = auth()->user()->id;
        $validated["path"] = FilesHelper::uploadFile($validated["path"]);
        $document = Document::create($validated);
        return $document ? response()->json(["success", $document], ResponseAlias::HTTP_OK) : response()->json(["error"], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param Document $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Document $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DocumentRequest $request
     * @param Document $document
     * @return JsonResponse
     */
    public function update(DocumentRequest $request, Document $document)
    {
        $validated = $request->validated();
        $validated["update_user_id"] = auth()->user()->id;
        $document->update($validated);
        return $document ? response()->json(["success", $document], ResponseAlias::HTTP_OK) : response()->json(["error"], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Document $document
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Document $document)
    {
        FilesHelper::deleteFile($document->path);
        $document->delete();
        return response()->json(["success"], ResponseAlias::HTTP_OK);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function document()
    {
        return view("layouts.cms");
    }

    public function byUserId( $userId)
    {
        $document = Document::where("user_id", $userId)->orderBy("id", "DESC")->paginate(10);
        return response()->json(["success", $document], ResponseAlias::HTTP_OK);
    }
}
