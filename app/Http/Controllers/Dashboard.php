<?php

namespace App\Http\Controllers;

use App\Models\Quiz\IsianModel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard extends Controller
{
    public function index()
    {
        // Ambil overview sekaligus distribusi dengan 1 query
        $stats = IsianModel::selectRaw("
            COUNT(*) as total_words,
            SUM(CASE WHEN is_checked = 1 THEN 1 ELSE 0 END) as checked_words,
            AVG(CASE WHEN is_checked = 1 THEN score ELSE NULL END) as avg_score,
            MAX(score) as max_score,
            MIN(score) as min_score,
            SUM(CASE WHEN score = 100 THEN 1 ELSE 0 END) as perfect,
            SUM(CASE WHEN score BETWEEN 90 AND 99 THEN 1 ELSE 0 END) as excellent,
            SUM(CASE WHEN score BETWEEN 75 AND 89 THEN 1 ELSE 0 END) as good,
            SUM(CASE WHEN score BETWEEN 60 AND 74 THEN 1 ELSE 0 END) as fair,
            SUM(CASE WHEN score BETWEEN 40 AND 59 THEN 1 ELSE 0 END) as poor,
            SUM(CASE WHEN score BETWEEN 1 AND 39 THEN 1 ELSE 0 END) as bad,
            SUM(CASE WHEN score = 0 THEN 1 ELSE 0 END) as zero
        ")->where("indonesian_word", "!=", null)->whereNotNull("score")->first();

        // Daily stats 7 hari terakhir
        $sevenDaysAgo = Carbon::today()->subDays(6)->toDateString();
        $dailyRaw = IsianModel::select(
            DB::raw('DATE(quiz_date) as date'),
            DB::raw('AVG(score) as avg_score')
        )
            ->whereNotNull('score')
            ->whereNotNull('quiz_date')
            ->where('quiz_date', '>=', $sevenDaysAgo)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy('date'); // mempermudah lookup

        // Pastikan selalu 7 hari terakhir muncul
        $daily = collect();
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i)->toDateString();
            $avg = isset($dailyRaw[$day]) ? round($dailyRaw[$day]->avg_score, 2) : 0;
            $daily->push([
                'date' => $day,
                'avg_score' => $avg
            ]);
        }

        return response()->json([
            'overview' => [
                'total_words' => (int) $stats->total_words,
                'checked_words' => (int) $stats->checked_words,
                'average_score' => $stats->avg_score ? round($stats->avg_score, 2) : 0,
                'max_score' => (int) $stats->max_score,
                'min_score' => (int) $stats->min_score,
            ],
            'distribution' => [
                'perfect' => (int) $stats->perfect,
                'excellent' => (int) $stats->excellent,
                'good' => (int) $stats->good,
                'fair' => (int) $stats->fair,
                'poor' => (int) $stats->poor,
                'bad' => (int) $stats->bad,
                'zero' => (int) $stats->zero,
            ],
            'daily_stats' => $daily
        ]);
    }
}
