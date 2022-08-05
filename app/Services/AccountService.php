<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AccountService
{
    public function getAccountById(int $id): Model|User|null
    {
        return User::query()->where('id', $id)->first();
    }

    /**
     * @throws ApiException
     */
    public function updateAccount(int $id, array $data): Model|User
    {
        $user = User::query()->where('id', $id)->first();
        if (is_null($user)) {
            throw new ApiException('USER_NOT_FOUND', 422);
        }

        $user->update($data);
        return $user;
    }

    public function deleteAccount(int $id)
    {
        return User::query()->where('id', $id)->delete();
    }

    /**
     * @throws ApiException
     */
    public function updateProfilePicture(int $id, string $encodedPhotoString): array
    {
        $imageName = sprintf("%s.%s", $id, "png");
        $path = public_path('storage/profile-photos/'.$imageName);
        Image::make($encodedPhotoString)->save($path);
        $url = '/storage/profile-photos/'.$imageName;

        try {
            $this->updateAccount($id, ['photo' => $url]);
            return [
                'photo' => $url,
            ];
        } catch (ApiException) {
            Storage::delete($url);
            throw new ApiException('PROFILE_PHOTO_UPLOAD_FAILED', 422);
        }
    }
}
