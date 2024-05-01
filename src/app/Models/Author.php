<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuinate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany as RelationsBelongsToMany;

class Author extends Model
{
    use HasFactory;

    public function books(): RelationsBelongsToMany{
        return $this->belongsToMany(Book::class)->withTimestamps();
    }
}
