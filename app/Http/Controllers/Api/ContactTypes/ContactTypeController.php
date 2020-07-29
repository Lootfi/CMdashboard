<?php

namespace App\Http\Controllers\Api\ContactTypes;

use App\ContactType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ContactType::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $type = new ContactType([
            'name' => $request->name
        ]);
        $type->save();

        return response()->json($type, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ContactType  $contactType
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        if ($type = ContactType::with('contacts')->where('slug', $slug)->first()) {
            return response()->json($type, 200);
        }

        return response()->json("Type not found", 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ContactType  $contactType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        if ($type = ContactType::where('slug', $slug)->first()) {
            $type->name = $request->name;
            $type->save();

            return response()->json("Type updated successfuly", 200);
        }

        return response()->json("Type not found", 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ContactType  $contactType
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        if ($type = ContactType::where('slug', $slug)->first()) {
            $type->destroy();
            return response()->json("Type deleted successfuly", 200);
        }

        return response()->json("Type not found", 404);
    }
}
