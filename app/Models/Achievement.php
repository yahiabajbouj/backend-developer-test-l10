<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Achievement extends Model
{
    use HasFactory;

    public $fillable = ["point", "type"];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => trans_choice("general.achievement_title.$this->type", $this->point, ["value" => $this->point]),
        );
    }
}
