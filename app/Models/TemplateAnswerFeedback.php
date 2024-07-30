<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateAnswerFeedback extends Model
{
    use HasFactory;

    public $table = 'template_answer_feedbacks';

    protected $fillable = [
        'answer',
        'stars',
        'account_id'
    ];
}
