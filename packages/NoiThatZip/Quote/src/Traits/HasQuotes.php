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

namespace NoiThatZip\Quote\Traits;

use NoiThatZip\Quote\Models\Quote;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasQuotes
{
  /**

     * @return string

     */

    public function quoteModel(): string
    {
        return config('quote.model');
    }

    /**
     * @return mixed
     */

    public function quotes(): MorphMany
    {
        return $this->morphMany($this->quoteModel(), 'quoteable');
    }



    /**

     * @param $data

     * @param Model      $creator

     * @param Model|null $parent

     *

     * @return static

     */

    public function quote($data, Model $creator)
    {
        $quoteModel = $this->quoteModel();
        $quote = (new $quoteModel())->createQuote($this, $data, $creator);
        return $quote;
    }



    /**

     * @param $id

     * @param $data

     * @param Model|null $parent

     *

     * @return mixed

     */

    public function updateQuote($id, $data)
    {
        $quoteModel = $this->quoteModel();
        $quote = (new $quoteModel())->updateQuote($id, $data);
        return $quote;
    }



    /**

     * @param $id

     *

     * @return mixed

     */

    public function deleteQuote($id): bool

    {

        $quoteModel = $this->quoteModel();



        return (bool) (new $quoteModel())->deleteQuote($id);

    }



    /**

     * @return mixed

     */

    public function quoteCount(): int

    {

        return $this->quotes->count();

    }

}

