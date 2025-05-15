<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCoverPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'photo_path',
    'crop_x',
    'crop_y',
    'crop_width',
    'crop_height',
    'cropped_photo_path'
];
}
