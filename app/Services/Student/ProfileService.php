<?php

namespace App\Services\Student;

use App\Enums\UploadDiskEnum;
use App\Http\Requests\ProfileRequest;
use App\Traits\UploadTrait;

class ProfileService
{
    use UploadTrait;

    /**
     * Update student credential
     *
     * @param ProfileRequest $request
     * @return void
     */
    public function update(ProfileRequest $request): void
    {
        $data = $request->validated();
        $user = auth_user();

        if($request->hasFile('image')){
            if($user->student->image){
                $this->remove($user->student->image);
            }
            $data['image'] = $this->uploadFile(UploadDiskEnum::STUDENT->value, $request->file('image'));
        }

        $data = collect($data);

        $user->update($data->only([
            'name',
            'email'
        ])->toArray());

        $user->student->update($data->only([
            'phone_number',
            'gender',
            'address',
            'image'
        ])->toArray());
    }

    /**
     * logic delete image student logged in
     *
     * @return boolean
     */
    public function deleteImage(): bool
    {
        $student = auth_student();
        $image = $student->image;

        if($image) {
            $this->remove($image);
            $student->update([
                'image' => null,
            ]);

            return true;
        } else {
            return false;
        }
    }
}
