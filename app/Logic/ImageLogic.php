<?php /** @noinspection PhpUndefinedMethodInspection */


namespace App\Logic;


use App\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ImageLogic implements ImageInterface
{
    /**
     * @inheritDoc
     */
    public function uploadImage(object $object, UploadedFile $file, string $path = 'public'): Image
    {
        return Image::create([
            'imageable_id' => $object->id,
            'imageable_type' => get_class($object),
            'path' => $filePath = Storage::put($path, $file),
            'url' => url($filePath)
        ]);
    }

    /**
     * @inheritDoc
     */
    public function uploadImages(object $object, Collection $images, string $path = 'public'): Collection
    {
        return $images->map(function(UploadedFile $image) use ($object, $path) {
            return $this->uploadImage($object, $image, $path);
        });
    }
}
