<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Service\GeminiService;
use App\Http\Controllers\Traits\ApiResponser;
use App\Models\Quiz\IsianModel;
use Illuminate\Http\Request;
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
        $soal = IsianModel::where("quiz_date", $now)->where("indonesian_word", NULL)->limit(10)->get();

        return $this->successResponse($soal, Response::HTTP_OK, 'success', 'success');
    }

    function save_answer($req)
    {
        $question_id = $req->question_id;
        $answer = $req->answer;

        $save = IsianModel::where("id", $question_id)->first();
        if ($save) {
            $save->indonesian_word = $answer;
            $save->is_checked = 2;
            $save->save();
        }

        return $this->successResponse([], Response::HTTP_OK, 'Jawaban berhasil disimpan', 'success');
    }

    function generateSoalTanggal()
    {
        $date = date("Y-m-d");
        $soalCount = IsianModel::where("indonesian_word", NULL)->where("quiz_date", $date)->limit(10)->count();

        $jumlahGenerasi = 10;
        if ($soalCount < 10) {
            $jumlahGenerasi = 10 - $soalCount;
        }

        try {
            $this->cekHasil();
        } catch (\Throwable $th) {
            Log::error($th);
        }

        if ($jumlahGenerasi == 0) {
            return $this->successResponse([], Response::HTTP_OK, 'success', 'success');
        } else {
            $soal = IsianModel::where("indonesian_word", NULL)->limit($jumlahGenerasi)->get();
            foreach ($soal as $key => $value) {
                $value->quiz_date = $date;
                $value->save();
            }
            return $this->successResponse([], Response::HTTP_OK, 'success', 'success');
        }
    }

    function cekHasil()
    {
        $words = IsianModel::whereNull('is_checked', 2)->limit(10)->get();

        if ($words->count() === 0) {
            return $this->successResponse([], Response::HTTP_OK, 'no words to check', 'success');
        }

        $gemini = new GeminiService();
        $results = $gemini->checkWords($words);

        try {
            foreach ($results as $index => $result) {

                $word = $words[$index];

                $word->update([
                    'is_checked' => 1,
                    'score' => $result['score'],
                    'ai_feedback' => $result['feedback'] ?? null
                ]);
            }
        } catch (\Throwable $th) {
            Log::error($th);
        }



        return $this->successResponse([], Response::HTTP_OK, 'Words checked successfully', 'success');
    }
}
