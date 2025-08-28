<?php

namespace App\Services;

use ImageKit\ImageKit;
use Illuminate\Support\Facades\Storage;

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

    public function upload($file, $fileName = null)
    {
        $fileName = $fileName ?? uniqid().'.'.$file->getClientOriginalExtension();
        
        $response = $this->imageKit->upload([
            'file' => base64_encode(file_get_contents($file->getRealPath())),
            'fileName' => $fileName,
            'folder' => 'uploads/'
        ]);

        return $response->result;
    }

    public function delete($fileId)
    {
        return $this->imageKit->deleteFile($fileId);
    }

    public function getUrl($path, $transformations = [])
    {
        return $this->imageKit->url([
            'path' => $path,
            'transformation' => $transformations
        ]);
    }

}
