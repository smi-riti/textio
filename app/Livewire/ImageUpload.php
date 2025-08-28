<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\ImageKitService;
use Illuminate\Support\Facades\Log;

class ImageUpload extends Component
{

        use WithFileUploads;

    public $image;
    public $uploadedImage;
    public $uploadedImageUrl;
    public $isUploading = false;

    protected $rules = [
        'image' => 'required|image|max:10240', 
    ];

    public function uploadImage()
    {
        $this->validate();

        $this->isUploading = true;

        try {
            $imageKitService = new ImageKitService();
            $result = $imageKitService->upload($this->image);

            $this->uploadedImage = $result;
            $this->uploadedImageUrl = $result->url;
            
            session()->flash('message', 'Image uploaded successfully!');
            
        } catch (\Exception $e) {
            Log::error('ImageKit upload error: ' . $e->getMessage());
            session()->flash('error', 'Upload failed: ' . $e->getMessage());
        }

        $this->isUploading = false;
        $this->reset('image');
    }

    public function render()
    {
        return view('livewire.image-upload');
    }
}
