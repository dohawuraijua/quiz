<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz\IsianModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class IsianController extends Controller
{
    use ApiResponser;

    function index(Request $req)
    {
        $action = $req->action;
        if ($action == "view") {
            return $this->view($req);
        } elseif ($action == "save_answer") {
            return $this->save_answer($req);
        }
    }

    function view($req)
    {
        $now = date("Y-m-d");
        $soal = IsianModel::where("quiz_date", $now)->get();

        return $this->successResponse($soal, Response::HTTP_OK, 'success', 'success');
    }

    function save_answer($req)
    {
        $question_id = $req->question_id;
        $answer = $req->answer;

        $save = IsianModel::where("id", $question_id)->first();
        if ($save) {
            $save->indonesian_word = $answer;
            $save->save();
        }

        return $this->successResponse([], Response::HTTP_OK, 'Jawaban berhasil disimpan', 'success');
    }
}
