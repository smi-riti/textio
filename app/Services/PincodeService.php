<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PincodeService
{
    /**
     * Fetch location details by pincode
     *
     * @param string $pincode
     * @return array
     */
    public static function getLocationByPincode(string $pincode): array
    {
        // Validate pincode format
        if (!preg_match('/^\d{6}$/', $pincode)) {
            return [
                'success' => false,
                'error' => 'Invalid pincode format. Must be 6 digits.'
            ];
        }

        // Check cache first
        $cacheKey = "pincode_location_{$pincode}";
        $cached = Cache::get($cacheKey);
        
        if ($cached) {
            return $cached;
        }

        try {
            // Try Laravel HTTP client first with better configuration
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Accept' => 'application/json',
                'Cache-Control' => 'no-cache'
            ])
            ->withOptions([
                'verify' => false, // Disable SSL verification for development
            ])
            ->timeout(10)
            ->get("https://api.postalpincode.in/pincode/{$pincode}");
            
            if (!$response->successful()) {
                throw new \Exception('Laravel HTTP request failed with status: ' . $response->status());
            }

            $data = $response->json();

        } catch (\Exception $laravelHttpException) {
            // Fallback to cURL if Laravel HTTP client fails
            Log::info('Laravel HTTP client failed, falling back to cURL', [
                'pincode' => $pincode,
                'error' => $laravelHttpException->getMessage()
            ]);

            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://api.postalpincode.in/pincode/{$pincode}");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 15);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Accept: application/json',
                    'Cache-Control: no-cache'
                ]);

                $curlResponse = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curlError = curl_error($ch);
                curl_close($ch);

                if ($curlError) {
                    throw new \Exception('cURL error: ' . $curlError);
                }

                if ($httpCode !== 200) {
                    throw new \Exception('cURL request failed with HTTP code: ' . $httpCode);
                }

                if (!$curlResponse) {
                    throw new \Exception('cURL returned empty response');
                }

                $data = json_decode($curlResponse, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Failed to decode JSON response: ' . json_last_error_msg());
                }

            } catch (\Exception $curlException) {
                Log::error('Both Laravel HTTP and cURL failed for pincode API', [
                    'pincode' => $pincode,
                    'laravel_error' => $laravelHttpException->getMessage(),
                    'curl_error' => $curlException->getMessage()
                ]);
                throw $curlException;
            }
        }

        try {

            if (
                !isset($data[0]['Status']) || 
                $data[0]['Status'] !== 'Success' || 
                empty($data[0]['PostOffice'])
            ) {
                $result = [
                    'success' => false,
                    'error' => 'Invalid pincode or no data found'
                ];
            } else {
                $postOffice = $data[0]['PostOffice'][0];
                $result = [
                    'success' => true,
                    'data' => [
                        'pincode' => $pincode,
                        'city' => $postOffice['District'] ?? '', // Use District as city for better accuracy
                        'district' => $postOffice['District'] ?? '',
                        'state' => $postOffice['State'] ?? '',
                        'country' => $postOffice['Country'] ?? 'India'
                    ]
                ];
            }

            // Cache the result for 24 hours
            Cache::put($cacheKey, $result, now()->addHours(24));
            
            return $result;

        } catch (\Exception $e) {
            Log::error('Pincode API error: ' . $e->getMessage(), [
                'pincode' => $pincode,
                'trace' => $e->getTraceAsString()
            ]);

            // Return more specific error messages
            $errorMessage = 'Unable to fetch location details. Please try again later.';
            if (strpos($e->getMessage(), 'timeout') !== false) {
                $errorMessage = 'Request timeout. Please check your internet connection and try again.';
            } elseif (strpos($e->getMessage(), 'DNS') !== false || strpos($e->getMessage(), 'resolve') !== false) {
                $errorMessage = 'Network error. Please check your internet connection.';
            }

            return [
                'success' => false,
                'error' => $errorMessage
            ];
        }
    }

    /**
     * Get a list of supported pincodes for a state
     *
     * @param string $stateName
     * @return array
     */
    public static function getPincodesByState(string $stateName): array
    {
        $cacheKey = "state_pincodes_" . strtolower(str_replace(' ', '_', $stateName));
        
        return Cache::remember($cacheKey, now()->addDays(7), function () use ($stateName) {
            try {
                $response = Http::timeout(10)->get("https://api.postalpincode.in/postoffice/{$stateName}");
                
                if (!$response->successful()) {
                    return [];
                }

                $data = $response->json();
                $pincodes = [];

                if (isset($data[0]['PostOffice']) && is_array($data[0]['PostOffice'])) {
                    foreach ($data[0]['PostOffice'] as $office) {
                        if (isset($office['Pincode'])) {
                            $pincodes[] = $office['Pincode'];
                        }
                    }
                }

                return array_unique($pincodes);

            } catch (\Exception $e) {
                Log::error('State pincodes API error: ' . $e->getMessage());
                return [];
            }
        });
    }
}