<?php


namespace App\Logic;


use App\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

interface ImageInterface
{
    /**
     * Upload image to local storage.
     *
     * @param object $object
     * @param UploadedFile $file
     * @param string $path
     * @return Image
     */
    public function uploadImage(object $object, UploadedFile $file, string $path = 'public'): Image;

    /**
     * Upload images to local storage.
     *
     * @param object $object
     * @param Collection $images
     * @param string $path
     * @return Collection
     */
    public function uploadImages(object $object, Collection $images, string $path = 'public'): Collection;
}
