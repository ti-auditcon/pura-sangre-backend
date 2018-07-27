<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->m"id"dleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $events = [
         [
           "id" => 1,
           "title" => '23 reservas',
           "start" => '10:00',
           "end" => '11:00',
           "className" => 'fc-event-primary',
           "dow" => [ 1,2,3,4,5,6 ] // Repeat monday and thursday
         ],
         [
           "id" => 2,
           "title" => '15 reservas',
           "start" => '11:00',
           "end" => '12:00',
           "className" => 'fc-event-primary',
           "dow" => [ 1,2,3,4,5,6 ] // Repeat monday and thursday
         ],
         [
           "id" => 3,
           "title" => '18 reservas',
           "start" => '16:00',
           "end" => '17:00',
           "className" => 'fc-event-primary',
           "dow" => [ 1,2,3,4,5,6 ] // Repeat monday and thursday
         ],
         [
           "id" => 4,
           "title" => '8 reservas',
           "start" => '17:00',
           "end" => '18:00',
           "className" => 'fc-event-primary',
           "dow" => [ 1,2,3,4,5,6 ] // Repeat monday and thursday
         ],
         [
           "id" => 5,
           "title" => '25 reservas',
           "start" => '18:00',
           "end" => '19:00',
           "className" => 'fc-event-primary',
           "dow" => [ 1,2,3,4,5,6 ] // Repeat monday and thursday
         ],
         [
           "id" => 6,
           "title" => '23 reservas',
           "start" => '9:00',
           "end" => '10:00',
           "className" => 'fc-event-primary',
           "dow" => [ 1,2,3,4,5,6 ] // Repeat monday and thursday
         ],
         [
           "id" => 12,
           "title" => 'WOD',
           "start" => date('Y-m-d', mktime(0,0,0, date('m'),date('d'), date('Y'))),
           "allDay" => true,
           "className" => 'fc-event-success',
         ],
         [
           "id" => 13,
           "title" => 'WOD',
           "start" => date('Y-m-d', mktime(0,0,0, date('m'),date('d')+1, date('Y'))),
           "allDay" => true,
           "className" => 'fc-event-success',
         ],
         [
           "id" => 14,
           "title" => 'WOD',
           "start" => date('Y-m-d', mktime(0,0,0, date('m'),date('d')+2, date('Y'))),
           "allDay" => true,
           "className" => 'fc-event-success',
         ],
         [
           "id" => 14,
           "title" => 'WOD',
           "start" => date('Y-m-d', mktime(0,0,0, date('m'),date('d')-1, date('Y'))),
           "allDay" => true,
           "className" => 'fc-event-success',
         ],
         [
           "id" => 15,
           "title" => 'WOD',
           "start" => date('Y-m-d', mktime(0,0,0, date('m'),date('d')-2, date('Y'))),
           "allDay" => true,
           "className" => 'fc-event-success',
         ],
      ];



        return view('home')->with('events',json_encode($events));
    }


    public function blocks()
    {
      $events = [
         [
           "id" => 1,
           "title" => '23 reservas',
           "start" => '10:00',
           "end" => '11:00',
           "className" => 'fc-event-primary',
           "dow" => [ 1,2,3,4,5,6 ] // Repeat monday and thursday
         ],
         [
           "id" => 2,
           "title" => '15 reservas',
           "start" => '11:00',
           "end" => '12:00',
           "className" => 'fc-event-primary',
           "dow" => [ 1,2,3,4,5,6 ] // Repeat monday and thursday
         ],
         [
           "id" => 3,
           "title" => '18 reservas',
           "start" => '16:00',
           "end" => '17:00',
           "className" => 'fc-event-primary',
           "dow" => [ 1,2,3,4,5,6 ] // Repeat monday and thursday
         ],
         [
           "id" => 4,
           "title" => '8 reservas',
           "start" => '17:00',
           "end" => '18:00',
           "className" => 'fc-event-primary',
           "dow" => [ 1,2,3,4,5,6 ] // Repeat monday and thursday
         ],
         [
           "id" => 5,
           "title" => '25 reservas',
           "start" => '18:00',
           "end" => '19:00',
           "className" => 'fc-event-primary',
           "dow" => [ 1,2,3,4,5,6 ] // Repeat monday and thursday
         ],
         [
           "id" => 6,
           "title" => '23 reservas',
           "start" => '9:00',
           "end" => '10:00',
           "className" => 'fc-event-primary',
           "dow" => [ 1,2,3,4,5,6 ] // Repeat monday and thursday
         ],
         [
           "id" => 12,
           "title" => 'WOD',
           "start" => date('Y-m-d', mktime(0,0,0, date('m'),date('d'), date('Y'))),
           "allDay" => true,
           "className" => 'fc-event-success',
         ],
         [
           "id" => 13,
           "title" => 'WOD',
           "start" => date('Y-m-d', mktime(0,0,0, date('m'),date('d')+1, date('Y'))),
           "allDay" => true,
           "className" => 'fc-event-success',
         ],
         [
           "id" => 14,
           "title" => 'WOD',
           "start" => date('Y-m-d', mktime(0,0,0, date('m'),date('d')+2, date('Y'))),
           "allDay" => true,
           "className" => 'fc-event-success',
         ],
         [
           "id" => 14,
           "title" => 'WOD',
           "start" => date('Y-m-d', mktime(0,0,0, date('m'),date('d')-1, date('Y'))),
           "allDay" => true,
           "className" => 'fc-event-success',
         ],
         [
           "id" => 15,
           "title" => 'WOD',
           "start" => date('Y-m-d', mktime(0,0,0, date('m'),date('d')-2, date('Y'))),
           "allDay" => true,
           "className" => 'fc-event-success',
         ],
     ];



        return view('blocks.index')->with('events',json_encode($events));
    }
    public function blocksshow()
    {
      $students = Student::all();

      return view('blocks.show')->with('students',$students);
    }
}
