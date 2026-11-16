<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
   protected function casts(): array{

    return['date' => 'date', 'complete'=>'boolean', 'complete_in' => 'datetime'];
   }

   public function user(){
      return $this->belongsTo(User::class);
   }

   public function tasks(){
      return $this->hasMany(Task::class);
   }

   public $timestamps = false;
}
