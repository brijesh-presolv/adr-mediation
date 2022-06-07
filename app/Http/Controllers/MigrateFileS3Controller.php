<?php

namespace App\Http\Controllers;

use App\Models\MedCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Traits\UploadTrait;


class MigrateFileS3Controller extends Controller
{
    use UploadTrait;

    public function MigrateFile()
    {
        $data = MedCase::with('invitation_file', 'consent_disclosures', 'supporting_docs', 'document_settlements')->orderBy('id', 'DESC')->take(2)->get();
        foreach ($data as $value) {
            // dd($value['invitation_file']);
            // --------------- move user supporting document ---------------
            if ($value->documentPath != "NULL") {
                $path = 'storage/app/public/mediation/' . $value->id . '/' . $value->documentPath;
                if (File::exists($path)) {
                    $filedata = [
                        'filename' => $value->documentPath,
                        'case_id' => $value->id,
                    ];
                    $this->migrateDocOnAws($filedata, 'user/supportingDocument', $path);
                }
            }

            // // --------------- move request letter ---------------
            if ($value->request_letter != null) {
                $path = 'storage/app/public/mediation/' . $value->id . '/' . $value->request_letter;
                if (File::exists($path)) {
                    $filedata = [
                        'filename' => $value->request_letter,
                        'case_id' => $value->id,
                    ];
                    $this->migrateDocOnAws($filedata, '', $path);
                }
            }
            if (count($value['invitation_file']) != 0) {
                foreach ($value['invitation_file'] as $files) {
                    // --------------- move Invitation mediate ---------------
                    if ($files->file_name != null) {
                        $path = 'storage/app/public/mediation/' . $value->id . '/' . $files->file_name;
                        if (File::exists($path)) {
                            $filedata = [
                                'filename' => $files->file_name,
                                'case_id' => $value->id,
                            ];
                            $this->migrateDocOnAws($filedata, '', $path);
                        }
                    }

                    // --------------- move mediator appoinment letter ---------------
                    if ($files->file_name_mediator_appointment != null) {
                        $path = 'storage/app/public/mediation/' . $value->id . '/' . $files->file_name_mediator_appointment;
                        if (File::exists($path)) {
                            $filedata = [
                                'filename' => $files->file_name_mediator_appointment,
                                'case_id' => $value->id,
                            ];
                            $this->migrateDocOnAws($filedata, '', $path);
                        }
                    }
                }
            }
            if (count($value['consent_disclosures']) != 0) {
                foreach ($value['consent_disclosures'] as $disclosures) {

                    // --------------- move consent disclosures ---------------
                    if ($disclosures->file_name != null) {
                        $path = 'storage/app/public/mediation/' . $value->id . '/' . $disclosures->file_name;
                        if (File::exists($path)) {
                            $filedata = [
                                'filename' => $disclosures->file_name,
                                'case_id' => $value->id,
                            ];
                            $this->migrateDocOnAws($filedata, '', $path);
                        }
                    }
                }
            }
            if (count($value['supporting_docs']) != 0) {
                foreach ($value['supporting_docs'] as $supporting) {

                    // --------------- move supporting document ---------------
                    if ($supporting->file_name != null) {
                        $path = 'storage/app/supporting/' . $value->id . '/' . $supporting->file_name;
                        if (File::exists($path)) {
                            $filedata = [
                                'filename' => $supporting->file_name,
                                'case_id' => $value->id,
                            ];
                            $this->migrateDocOnAws($filedata, 'supportingDocument', $path);
                        }
                    }
                }
            }
            if (count($value['document_settlements']) != 0) {
                foreach ($value['document_settlements'] as $settlement) {

                    // --------------- move supporting document ---------------
                    if ($settlement->file_path != null) {
                        $path = 'storage/app/supporting/' . $value->id . '/' . $settlement->file_path;
                        if (File::exists($path)) {
                            $filedata = [
                                'filename' => $settlement->file_path,
                                'case_id' => $value->id,
                            ];
                            $this->migrateDocOnAws($filedata, 'settelmentDocument', $path);
                        }
                    }
                }
            }
        }
        return true;
    }
}
