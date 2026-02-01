<?php
class FileUpload {
    public static function uploadImage($file, $subfolder = '') {
        if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Gabim në ngarkim të file-it.'];
        }

        // Check file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, ALLOWED_IMAGE_TYPES)) {
            return ['success' => false, 'message' => 'Lloji i file-it nuk lejohet. Lejohen vetëm imazhe.'];
        }

        // Check file size
        if ($file['size'] > MAX_FILE_SIZE) {
            return ['success' => false, 'message' => 'File-i është shumë i madh. Maksimumi 5MB.'];
        }

        // Create upload directory
        $uploadDir = UPLOAD_DIR . ($subfolder ? $subfolder . '/' : '');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $filepath = $uploadDir . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $url = UPLOAD_URL . ($subfolder ? $subfolder . '/' : '') . $filename;
            return ['success' => true, 'path' => $filepath, 'url' => $url, 'filename' => $filename];
        }

        return ['success' => false, 'message' => 'Gabim në ruajtje të file-it.'];
    }

    public static function uploadPDF($file, $subfolder = '') {
        if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Gabim në ngarkim të file-it.'];
        }

        // Check file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, ALLOWED_PDF_TYPES)) {
            return ['success' => false, 'message' => 'Lloji i file-it nuk lejohet. Lejohen vetëm PDF.'];
        }

        // Check file size
        if ($file['size'] > MAX_FILE_SIZE) {
            return ['success' => false, 'message' => 'File-i është shumë i madh. Maksimumi 5MB.'];
        }

        // Create upload directory
        $uploadDir = UPLOAD_DIR . ($subfolder ? $subfolder . '/' : '');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $extension = 'pdf';
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $filepath = $uploadDir . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $url = UPLOAD_URL . ($subfolder ? $subfolder . '/' : '') . $filename;
            return ['success' => true, 'path' => $filepath, 'url' => $url, 'filename' => $filename];
        }

        return ['success' => false, 'message' => 'Gabim në ruajtje të file-it.'];
    }

    public static function deleteFile($filepath) {
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        return false;
    }
}

