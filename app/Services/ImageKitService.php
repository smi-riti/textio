<?php

namespace App\Services;

use ImageKit\ImageKit;
use Illuminate\Support\Facades\Log;

class ImageKitService
{
    protected $imageKit;

    public function __construct()
    {
        $this->imageKit = new ImageKit(
            config('services.imagekit.public_key'),
            config('services.imagekit.private_key'),
            config('services.imagekit.url_endpoint')
        );
    }

    public function upload($file, $fileName, $folder = '')
    {
        try {
            // Handle Livewire temporary uploaded file
            if ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                $filePath = $file->getRealPath();
            } else {
                $filePath = $file->getRealPath();
            }

            // Upload to ImageKit
            $response = $this->imageKit->upload([
                'file' => fopen($filePath, 'r'),
                'fileName' => $fileName,
                'folder' => $folder,
            ]);

            // Debug: Check what type of response we're getting
            Log::debug('ImageKit response type: ' . gettype($response));
            Log::debug('ImageKit response content: ', ['response' => $response]);

            // If response is a string, it's likely an error message
            if (is_string($response)) {
                Log::error('ImageKit returned string response: ' . $response);
                throw new \Exception('ImageKit upload failed: ' . $response);
            }

            // If response is an object, check for success
            if (is_object($response)) {
                // Check for different possible response structures
                if (isset($response->result)) {
                    // Success - return the result object
                    return $response->result;
                } elseif (isset($response->error)) {
                    // Error occurred
                    Log::error('ImageKit upload error:', (array)$response->error);
                    $errorMessage = $response->error->message ?? 'Unknown error';
                    throw new \Exception('ImageKit upload failed: ' . $errorMessage);
                } elseif (isset($response->message)) {
                    // Another possible error format
                    Log::error('ImageKit upload message: ' . $response->message);
                    throw new \Exception('ImageKit upload failed: ' . $response->message);
                }
            }

            // If we get here, the response format is unexpected
            Log::error('Unexpected ImageKit response format:', ['response' => $response]);
            throw new \Exception('Unexpected response format from ImageKit');

        } catch (\Exception $e) {
            Log::error('ImageKit upload exception: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete($fileId)
    {
        try {
            $response = $this->imageKit->deleteFile($fileId);
            
            // Handle string response
            if (is_string($response)) {
                Log::error('ImageKit delete string response: ' . $response);
                throw new \Exception('ImageKit delete failed: ' . $response);
            }
            
            if (is_object($response) && isset($response->error)) {
                Log::error('ImageKit delete error:', (array)$response->error);
                throw new \Exception('ImageKit delete failed: ' . ($response->error->message ?? 'Unknown error'));
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('ImageKit delete exception: ' . $e->getMessage());
            throw $e;
        }
    }
}