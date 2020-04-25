<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachments extends Model
{
    /**
     * Allowed attachment file extension mime type.
     *
     * @var array
     */
    public const ALLOWED_EXTENSIONS = [
        'jpg', 'jpeg', 'png' , 'gif', 'pdf', 'docx', 'mp3', 'mp4', 'txt'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'attachmentable_id', 'attachmentable_type', 'mine_type', 'path', 'url'
    ];
}
