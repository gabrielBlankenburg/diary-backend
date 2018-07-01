<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Note;

use App\Http\Resources\Diary as NoteResource;

class DiaryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notes = Note::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();

        return response(NoteResource::collection($notes), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $note = new Note();
        $note->user_id = Auth::id();
        $note->title = $request->input('title'); 
        $note->content = $request->input('content'); 
        $note->label = $request->input('label');

        if ($note->save()) {
            return response(['response' => 'Note added successfully', 'data' => $note], 200);
        } else {
            return response(['response' => 'Error saving'], 400);         
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $note = Note::findOrFail($id);

        return response($note, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $note = Note::findOrFail($id);

        $note->title = $request->input('title'); 
        $note->content = $request->input('content'); 
        $note->label = $request->input('label');

        if ($note->save()) {
            return response(['response' => 'Note updated successfully', 'data' => $note], 200);
        } else {
            return response(['response' => 'Error saving'], 400);         
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $note = Note::findOrFail($id);

        if ($note->delete()) {
            return response(['response' => 'Note deleted successfully', 'data' => $note], 201);
        } else {
            return response(['response' => 'Error deleting'], 400);         
        }
    }
}
