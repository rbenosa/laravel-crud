<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Person;
use App\Models\Relation;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    
    public function __construct()
    {
        $this->title = 'organization';
    }
    


    public function index()
    {
        $organizations = Organization::with('person')->paginate(10);
        
        $data = [
            'title'         => $this->title,
            'organizations' => $organizations,
        ];

        return view('organization.index', $data);
    }



    public function create()
    {

        $people = Person::all()->where('deleted_at', null);

        $data = [
                'title'     => $this->title,
                'people'    => $people,
            ];

        return view('organization.create', $data);

    }



    public function store(Request $request)
    {
        $this->ValidateOrganizationRequest($request);

        $org = new Organization();

        $org->name = $request->name;

        $org->save();

        if (!$org) {
            return abort(500, 'Cannot create organization, please try again.');
        }

        $org_id = $org->id;

        if (isset($request->member)) {
            
            $bulk_insert = [];

            foreach ($request->member as $value) {
                array_push($bulk_insert, [
                    'organization_id'   => $org_id,
                    'person_id'         => (int) $value
                ]);
            }

            $relation = Relation::insert($bulk_insert);

            if (!$relation) {
                return abort(500);
            }

        }

        return response()->json(200);

    }



    public function view($org_id)
    {
        $org                = Organization::with('person')->find($org_id)->toArray();
        $member_ids         = array_column($org['person'], 'person_id');
        $members_result     = Person::all();
        $members_list       = [];
        $members            = []; 
 
        foreach ($members_result as $value) {
            
            if (!in_array($value['id'],$member_ids)) {
                array_push($members_list, $value);
            } else {
                array_push($members, $value);
            }
        }

        $data = [
            'title'     => $this->title,
            'org'       => $org,
            'members'   => $members,
            'mem_list'  => $members_list
        ];

        return view('organization.view', $data);
    }   



    public function add_member(Request $request)
    {
        if (!isset($request->member)) {

            return response()->json([
                'errors' => "Please select member",
            ], 422);
        }

        $bulk_insert = [];

        foreach ($request->member as $value) {
            array_push($bulk_insert, [
                'person_id'         => (int) $value,
                'organization_id'   => (int) $request->org_id
            ]);
        }

        $relation = Relation::insert($bulk_insert);

        if (!$relation) {
            return abort(500);
        }

        return response()->json(200);
    }



    public function update(Request $request)
    {
        $this->ValidateOrganizationRequest($request);

        $org = Organization::find($request->org_id);

        $org->name   = $request->name;

        $org->save();

        if (!$org) {
            return abort(500);
        }

        return response()->json(200);

    }



    public function delete(Request $request)
    {
        $org        = Organization::find($request->id);
        $relation   = Relation::where('organization_id', $request->id);

        $org->delete();
        $relation->delete();

        if (!$org || !$relation) {
            return abort(500);
        }

        return response()->json(200);

    }



    public function delete_member(Request $request)
    {
        $org = Relation::where('person_id', $request->member_id)->where('organization_id', $request->org_id)->first();

        $org->delete();

        if (!$org) {
            return abort(500);
        }

        return response()->json(200);

    }



    public function ValidateOrganizationRequest($request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
    }
}
