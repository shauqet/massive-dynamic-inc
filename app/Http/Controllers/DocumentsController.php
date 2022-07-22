<?php

namespace App\Http\Controllers;

use App\Helpers\FilesHelper;
use App\Http\Requests\DocumentRequest;
use App\Models\Document;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class DocumentsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param DocumentRequest $request
     * @return JsonResponse
     */
    public function store(DocumentRequest $request)
    {
        $validated = $request->validated();
        $validated["create_user_id"] = auth()->user()->id;
        $validated["path"] = FilesHelper::uploadFile($validated["path"]);
        $document = Document::create($validated);
        if($document){
            return response()->json(["success", $document], ResponseAlias::HTTP_OK);
        }

        return response()->json(["error"], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
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
     * @return Application|Factory|View
     */
    public function document()
    {
        return view("layouts.cms");
    }

    public function byUserId( $userId)
    {
        $document = Document::where("user_id", $userId)->orderByDesc("id")->paginate(10);
        return response()->json(["success", $document], ResponseAlias::HTTP_OK);
    }
}
