<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'groups';

    public function users()
    {
        return $this->hasMany(User::class,'group_id','id');
    }

    public function postBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getAll()
    {
        $groups = DB::table($this->table)
            ->orderBy('name', 'asc')
            ->get();

        return $groups;
    }
}
