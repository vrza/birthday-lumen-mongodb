<?php declare(strict_types=1);

namespace App\Birthday;

use \DateTime;
use \DateInterval;

/**
* Birthday warns about the upcoming birthday for a given Person.
*/
class Birthday {
  // $now is our reference timestamp (can be injected from tests)
  public DateTime $now;
  public Person $person;
  public int $age;
  public int $nextAge;
  // is $now this person's birthday
  public bool $isBirthday;
  // interval until next birthday, or if $isBirthday, until birthday ends
  public DateInterval $interval;

 /*
  *  Previous/current birthday starts at time t1 (midnight on birthday),
  *  ends at time t2 (midnight the next day). Similarly, their next birthday
  *  starts at time t3, ends at t4. We obtain t1 by adding the person's age 
  *  in years (on current date/time $t) to midnight of their birthdate in their
  *  time zone.
  *
  *  -----t1---t2--------------------t3---t4-----> time
  *      prev bday                  next bday
  *
  *  Current date/time $t can be either:
  *  a) between t1 and t2, in which case we will calculate time until t2
  *     -- end of birthday, or
  *  b) between t2 and t3, in which case we will calculate time until t3
  *     -- start of their next birthday.
  */
  public function __construct(Person $person, string $t = 'now') {
    $this->person = $person;
    $this->now = new DateTime($t);
    $fromBirth = $this->person->birthday->diff($this->now);
    $this->age = $fromBirth->y;
    $this->nextAge = $this->age + 1;
    // calculate t1
    $ageInterval = new DateInterval('P' . $this->age . 'Y');
    $prevBdayStart = (clone $this->person->birthday)->add($ageInterval);
    // calculate t2
    $oneDay = new DateInterval('P1D');
    $prevBdayEnd = (clone $prevBdayStart)->add($oneDay);
    // calculate t3
    $oneYear = new DateInterval('P1Y');
    $nextBdayStart = (clone $prevBdayStart)->add($oneYear);

    $this->isBirthday = $prevBdayEnd > $this->now;
    $this->interval = $this->isBirthday ? $prevBdayEnd->diff($this->now) :
                                          $nextBdayStart->diff($this->now);
  }

  public function pretty(): string {
    // TODO string localization
    $prettyInterval = self::prettyDateInterval($this->interval);
    return $this->isBirthday ?
      "{$this->person->name} is {$this->age} years old today ({$prettyInterval} remaining in {$this->person->sTimeZone})" :
      "{$this->person->name} is {$this->nextAge} years old in {$prettyInterval} in {$this->person->sTimeZone}";
  }   

  public static function prettyDateInterval(DateInterval $ti): string {
    // TODO string localization
    $loc = [
      'years'   => 'years',
      'months'  => 'months',
      'days'    => 'days',
      'hours'   => 'hours',
      'minutes' => 'minutes',
      'seconds' => 'seconds'
    ];
    $s = '';
    $sep = '';
    if ($ti->y > 0) {
      $s .= $ti->y . ' ' . $loc['years'];
      $sep = ', ';
    }
    if ($ti->m > 0) {
      $s .= $sep . $ti->m . ' ' . $loc['months'];
      $sep = ', ';
    }
    if ($ti->d > 0) {
      $s .= $sep . $ti->d . ' ' . $loc['days'];
      $sep = ', ';
    }
    if ($ti->h > 0 && $sep === '') {
      $s .= $sep . $ti->h . ' ' . $loc['hours'];
      $sep = ', ';
    }
    if ($ti->i > 0 && $sep === '') {
      $s .= $sep . $ti->i . ' ' . $loc['minutes'];
    }
    if ($ti->s > 0 && $sep === '') {
      $s .= $sep . $ti->s . ' ' . $loc['seconds'];
    }
    return $s;
  }
}
