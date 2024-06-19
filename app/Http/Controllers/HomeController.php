<?php

namespace App\Http\Controllers;

use App\Models\appointment;

use App\Models\booking;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class HomeController extends Controller
{
    public function redirect()
    {
        if (Auth::id()) {

            if(Auth::user()->usertype=='0'){

                $doctor = Doctor::all();

                return view('user.home',compact('doctor'));

            }
            else{
                return view('admin.home');
            }
           

        }
        else{
            return redirect()->back();
        }
    }

    public function index()
    {

        if (Auth::id()){

            redirect('home');
        }
        else{

            $doctor = Doctor::all();
            return view('user.home',compact('doctor'));

        }

     
    }

    public function appointment(Request $request)
    {
        $data= new booking;

        $data->name=$request->name;

        $data->email=$request->email;

        $data->date=$request->date;

        $data->message=$request->message;

        $data->doctor=$request->doctor;

        $data->phone=$request->number;

        $data->status= 'In progress';

        if(Auth::id()){

        $data->user_id=Auth::user()->id;

        }

        $data->save();
        return redirect()->back()->with('message','appointment request successfull..we will contact with you soon');

    }

    public function myappointment()
    {
         
        if(Auth::id()){

            if(Auth::user()->usertype==0){

                $userid=Auth::user()->id;
                $appoint=booking::where('user_id',$userid)->get();
            
            return view('user.my_appointment',compact('appoint'));
    
            }
            else{
                return redirect()->back();
   
        }
        }else{
            return redirect('login');
        }
    }
    public function cancel_appoint($id)
    {
      $data=booking::find($id);
      $data->delete();
      
      return redirect()->back();
      

    }
}
