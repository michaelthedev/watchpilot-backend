<?php

declare(strict_types=1);

namespace App\Helpers;

class File
{

	public static function upload(array $file, array $options):array {
        $response['status'] = false;
        $response['message'] = 'Failed to upload file';

        // Check upload status
        if (empty($file['name']) || $file['error'] != UPLOAD_ERR_OK) {
            $response['message'] = "File upload failed. Please check the file";
            return $response;
        }

		$PATH = UPLOADS_PATH;

		$imageExtensions = ['jpg', 'jpeg', 'png'];
		$documentExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
		if ($options['type'] == 'image') {
			$folder = UPLOADS_PATH . "/images";
			$allowedExtensions = $imageExtensions;
		} elseif($options['type'] == 'document') {
			$folder = UPLOADS_PATH."/documents";
			$allowedExtensions = $documentExtensions;
		} elseif($options['type'] == 'img_doc') {
			$folder = UPLOADS_PATH."/documents";
			$allowedExtensions = array_merge($imageExtensions, $documentExtensions);
		}

        // Upload Folder //
        $fileSizeLimit = $options['limit'] ?? 10000000;
        if (!empty($options['folder'])) {
            $folder = $folder."/{$options['folder']}/".date("Y")."/".date("m")."/";
        } else {
            $folder = $folder."/".date("Y")."/".date("m")."/";
        }

        if (!is_dir($folder)) mkdir($folder, 0755, true);
        $temporaryName = $file['tmp_name'];

        // Check file size
        if ($file["size"] > $fileSizeLimit) {
            $response['message'] = 'Sorry, your file is too large';
            return $response;
        }

        // Allow certain file formats
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (!in_array($extension, $allowedExtensions)) {
            $response['message'] = 'Sorry, only '.implode(', ', $allowedExtensions).' files are allowed';
            return $response;
        }

		$uid = time().uniqid();
        $file_name = !empty($options['prefix']) ? $options['prefix'] .'-'. $uid .".". $extension : $uid .".". $extension;
        if (move_uploaded_file($temporaryName, $folder.$file_name) === true) {
            $path = str_replace($PATH."/", '', $folder.$file_name);

            $response['path'] = $path;
            $response['fullPath'] = $folder.$file_name;

            $response['status'] = true;
            $response['message'] = 'Image uploaded';
        }

        return $response;
    }

	public static function delete(string $path): void
	{
		$path = ltrim($path,'uploads/');
		unlink(UPLOADS_PATH.'/'.$path);
	}
}