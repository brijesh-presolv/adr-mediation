<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;


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

    function getPreSignedUrl($filename, $exp_time)
    {
        $filenameForPresignedUrl = ltrim($filename, '/');
        // $temporarySignedUrl = Storage::disk('s3')->temporaryUrl($filename,  Carbon::now()->addMinutes($exp_time));
        $disk = Storage::disk('s3');
        $temporarySignedUrl = $disk->getAwsTemporaryUrl($disk->getDriver()->getAdapter(), $filenameForPresignedUrl, Carbon::now()->addMinutes($exp_time), []);
        // $preSignedUrl = preg_replace('/([^:])(\/{2,})/', '$1/', $temporarySignedUrl);
        return $temporarySignedUrl;
    }

    function migrateDocOnAws($fileData, $parentFolder = "", $localfullpath) {
        // dd($localfullpath, $fileData);
        $filenametostore = '';
        if(isset($fileData['filename'])) {
            $pathinfo = pathinfo($fileData['filename'])['basename'];

            // dd($pathinfo);
            if ($parentFolder != '') {
                $filenametostore .= 'mediation_documents/mediation/' . $fileData['case_id'] . '/' . $parentFolder . '/' . $pathinfo;

                // Directory path with user Id
                $directoryName = 'mediation_documents/mediation/' . $fileData['case_id'] . '/' . $parentFolder;
            } else {
                $filenametostore .= 'mediation_documents/mediation/' . $fileData['case_id'] . '/' . $pathinfo;

                // Directory path with user Id
                $directoryName = 'mediation_documents/mediation/' . $fileData['case_id'];
            }
            if (Storage::disk('s3')->exists($directoryName)) {
                // Upload file if directory already there
                $storedFile = Storage::disk('s3')->put($filenametostore, fopen($_SERVER['DOCUMENT_ROOT'] . '/' . $localfullpath, 'r+'));
            } else {
                // Create directory & Upload file
                Storage::disk('s3')->makeDirectory($directoryName);
                $storedFile = Storage::disk('s3')->put($filenametostore, fopen($_SERVER['DOCUMENT_ROOT'] . '/' . $localfullpath, 'r+'));
            }
            if ($storedFile) {
                // unlink($localfullpath);
                return array('status' => true, 'message' => 'File uploaded successfully!', 'filename' => $filenametostore);
            } else {
                return array('status' => true, 'message' => 'File uploading failed!', 'filename' => $filenametostore);
            }
        }

    }
}