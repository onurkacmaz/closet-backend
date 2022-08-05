<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Closet;
use App\Models\Item;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ItemService
{

    public function __construct()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');
        ini_set('max_input_time', '-1');
        ini_set('post_max_size', '-1');
        ini_set('upload_max_filesize', '-1');
        ini_set('max_file_uploads', '-1');
        ini_set('max_input_vars', '-1');
    }

    public function create(int $closetId, string $name, string $note, array $photos, array $links = []): Model|Item
    {
        $item = Item::query()->create([
            'closet_id' => $closetId,
            'name' => $name,
            'note' => $note,
        ]);

        $urls = $this->uploadPhotos($item, $photos);

        $item->photos()->insert($urls);

        foreach ($links as $link) {
            if (!empty($link['value'])) {
                $item->relatedLinks()->create([
                    'value' => $link['value'],
                ]);
            }
        }

        $item->load('photos', 'relatedLinks');
        return $item;
    }

    private function uploadPhotos(Item|Model $item, array $photos): array
    {
        $urls = [];
        foreach ($photos as $photo) {
            $imageName = sprintf("%s.%s", random_int(0, 99999999), "png");
            $path = public_path('storage/photos/'.$imageName);
            Image::make($photo['base64'])->save($path);
            $urls[] = ['item_id' => $item->id, 'url' => '/storage/photos/'.$imageName];
        }
        return $urls;
    }

    public function delete(int $id): bool
    {
        return Item::query()->where('id', $id)->delete();
    }

    public function getItemById(int $itemId): Item|Model|null {
        return Item::query()->with('closet', 'photos', 'relatedLinks')->where('id', $itemId)->first();
    }

    /**
     * @throws ApiException
     */
    public function update(int $itemId, string $name, string $note, array $photos, array $links = []): Model|Item
    {
        $item = $this->getItemById($itemId);
        if (is_null($item)) {
            throw new ApiException("ITEM_NOT_FOUND", 422);
        }

        $item->update([
            'name' => $name,
            'note' => $note
        ]);

        $item->photos()->whereNotIn('id', array_column($photos, 'id'))->delete();

        $photosToUpload = array_filter($photos, static function ($photo) {
            return !isset($photo['id']) && isset($photo['base64']);
        });
        if (count($photosToUpload) > 0) {
            $urls = $this->uploadPhotos($item, $photosToUpload);
            $item->photos()->insert($urls);
        }

        $item->refresh();

        return $item;
    }
}
