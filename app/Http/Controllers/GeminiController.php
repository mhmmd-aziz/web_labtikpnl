<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Menggunakan HTTP Client bawaan
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class GeminiController extends Controller
{
    public function ask(Request $request)
    {
        // 1. Validasi input (disarankan)
        try {
            $validated = $request->validate([
                'prompt' => 'required|string|max:1000',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()->first()], 422);
        }

        $userInput = $validated['prompt'];
        
        // PERBAIKAN #1: Gunakan 'GOOGLE_API_KEY'
        $apiKey = env('GOOGLE_API_KEY'); 

        if (empty($apiKey)) {
            return response()->json(['error' => 'GOOGLE_API_KEY tidak ditemukan di file .env'], 500);
        }

        // PERBAIKAN #2: Gunakan 'v1beta' dan model 'gemini-1.5-flash-latest'
        $modelName = 'gemini-1.5-pro-latest';
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}";


        try {
            // Panggil API Google
            $response = Http::post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $userInput]
                        ]
                    ]
                ]
            ]);

            // Cek jika request gagal
            if ($response->failed()) {
                $errorData = $response->json();
                $errorMessage = $errorData['error']['message'] ?? 'Error tidak diketahui dari Google API';
                Log::error('Gemini API Gagal: ' . $response->body());
                return response()->json(['error' => 'Gemini API Error: ' . $errorMessage], $response->status());
            }

            // Ambil teks jawaban
            $responseText = $response->json()['candidates'][0]['content']['parts'][0]['text'];

            // PERBAIKAN #3: Kirim JSON key 'response'
            return response()->json([
                'response' => $responseText 
            ]);

        } catch (\Exception $e) {
            Log::error('Gemini Controller Error: ' . $e->getMessage());
            return response()->json(['error' => 'Maaf, terjadi kesalahan server: ' . $e->getMessage()], 500);
        }
    }
}