<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    public $table = 'chat';
    protected $fillable = [
        'message',

        'to_user_id',
        'from_user_id'
    ];
    protected $hidden = [
        'remember_token',
        'updated_at',
        'created_at'

    ];
   public function fromUser(){
     return  $this->hasOne(User::class,foreignKey:'id',localKey:'from_user_id');
   }

   public function toUser(){
    return  $this->hasOne(User::class,foreignKey:'id',localKey:'to_user_id');
  }
}
