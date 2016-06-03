<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Afftree extends Model
{
    protected $table = 'afftree';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }
    public function subparent()
    {
        return $this->belongsTo(User::class, 'sub_parent_id');
    }

}
