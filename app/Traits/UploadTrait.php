<?php

namespace App\Traits;

use App\Helpers\ImageCompressing;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait UploadTrait
{
    /**
     * delete specified file in storage
     * @param string $file
     * @return void
     */

    public function remove(string $file): void
    {
        if ($this->exist($file)) {
            Storage::disk('public')->delete($file);
        }
    }

    /**
     * check specified file in storage
     * @param string $file
     * @return bool
     */

    public function exist(?string $file): bool
    {
        if (!$file) {
            return false;
        }

        return Storage::disk('public')->exists($file);
    }

    /**
     * Handle upload file to storage
     * @param string $disk
     * @param UploadedFile $file
     * @param bool $originalName
     * @return string
     */

    public function upload(string $disk, UploadedFile $file, bool $originalName = false): string
    {
        if (!$this->exist($disk)) {
            Storage::makeDirectory($disk);
        }

        if ($originalName) {
            return $file->storeAs($disk, $this->originalName($file));
        }

        return Storage::put($disk, $file);
    }

    /**
     * Handle get original name
     * @param UploadedFile $file
     * @return string
     */

    public function originalName(UploadedFile $file): string
    {
        return $file->getClientOriginalName();
    }

    /**
     * Handle get original extension
     *
     * @param UploadedFile $file
     * @return string
     */

    public function originalExtension(UploadedFile $file): string
    {
        return $file->getClientOriginalExtension();
    }

    /**
     * Get the storage path
     *
     * @return void
     */
    public function folderStorage(String $folderNameEnum, String $folderName)
    {
        $destinationPath = $folderName . '/' . $folderNameEnum;
        if (!file_exists(public_path('storage/' . $destinationPath))) {
            mkdir(public_path('storage/' . $destinationPath), 0777, true);
        }
        return $destinationPath;
    }

    /**
     * Image uploader with resize image
     *
     * $imagePath The image path
     * @param string $storePath the store path
     * @param array{name:string, duplicate:bool, quality:int} $options The compress option
     * @see https://image.intervention.io/v3/introduction/index
     * @see https://image.intervention.io/v3/modifying/resizing
     */
    public function compressImage($fileName, $imagePath, $storePath, array $options = []): mixed
    {
        $storedImage = ImageCompressing::process($fileName, $imagePath, $storePath, $options)->toArray();
        return $storedImage['files'];
    }

    public function renameFile($fileName, $file): mixed
    {
        $originalExtension = strtolower($file->getClientOriginalExtension());

        if ($originalExtension !== 'pdf' && $originalExtension !== 'doc' && $originalExtension !== 'docx' && $originalExtension !== 'zip') {
            $fileName .= '.webp';
        } else {
            $fileName .= '.' . $originalExtension;
        }

        $tempFilePath = sys_get_temp_dir() . '/' . $fileName;

        if (!copy($file->getRealPath(), $tempFilePath)) {
            throw new \Exception("Failed to copy file to temporary directory.");
        }

        return new UploadedFile($tempFilePath, $fileName, null, true);
    }

    public function uploadFile(string $folder, UploadedFile $file, string $customName = null, $options = []): string
    {
        $filename = $customName
            ? $customName . '.' . $file->getClientOriginalExtension()
            : Str::random(40) . '.' . $file->getClientOriginalExtension();

        $destination = storage_path('app/public/' . $folder);

        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }

        $destinationPath = $destination . '/' . $filename;

        if (!copy($file->getRealPath(), $destinationPath)) {
            throw new \Exception("Gagal menyalin file ke folder tujuan.");
        }

        return $folder . '/' . $filename;
    }
}
