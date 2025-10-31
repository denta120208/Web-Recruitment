<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestHrisController extends Controller
{
    public function testSendCandidate(Request $request)
    {
        $payload = [
            'job_vacancy_id' => 1,
            'recruitment_candidate_id' => 101,
            'candidate_name' => 'Kurnia Mega',
            'candidate_email' => 'kurniamega@gmail.com',
            'candidate_contact_number' => '087463746787',
            'candidate_apply_date' => '2025-10-29',
            'apply_jobs_status_id' => 1,
            'set_new_candidate_by' => 'Abdul',
        ];
        $response = Http::asJson()->post('https://trialhris.metropolitanland.com/recruitment/api/setNewCandidate', $payload);
        Log::info('Debug TEST HRIS API', [
            'payload' => $payload,
            'status' => $response->status(),
            'body' => $response->body(),
            'headers' => $response->transferStats ? $response->transferStats->getRequest()->getHeaders() : [],
        ]);
        return response()->json([
            'status' => $response->status(),
            'body' => $response->body()
        ]);
    }
}
