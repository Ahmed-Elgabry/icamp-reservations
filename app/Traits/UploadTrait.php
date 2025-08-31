<?php

namespace App\Traits;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

trait UploadTrait
{

    public static function StoreFileAs($directory, $uploadedFile, $newFileName, $locale = null){
        return $uploadedFile->storeAs($directory, self::NewFileNameWithExtension($uploadedFile, $newFileName, $locale));
    }

    public static function StoreFile($directory, $uploadedFile){
        return $uploadedFile->store($directory);
    }

    public static function NewFileNameWithExtension($uploadedFile, $newFileName, $locale){
        return self::SlugifyFileName($newFileName, $locale).self::AddExtension($uploadedFile);
    }

    public static function SlugifyFileName($newFileName, $locale){
        $sluggedFileName = Str::slug($newFileName, '-');
        return $locale ? $sluggedFileName.'-'.$locale : $sluggedFileName;
    }

    public static function AddExtension($uploadedFile){
        return '.'.$uploadedFile->extension();
    }

    public static function ResizeImage($width, $height, $path){
        Image::make('storage/'.$path)->resize($width, $height)->save('storage/'.$path);
    }

    public static function deleteFile($path){
        Storage::delete($path);
    }

    public static function deleteFiles($paths){
        foreach($paths as $path){
            Storage::delete($path);
        }
    }

     
    public function getImageAttribute($value)
    {
        return asset('storage/'.$value);
    }
}
