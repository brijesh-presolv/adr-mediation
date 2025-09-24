<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DownloadDocument extends Controller {

    public function downloadSecure(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'caseId'     => 'required'
            ]);

            if ($validator->fails()) {

                $errors = $validator->errors()->all(); 

                $result['success'] = false;
                $result['message'] = $implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }
            
            if(isset($request->fullurl)) {

                $path = parse_url($request->fullurl);
                $filenametostore = ltrim($path['path'], '/');
                $request->urlpath = basename($request->fullurl);
            }
            else if (isset($request->parentFolder)) {
                $filenametostore = 'mediation_documents/mediation/' . $request->caseId . '/' . $request->parentFolder . '/' . $request->urlpath;
            } else {
                $filenametostore = 'mediation_documents/mediation/' . $request->caseId . '/' . $request->urlpath;
            }
            $s3Client = Storage::cloud()->getAdapter()->getClient();

            $objectExists = $s3Client->doesObjectExist(env('AWS_BUCKET'), $filenametostore);

            if (!$objectExists) {

                $result['success'] = false;
                $result['message'] = "File not found";
                $result['error'] =  "File Fetching failed.";
                return response()->json($result, 404);
            }

            $stream = $s3Client->getObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key'    => $filenametostore
            ]);

            /* $data['docsFile']=$stream['Body'];

            $result['success'] = true;
            $result['message'] = "Data fetched successfully.";
            $result['data'] = $data; */
            return response($stream['Body'], 200)->withHeaders([
                'Content-Type'        => $stream['ContentType'],
                'Content-Length'      => $stream['ContentLength'],
                'Content-Disposition' => 'attachment; filename="' . basename($request->urlpath) . '"'
            ]);




        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "File Fetching failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
         
        }
    }

    public function previewSecure(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'caseId'     => 'required'
            ]);

            if ($validator->fails()) {

                $errors = $validator->errors()->all(); 

                $result['success'] = false;
                $result['message'] = implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }

            if(isset($request->fullurl)) {

                $path = parse_url($request->fullurl);
                $filenametostore = ltrim($path['path'], '/');
                $request->urlpath = basename($request->fullurl);
            }
            else if (isset($request->parentFolder)) {

                $filenametostore = 'mediation_documents/mediation/' . $request->caseId . '/' . $request->parentFolder . '/' . $request->urlpath;
            } else {
                $filenametostore = 'mediation_documents/mediation/' . $request->caseId . '/' . $request->urlpath;
            }

            $s3Client = Storage::cloud()->getAdapter()->getClient();

            $objectExists = $s3Client->doesObjectExist(env('AWS_BUCKET'), $filenametostore);

            if (!$objectExists) {

                $result['success'] = false;
                $result['message'] = "File not found";
                $result['error'] =  "File Fetching failed.";
                return response()->json($result, 404);
            }

            $stream = $s3Client->getObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key'    => $filenametostore
            ]);
         
            

            $body = $stream['Body']; // This is a GuzzleHttp\Psr7\Stream

            return response()->stream(function () use ($body) {
                while (!$body->eof()) {
                    echo $body->read(1024); // read in chunks
                }
            }, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($request->urlpath) . '"',
                'Content-Length' => $stream['ContentLength'],
            ]);

        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "File Fetching failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }
}