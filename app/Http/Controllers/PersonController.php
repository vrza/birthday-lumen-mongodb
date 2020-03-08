<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Person;
use App\Http\Resources\PersonResource;
use App\Filters\PersonFilter;
use App\Http\Validators\ValidatesPersonRequests;

class PersonController extends Controller
{
    use ValidatesPersonRequests;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get all the persons.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PersonFilter $filter)
    {
        $persons = $this->paginate(Person::filter($filter));
        return PersonResource::collection($persons);
    }

    /**
     * Create a new person and return the person if successful.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateNew($request);

        $person = Person::create([
            'name' => $request->input('person.name'),
            'birthdate' => $request->input('person.birthdate'),
            'timezone' => $request->input('person.timezone'),
        ]);

        return (new PersonResource($person))
                ->response()
                ->header('Status', 201);
    }
}
