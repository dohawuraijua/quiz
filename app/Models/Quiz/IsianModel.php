<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IsianModel extends Model
{
    use HasFactory;
    protected $table = 'quiz_words';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = true;
}
