<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Blog\Entities\PostTranslation
 *
 * @property int $id
 * @property int $post_id
 * @property string $locale
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string|null $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PostTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|PostTranslation whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostTranslation whereExcerpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostTranslation wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostTranslation whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostTranslation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PostTranslation extends Model
{
    protected $table = "blog__post_translations";
    protected $fillable = ['title', 'slug', 'body', 'excerpt'];
}
