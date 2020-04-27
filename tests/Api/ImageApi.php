<?php

namespace Tests\Api;

use Illuminate\Http\UploadedFile;

trait ImageApi
{
    /**
     * Generate faker image.
     *
     * @param string $name
     * @param float $sizeKilobytes
     * @return array
     */
    protected function generateImage(string $name = 'image.png', float $sizeKilobytes = 2*1000)
    {
        return [
            'image' => $file = UploadedFile::fake()->image($name)->size($sizeKilobytes)
        ];
    }
}
