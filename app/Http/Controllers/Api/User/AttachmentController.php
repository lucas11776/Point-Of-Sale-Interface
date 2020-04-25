<?php

namespace App\Http\Controllers\Api\User;

use App\Traits\AttachmentTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\AttachmentsRequest;
use Illuminate\Http\JsonResponse;

class AttachmentController extends Controller
{
    use AttachmentTrait;

    public function index()
    {

    }

    /**
     * Store user attachments in storage.
     *
     * @param AttachmentsRequest $attachmentsRequest
     * @return JsonResponse
     */
    public function store(AttachmentsRequest $attachmentsRequest): JsonResponse
    {
        $attachments = $this->storeAttachments(auth()->user(), $attachmentsRequest->validated()['attachments']);

        return response()->json([
            'message' => 'Attachments have been uploaded.',
            'data' => [
                'attachments' => $attachments
            ]
        ]);
    }

    public function destroy()
    {

    }
}
