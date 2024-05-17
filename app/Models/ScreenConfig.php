<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreenConfig extends Model
{
    use HasFactory;

    protected $fillable = ["screen_name"];

    public function sections() {
        return $this->hasMany(ScreenSection::class);
    }
}
