<?php

namespace App\Services\Teacher;

use App\Contracts\Repositories\EmployeeRepository;
use App\Contracts\Repositories\UserRepository;
use App\Enums\UploadDiskEnum;
use App\Http\Requests\Teacher\ProfileRequest;
use App\Traits\UploadTrait;

class ProfileService
{
    use UploadTrait;

    public function __construct
    (
        private UserRepository $user,
        private EmployeeRepository $employee,
    ){}

    /**
     * logic update teacher profile
     *
     * @param ProfileRequest $request
     * @return void
     */
    public function update(ProfileRequest $request): void
    {
        $authUser = auth_user();
        $authEmployee = $authUser->employee;

        if($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $this->uploadFile(UploadDiskEnum::TEACHER->value, $request->file('image'), null, 'public');
        } else {
            $image = $authEmployee->image;
        }
        
        $user = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        $employee = [
            'nip' => $request->nip,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'image' => $image,
        ];

        $this->user->update($authUser->id, $user);
        $this->employee->update($authEmployee->id, $employee);
    }

    /**
     * logic delete image employee logged in
     *
     * @return boolean
     */
    public function deleteImage(): bool
    {
        $employee = auth_employee();
        $image = $employee->image;

        if($image) {
            $this->remove($image);
            $employee->update([
                'image' => null,
            ]);

            return true;
        } else {
            return false;
        }
    }
}
