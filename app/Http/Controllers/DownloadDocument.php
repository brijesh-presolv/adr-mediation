<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class DownloadDocument extends Controller {
    public function downloadSecure(Request $request)
    {
        
        if(isset($request->fullurl)) {
            $path = parse_url($request->fullurl);
            $filenametostore = ltrim($path['path'], '/');
            $request->urlpath = basename($request->fullurl);
        }
        else if (isset($request->parentFolder)) {
            $filenametostore = 'mediation_documents/mediation/' . $request->id . '/' . $request->parentFolder . '/' . $request->urlpath;
        } else {
            $filenametostore = 'mediation_documents/mediation/' . $request->id . '/' . $request->urlpath;
        }
        $s3Client = Storage::cloud()->getAdapter()->getClient();

        $stream = $s3Client->getObject([
            'Bucket' => env('AWS_BUCKET'),
            'Key'    => $filenametostore
        ]);

        return response($stream['Body'], 200)->withHeaders([
            'Content-Type'        => $stream['ContentType'],
            'Content-Length'      => $stream['ContentLength'],
            'Content-Disposition' => 'attachment; filename="' . basename($request->urlpath) . '"'
        ]);
    }
}