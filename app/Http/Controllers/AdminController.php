<?php

namespace App\Http\Controllers;



use App\Models\booking;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Notification;


use App\Models\Doctor;
use App\Notifications\SendEmailNotification;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\error;

class AdminController extends Controller
{
    public function addview()
    {
        if(Auth::id()){

           if(Auth::user()->usertype==1){

            return view('admin.add_doctor');

           }else{
            return redirect()->back();
           }
        }
       else{
        return redirect('login');
       }
        
    }

    public function upload(Request $request)
    {
       $doctor= new doctor;

       //image............

      

       $image= $request->image;

       if($image){

       $imagename=time().'.'.$image->getClientoriginalExtension();

       $request->image->move('doctorimage',$imagename);

       $doctor->image=$imagename;

       }else{
        return redirect()->back();
       }

       $doctor->name=$request->name;

       $doctor->phone=$request->number;

       $doctor->speciality=$request->speciality;

       $doctor->room=$request->room;

       $doctor->save();

       return redirect()->back()->with('message','doctor added successfully');


    }

    public function showappointments()
    {
        if(Auth::id()){

            if(Auth::user()->usertype==1){
        
        $data=booking::all();


        return view('admin.showappointments',compact('data'));

            }else{
                return redirect()->back();
            }
        }
        else{
            return redirect('login');
        }
    } 


    public function approved($id)
    {
        $data=booking::find($id);

        $data->status='approved';

        $data->save();

        return redirect()->back();

    }

    public function canceled($id)
    {

        $data=booking::find($id);

        $data->status='canceled';

        $data->save();

        return redirect()->back();

    }

    public function showdoctor()
    {
       
        $data=Doctor::all();

        return view('admin.showdoctor',compact('data'));
    }

    public Function doctordelete($id)
    {
      $doctor=Doctor::find($id);

      $doctor->delete();

      return redirect()->back();

    }

    public function updatedoctor($id)
    {

        $data=Doctor::find($id);

        return view('admin.update_doctor',compact('data'));
    }

    public function editdoctor(Request $request,$id){

        $doctor=Doctor::find($id);
        $doctor->name=$request->name;
        $doctor->phone=$request->phone;
        $doctor->speciality=$request->speciality;
        $doctor->room=$request->room;

        $image=$request->image;
        
        if($image){

        $imagename=time().'.'.$image->getClientoriginalExtension();
        $request->image->move('doctorimage',$imagename);
        $doctor->image=$imagename;

        }

        $doctor->save();
        return redirect()->back()->with('message','doctor detailes updated successfully');

    }

    public function emailview($id)
    {
        $data=booking::find($id);

       return view('admin.email_view',compact('data'));
    }

    public function sendemail(Request $request,$id)
    {
        $data=booking::find($id);

        $details =[

            'greeting'=> $request->greeting,

            'body'=> $request->body,

            'actiontext'=> $request->actiontext,

            'actionurl'=> $request->actionurl,

            'endpart'=> $request->endpart,

        ];

         Notification::send($data,new SendEmailNotification($details));
       

        return redirect()->back()->with('message','email send is successfull');

    }
    
    
}
