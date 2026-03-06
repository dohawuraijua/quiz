<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponser;
use App\Models\Quiz\IsianModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IsianHasilController extends Controller
{
    use ApiResponser;
    function index(Request $req)
    {
        $action = $req->action;
        if ($action == "view") {
            return $this->view($req);
        }
    }

    function view($req)
    {
        $soal = IsianModel::where("indonesian_word", "!=", "")->orderByDesc("updated_at")->paginate(10);

        return $this->successResponse($soal, Response::HTTP_OK, 'success', 'success');
    }
}
