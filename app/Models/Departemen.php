<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departemen extends Model
{
    //
    use HasFactory;

    protected $table= 'departemen';
    protected $fillable = ['name'];

    public function userDivisi() {
        return $this->hasMany(UserDivisi::class);
    }
}
