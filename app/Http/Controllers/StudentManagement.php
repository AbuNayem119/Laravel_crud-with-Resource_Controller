<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class StudentManagement extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Student::all();
        return view('index', [
            'all_data'      => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this -> validate($request, [
            'name'  => ['required'],
            'email'  => ['required','email','unique:students'],
            'cell'  => ['required'],
            'address'  => ['required'],
            'image'  => ['required'],
        ]);


        if ( $request -> hasFile('image') ) {

            $img = $request -> file('image');
            $uni_img_name = md5(time().rand()).".". $img -> getClientOriginalExtension();
            $img -> move( public_path('media/student') , $uni_img_name);
        }

        Student::create([

            'name'     => $request -> name,
            'email'    => $request -> email,
            'cell'     => $request -> cell,
            'address'  => $request -> address,
            'image'    => $uni_img_name,

        ]);

        return back() -> with('message','Data sent successfully !');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Student::find($id);
        return view('show', [
            'all_data'  => $data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Student::find($id);
        return view('edit', [
            'all_data'  => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $data = Student::find($id);

        $this -> validate($request, [
            'name'     => ['required'],
            'email'    => ['required'],
            'cell'     => ['required'],
            'address'  => ['required'],
            'image'    => ['required'],
        ]);

        $old_img = 'public/media/student/'. $data -> image;
        if ( File::exists($old_img) ) {
            File::delete($old_img);
        }

        if ( $request -> hasFile('image') ) {

            $img = $request -> file('image');
            $uni_img_name = md5(time().rand()).".". $img -> getClientOriginalExtension();
            $img -> move( public_path('media/student') , $uni_img_name);
        }

        $data -> name = $request -> name;
        $data -> email = $request -> email;
        $data -> cell = $request -> cell;
        $data -> address = $request -> address;
        $data -> image = $uni_img_name;
        $data -> update();
        return back() -> with('message','Data Updated successfully !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Student::find($id);
        $data -> delete();
        $old_img = 'public/media/student/'. $data -> image;
        if ( File::exists($old_img) ) {
            File::delete($old_img);
        }
        return back() -> with('message','Data Deleted successfully !');
    }
}
