<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Person;
use App\Models\Relation;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    
    public function __construct()
    {
        $this->title = 'person';
    }


    
    public function index()
    {
        $persons = Person::with('organization')->paginate(10);
        
        $data = [
            'title'     => $this->title,
            'persons'   => $persons,
        ];

        return view('person.index', $data);
    }



    public function view($id)
    {
        $person         = Person::with('organization')->find($id)->toArray();
        $org_ids        = array_column($person['organization'], 'organization_id');
        $org_result     = Organization::all();
        $person_org     = [];
        $organizations  = [];

        foreach ($org_result as $value) {
            if (!in_array($value['id'], $org_ids)) {
                array_push($organizations, $value);
            } else {
                array_push($person_org, $value);
            }
        }

        $data = [
            'title'             => $this->title,
            'person'            => $person,
            'organizations'     => $organizations,
            'person_org'        => $person_org,
        ];

        
        return view('person.view', $data);
    }



    public function add_organization(Request $request)
    {
        if (!isset($request->organization)) {
            
            return response()->json([
                'errors' => "Please select organization",
            ], 422);
        }

        $bulk_insert = [];

        foreach ($request->organization as $value) {
            array_push($bulk_insert, [
                'person_id'         => $request->person_id,
                'organization_id'   => (int) $value
            ]);
        }

        $organization = Relation::insert($bulk_insert);

        if (!$organization) {
            return abort(500);
        }

        return response()->json(200);
    }



    public function create()
    {
        $organizations = Organization::all()->where('deleted_at', null);

        $data = [
            'title'         => $this->title,
            'organizations' => $organizations,
        ];

        return view('person.create', $data);
    }



    public function store(Request $request)
    {
        
        $this->ValidatePersonRequest($request);

        $person = new Person();

        $person->name   = $request->name;
        $person->email  = $request->email;

        $person->save();

        if (!$person) {
            return abort(500, 'Cannot create person, please try again.');
        }

        $person_id = $person->id;

        if (isset($request->organization)) {
            
            $bulk_insert = [];

            foreach ($request->organization as $value) {
                array_push($bulk_insert, [
                    'person_id'         => $person_id,
                    'organization_id'   => (int) $value
                ]);
            }

            $relation = Relation::insert($bulk_insert);

            if (!$relation) {
                return abort(500);
            }
        
        }

        return response()->json(200);

    }



    public function update(Request $request)
    {
        $this->ValidatePersonRequest($request);

        $person = Person::find($request->person_id);

        $person->name   = $request->name;
        $person->email  = $request->email;

        $person->save();

        if (!$person) {
            return abort(500);
        }

        return response()->json(200);

    }



    public function delete(Request $request)
    {
        $person     = Person::find($request->id);
        $relation   = Relation::where('person_id', $request->id);

        $person->delete();
        $relation->delete();

        if (!$person || !$relation) {
            return abort(500);
        }

        return response()->json(200);
    }



    public function delete_organization(Request $request)
    {
        $org = Relation::where('person_id', $request->person_id)->where('organization_id', $request->org_id)->first();
        
        $org->delete();
        
        if(!$org){
            return abort(500);
        }

        return response()->json(200);
    }



    public function ValidatePersonRequest($request)
    {

        $this->validate($request, [
            'name'      => 'required',
            'email'     => 'required|email',
        ]);

    }

}
