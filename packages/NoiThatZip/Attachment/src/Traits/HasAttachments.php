<?php

declare(strict_types=1);

namespace NoiThatZip\Attachment\Traits;
use NoiThatZip\Attachment\Models\Attachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasAttachments
{
    public function attachmentModel(): string
    {
        return config('attachment.model');
    }

    public function attachs(): MorphMany
    {
        return $this->morphMany($this->attachmentModel(), 'attachable');
    }

    public function attach($data, Model $creator)
    {
        $attachmentModel = $this->attachmentModel();
        $video = (new $attachmentModel())->createAttach($this, $data, $creator);
        return $video;
    }

    /**
     * @param $id
     * @param $data
     * @param Model|null $parent
     *
     * @return mixed
     */

    public function updateAttach($id, $data)
    {
        $attachmentModel = $this->attachmentModel();
        $attach = (new $attachmentModel())->updateAttach($id, $data);
        return $attach;
    }



    /**

     * @param $id

     *

     * @return mixed

     */

    public function deleteAttach($id): bool
    {
        $attachmentModel = $this->attachmentModel();
        return (bool) (new $attachmentModel())->deleteAttach($id);
    }

    /**
     * @return mixed
     */

    public function attachCount(): int
    {
        return $this->attachs->count();
    }

}

