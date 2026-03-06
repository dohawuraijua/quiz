<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService extends Controller
{
    public function checkWords($words)
    {
        $apiKey = env('gemini_key');

        $list = "";

        foreach ($words as $i => $word) {
            $list .= ($i + 1) . ". {$word->english_word} - {$word->indonesian_word}\n";
        }

        $prompt = "
Periksa apakah kata bahasa Indonesia adalah terjemahan benar dari bahasa Inggris.

Jawab hanya dalam JSON array:
[
 {\"correct\":true/false,\"score\":0-100,\"feedback\":\"text\"}
]

Data:
$list
";

        $response = Http::post(
            "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent?key=" . $apiKey,
            [
                "contents" => [
                    [
                        "parts" => [
                            ["text" => $prompt]
                        ]
                    ]
                ]
            ]
        );



        $text = $response['candidates'][0]['content']['parts'][0]['text'];

        $text = preg_replace('/```json|```/', '', $text);

        $text = trim($text);

        $data = json_decode($text, true);


        return $data;
    }
}
