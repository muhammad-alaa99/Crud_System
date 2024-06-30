<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Note\CreateRequest;
use App\Http\Requests\Note\updateRequest;
use App\Models\Note;
use App\Transformers\Note\ShowNoteTransformer;
use Illuminate\Http\Response;

class NoteController extends Controller
{
    public function index()
    {
        $user = $this->getCurrentUser();
        $notes = $user->notes;
        return responder()->success($notes, ShowNoteTransformer::class)->respond(Response::HTTP_OK);
    }

    public function create(CreateRequest $request)
    {
        Note::create([
            'user_id' => $this->getCurrentUser()->id,
            'note' => $request->note
        ]);
        return responder()->success(['message' => 'note created successfully'])->respond(Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $note = $this->checkownNote($id);
        if(!$note)
        {
            return responder()->error('unauthorized')->respond(Response::HTTP_UNAUTHORIZED);
        }
        return responder()->success($note, ShowNoteTransformer::class)->respond(Response::HTTP_OK);
    }

    public function update($id,updateRequest $request)
    {
        $note = $this->checkownNote($id);
        if(!$note)
        {
            return responder()->error('unauthorized')->respond(Response::HTTP_UNAUTHORIZED);
        }
        $note->note = $request->note;
        $note->save();
        return responder()->success(['message' => 'note updated successfully'])->respond(Response::HTTP_OK);
    }

    public function delete($id)
    {
        $note = $this->checkownNote($id);
        if(!$note)
        {
            return responder()->error('unauthorized')->respond(Response::HTTP_UNAUTHORIZED);
        }
        $note->delete();
        return responder()->success(['message' => 'note deleted successfully'])->respond(Response::HTTP_OK);
    }


    private function checkownNote($id)
    {
        $user = $this->getCurrentUser();
        $note = $user->notes->where('id', $id)->first();
        if(!$note)
        {
            return false;
        } 
        return $note;
    }

    private function getCurrentUser()
    {
        return auth('sanctum')->user();
    }
}
