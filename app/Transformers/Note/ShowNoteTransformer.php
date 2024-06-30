<?php

namespace App\Transformers\Note;

use App\Models\Note;
use App\Models\ShowNote;
use Flugg\Responder\Transformers\Transformer;

class ShowNoteTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [];

    /**
     * Transform the model.
     *
     * @param  \App\Models\Note $showNote
     * @return array
     */
    public function transform(Note $note)
    {
        return [
            'id' => (int) $note->id,
            'note' => $note->note,
        ];
    }
}
