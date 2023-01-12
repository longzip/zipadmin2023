<?php

declare(strict_types=1);

/*

 * This file is part of Laravel Commentable.

 *

 * (c) Brian Faust <hello@brianfaust.de>

 *

 * For the full copyright and license information, please view the LICENSE

 * file that was distributed with this source code.

 */



namespace NoiThatZip\Video\Traits;



use NoiThatZip\Video\Models\Video;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\MorphMany;



trait HasVideos

{

    /**

     * @return string

     */

    public function videoModel(): string

    {

        return config('video.model');

    }



    /**

     * @return mixed

     */

    public function videos(): MorphMany

    {

        return $this->morphMany($this->videoModel(), 'videolog');

    }



    /**

     * @param $data

     * @param Model      $creator

     * @param Model|null $parent

     *

     * @return static

     */

    public function video($data, Model $creator)

    {

        $videoModel = $this->videoModel();



        $video = (new $videoModel())->createVideo($this, $data, $creator);



        return $video;

    }



    /**

     * @param $id

     * @param $data

     * @param Model|null $parent

     *

     * @return mixed

     */

    public function updateVideo($id, $data)

    {

        $videoModel = $this->videoModel();



        $video = (new $videoModel())->updateVideo($id, $data);



        return $video;

    }



    /**

     * @param $id

     *

     * @return mixed

     */

    public function deleteVideo($id): bool

    {

        $videoModel = $this->videoModel();



        return (bool) (new $videoModel())->deleteVideo($id);

    }



    /**

     * @return mixed

     */

    public function videoCount(): int

    {

        return $this->videos->count();

    }

}

