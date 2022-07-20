<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'text',
        'image',
        'video',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'image' => 'array',
        'video' => 'array',
    ];

    /**
     * The attributes that should be appends.
     *
     * @var array<string, string>
     */
    protected $appends = [
        'bookmarked_at'
    ];

    /**
     * Get the user that owns the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The bookmarks that belong to the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bookmarks()
    {
        return $this->belongsToMany(User::class, 'bookmarks', 'post_id', 'user_id')->withPivot(['id', 'bookmarked_at']);
    }

    /**
     * Add bookmarked_at attribute
     *
     * @return mixed
     */
    public function getBookmarkedAtAttribute() {
        if(!auth()->check()) {
            return null;
        }

        $bookmark = $this->bookmarks()->where('user_id', auth()->user()->id)->first();

        if($bookmark) {
            return $bookmark->pivot->bookmarked_at;
        }

        return null;
    }
}
