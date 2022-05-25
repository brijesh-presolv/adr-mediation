<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Storage;


trait UploadTrait {
    // upload pdf file directly on AWS without saving on local
    function uploadOnAWSDirect($finalFilePath, $savePath, $pdfObj)
    {
        if (Storage::disk('s3')->exists($savePath)) {
            // Upload file if directory already there
            // if (Storage::disk('s3')->put($finalFilePath, $pdfObj->output())) {
            //     return true;
            // } else {
            //     return false;
            // }
            $s3Client = Storage::cloud()->getAdapter()->getClient();

            $result = $s3Client->putObject(array(
                'Bucket' => env('AWS_BUCKET'),
                'Key'    => $finalFilePath,
                'Body'   => $pdfObj->output(),
            ));
            return $result['ObjectURL'];
        } else {
            // Create directory & Upload file
            Storage::disk('s3')->makeDirectory($savePath);

            $s3Client = Storage::cloud()->getAdapter()->getClient();

            $result = $s3Client->putObject(array(
                'Bucket' => env('AWS_BUCKET'),
                'Key'    => $finalFilePath,
                'Body'   => $pdfObj->output(),
            ));

            return $result['ObjectURL'];

            // if (Storage::disk('s3')->put($finalFilePath, $pdfObj->output())) {
            //     return true;
            // } else {
            //     return false;
            // }
        }
    }
}