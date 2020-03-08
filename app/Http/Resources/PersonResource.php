<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\Resource;
use App\Birthday\Birthday;
use App\Birthday\Person;

class PersonResource extends Resource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'person';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $person = new Person($this->name, $this->birthdate, $this->timezone);
        // TODO Authenticate user and use their preset timezone.
        // Allow passing user timezone as request parameter for now.
        $refTime = 'now';
        if ($request->has('timezone')) {
            $refTime .= ' ' . $request->input('timezone');
        }
        $b = new Birthday($person, $refTime);

        return [
            'name'        => $this->name,
            'birthdate'   => $this->birthdate,
            'timezone'    => $this->timezone,
            'isBirthday'  => $b->isBirthday,
            'interval'    => $b->interval,
            'message'     => $b->pretty(),
        ];
    }
}
