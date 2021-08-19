<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Testing\Fluent\Concerns\Has;

class Message extends Model
{
    use HasFactory;

    public function users():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attachments():HasMany
    {
        return $this->hasMany(Attachment::class);
    }

}
