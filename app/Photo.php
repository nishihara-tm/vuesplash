<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
  protected $keyType= 'string';
  protected $appends = [
    'url', 'likes_count', 'liked_by_user'
  ];

  protected $visible = [
      'id', 'owner', 'url', 'comments', 'likes_count', 'liked_by_user'
  ];

  protected $perPage = 15;

  const ID_LENGTH = 12;

  public function owner() {
    return $this->belongsTo('App\User', 'user_id', 'id', 'users');
  }

  public function comments() {
    return $this->hasMany('App\Comment')->orderBy('id', 'desc');
  }

  public function likes() {
    return $this->belongsToMany('App\User', 'likes')->withTimestamps();
  }


  public function __construct(array $attributes = [] ){
    parent::__construct($attributes);

    if(! array_get($this->attributes, 'id')){
      $this->setId();
    } 
  }

  public function setId(){
    $this->attributes['id'] = $this->getRandomId();
  }

  public function getLikesCountAttribute() {
    return $this->likes->count();
  }

  public function getLikedByUserAttribute(){
    if(\Auth::guest()){
      return false;
    }

    return $this->likes->contains(function($user){
      return $user->id === \Auth::user()->id; 
    });
  }

  public function getUrlAttribute() {
    return \Storage::cloud()->url($this->attributes['filename']);
  }

  public function getRandomId(){
        $characters = array_merge(
            range(0, 9), range('a', 'z'),
            range('A', 'Z'), ['-', '_']
        );

        $length = count($characters);

        $id = "";

        for ($i = 0; $i < self::ID_LENGTH; $i++) {
            $id .= $characters[random_int(0, $length - 1)];
        }

        return $id;
  }
}
