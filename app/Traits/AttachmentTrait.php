<?php


namespace App\Traits;


use App\Attachments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

trait AttachmentTrait
{

    /**
     * Store attachmets in storage.
     *
     * @param Model $model
     * @param array $attachments
     * @return bool
     */
    protected function storeAttachments(Model $model, array $attachments): bool
    {
        $attachments = $this->uploadAttachments($attachments)->map(function(array $attachment) use($model)  {
            return array_merge($attachment, [
                'attachmentable_id' => $model->id,
                'attachmentable_type' => get_class($model)
            ]);
        });

        return Attachments::insert($attachments->toArray());
    }

    /**
     * Upload attachments.
     *
     * @param array $attachements
     * @return Collection
     */
    protected function uploadAttachments(array $attachements): Collection
    {
        return collect($attachements)->map(function(UploadedFile $attachement) {
            return [
                'path' => $path = $attachement->store('attachments'),
                'mime_type' => $attachement->getMimeType(),
                'url' => url($path),
            ];
        });
    }
}
