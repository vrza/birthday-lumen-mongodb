<?php declare(strict_types=1);

use App\Birthday\Birthday;
use App\Birthday\Person;

class BirthdayTest extends TestCase
{
    /**
     * Test a normal birthday.
     *
     * @return void
     */
    public function testKenBirthday()
    {
      $name = 'Ken Thompson';
      $birthdate = '1943-02-04';
      $timezone = 'America/New_York';
      $reftime = '2020-02-03 8:00 Europe/Belgrade';

      $person = new Person($name, $birthdate, $timezone);
      $b = new Birthday($person, $reftime);
      /*
       *   24 hours in a day
       * -  8 hours after midnight)
       * +  6 hours tz diff
       * = 22 hours until birthday starts
       */
      $this->assertFalse(
        $b->isBirthday
      );
      $this->assertEquals(
        $b->interval->m, 0
      );
      $this->assertEquals(
        $b->interval->d, 0
      );
      $this->assertEquals(
        $b->interval->h, 22
      );
    }

    /**
     * Test a person born on DST switch day.
     *
     * @return void
     */
    public function testLongDSTBirthday()
    {
      $name = 'Lucky Luke';
      $birthdate = '1946-10-25';
      $timezone = 'Europe/Brussels';
      $reftime = '2020-10-25 1:30 Europe/Brussels';

      $person = new Person($name, $birthdate, $timezone);
      $b = new Birthday($person, $reftime);
      /*
       *   24   hours in a day
       * -  1.5 hours after midnight)
       * +  0   hours tz diff
       * +  1   hour added to this day when clock jumps backward at 2am
       * = 23.5 hours until birthday ends
       */
      $this->assertTrue(
        $b->isBirthday
      );
      $this->assertEquals(
        $b->interval->m, 0
      );
      $this->assertEquals(
        $b->interval->d, 0
      );
      $this->assertEquals(
        $b->interval->h, 23
      );
      $this->assertEquals(
        $b->interval->i, 30
      );
    }

    /**
     * Test that invalid arguments to Person throw an exception.
     *
     * @return void
     */
    public function testPersonException()
    {
        $this->expectException(Exception::class);
        $person = new Person("_", "2042-42-42", "America/New_York");
    }

    /**
     * Test that invalid arguments to Birthday throw an exception.
     *
     * @return void
     */
    public function testBirthdayException()
    {
        $this->expectException(TypeError::class);
        $person = new Birthday("foo");
    }
}
