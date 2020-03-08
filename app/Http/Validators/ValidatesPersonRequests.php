<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;

trait ValidatesPersonRequests
{
    /**
     * Validate new person request input
     *
     * @param  Request $request
     * @throws \Illuminate\Auth\Access\ValidationException
     */
    protected function validateNew(Request $request)
    {
        $this->validate($request, [
            'person.name'         => 'required|string',
            'person.birthdate'    => 'required|string|date',
            'person.timezone'     => 'required|string|timezone',
        ]);
    }
}
