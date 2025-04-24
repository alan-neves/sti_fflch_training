<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Intern;

class InternController extends Controller
{
    public function index(){
        return view('interns.index', ['interns' => Intern::all()]);
    }

    public function create(){
        Intern::truncate();

        $intern1 = new Intern;
        $intern1->name = 'John';
        $intern1->email = 'John@usp.br';
        $intern1->age = 26;
        $intern1->save();

        $intern2 = new Intern;
        $intern2->name = 'Mary';
        $intern2->email = 'mary@usp.br';
        $intern2->age = 27;
        $intern2->save();

        return redirect('/interns');
    }
}
