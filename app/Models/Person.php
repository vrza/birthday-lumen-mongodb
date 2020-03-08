<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use App\Filters\Filterable;

class Person extends Model {
  use Filterable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'name',
      'birthdate',
      'timezone'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
  ];

}
