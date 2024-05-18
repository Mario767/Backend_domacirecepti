<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uloga extends Model
{
    use HasFactory;

    protected $table = 'uloge';
    protected $fillable = ['naziv_uloge'];
    public $timestamps = false;
}
