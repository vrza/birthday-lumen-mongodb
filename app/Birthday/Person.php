<?php declare(strict_types=1);

namespace App\Birthday;

use \DateTime;
use \DateTimeZone;

/**
* Person is defined by (name, birthday, timezone).
*/
class Person {
  public string $name;
  public string $sBirthDate;
  public string $sTimeZone;
  public DateTime $birthday;

  function __construct(string $name, string $birthdate, string $timezone) {
    $this->name = $name;
    $this->sBirthDate = $birthdate;
    $this->sTimeZone = $timezone;
    $tz = new DateTimeZone($this->sTimeZone);
    $this->birthday = new DateTime($this->sBirthDate, $tz);
  }
}
