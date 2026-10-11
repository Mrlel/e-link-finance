<?php

    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Models\User;
    use App\Models\Equipement;
    use App\Models\Licence;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\DB;


    class AcceuilController extends Controller
    {
        public function webHome()
        {
            $user = User::all();
            $equipement = Equipement::all();
            return view('home',compact('user','equipement'));
        }
        public function webDemo()
        {
            return view('WebApp.demo');
        }
    }
