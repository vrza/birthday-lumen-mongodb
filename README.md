# Take-home test problem

This is an example of a take-home test problem that we could send out to candidates.

## Problem statement

Design and implement a program that keeps track of people's birthdays
and warns about upcoming birthdays. If their birthday is today,
program should warn about number of hours remaining until end of
birthday, otherwise it should warn about number of months/days left
until their next birthday.

The program should be implemented as a JSON REST service in Lumen,
using MongoDB as database, storing the following minimum information
about a person:

- Name
- Birth date (validated on input)
- Time zone (their local, validated on input)

At least two HTTP endpoints should be implemented:

- Create a new Person and persist it in the database
- List all Persons in the database with their upcoming birthdays.

Include a human readable message with details about upcoming birthday, such as:

    "Ken Thompson is 78 years old in 10 months, 29 days in America/New_York"

or

    "Ken Thompson is 78 years old today (7 hours remaining in America/New_York)"

### Notes on design

The engine should support calculating intervals from the current time,
or from a user-specified time. The latter should be used in unit
tests, that should also be provided (using PHPUnit).

#### Features outside of the project scope (don't need to be implemented)

- It is not necessary to have endpoints for deleting and modifying
Person records
- It is not necessary to enforce uniqueness of Person records
- It is not necessary to support localization (language/time format/etc.)
- It is not necessary to seed the database

#### Example query and response

    $ curl -s http://localhost:8000/person/ | jq .
    {
      "data": [
        {
          "name": "Ken Thompson",
          "birthdate": "1943-02-04",
          "timezone": "America/New_York",
          "isBirthday": false,
          "interval": {
            "y": 0,
            "m": 10,
            "d": 29,
            "h": 10,
            "i": 49,
            "s": 47,
            "_comment": "possibly other fields..."
          },
          "message": "Ken Thompson is 78 years old in 10 months, 29 days in America/New_York"
        },
    # ...

## Additional thoughts

Perhaps we should provide an empty/skeleton Lumen project to
candidates.

Pros: saves candidates' time on writing boilerplate code;
we can set up interfaces for them to code against, so that we can use
our own unit tests when checking candidate submissions; we set
expectations for the form of their submission, including dependencies,
so it's easier for us to review.

Cons: project skeleton has to be periodically updated on our end.

## Proposed solution

Previous/current birthday starts at time t1 (midnight on birthday),
ends at time t2 (midnight the next day). Similarly, their next birthday
starts at time t3, ends at t4. We obtain t1 by adding the person's age
in years (on current date/time) to midnight of their birthdate in their
time zone.

    -----t1---t2--------------------t3---t4-----> time
    [t1-t2) is prev bday     [t3-t4) is next bday

Current date/time can be either:
a) between t1 and t2, in which case we will calculate time until t2
   -- end of birthday, or
b) between t2 and t3, in which case we will calculate time until t3
   -- start of their next birthday.

Using the standard PHP DateTime and TimeInterval classes should help
deal with all and any timezone and DST switching issues.

Birth date is intentionally stored as a string, to avoid entanglement
of date, time and timezone information in a single field, as MongoDB
does not support a date-only type. Storing it in ISO 8601 format
should also allow having an ordered index, if needed. Validation of
date and time zone is done using Lumen/Laravel validations.

This repo contains a Lumen/MongoDB REST service with PHPUnit tests,
coded per requirements above.

## Other thoughts on screening software engineers

Some other thoughts on what a take-home test should include: I like to
screen candidates' understanding of algorithms and data structures,
e.g. at least getting them to demonstrate understanding and proper use
of associative arrays (hash maps or hash sets) which are the single
most useful/common data structure in the practice of programming.
Explicit and proper use of data structures is IMHO something that's
missing from this home assignment (but should in any case be checked
on the technical interview).

I tried to dump most of my thought process into this somewhat long
text, however it is possible that I missed some details. Please don't
hesitate to reach out with thoughts, comments, questions etc. more
than happy to discuss and collaboratively iterate on this.

## License

Licensed under [The 3-Clause BSD License](https://opensource.org/licenses/BSD-3-Clause).
