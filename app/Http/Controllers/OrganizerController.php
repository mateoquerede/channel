<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Event;
use App\Models\Visitor;
use App\Models\User;
use App\Models\Talk;
use Carbon\Carbon;

class OrganizerController extends Controller
{
    public function eventsIndex()
    {
        $events = Event::get()->all();
        $visitors = Visitor::where('approved', null)->get();

        return view('organizer.events.index', compact('events', 'visitors'));
    }

    public function eventsCreate(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'date' => 'required',
            'inscription' => 'required',
        ]);

        if ($request->approve == 'on') {
            $approve = true;
        } else {
            $approve = false;
        }

        do {
            $custid = createCustomid();
        } while (Event::where('custid', $custid)->first() <> null);

        $event = new Event([
            'custid' => $custid,
            'title' => $request->title,
            'date' => $request->date,
            'inscription' => implode("*", $request->inscription),
            'approve' => $approve
        ]);
        $event->save();

        Role::create(['name' => $custid]);

        return redirect()->back()->with('success', 'Evento dado de alta correctamente');
    }

    public function visitorAccept(Request $request, $id)
    {
        $visitor = Visitor::find($id);
        $visitor->approved = true;
        $visitor->update();

        return redirect()->back()->with('successvisitors', 'Inscripción aceptada correctamente');
    }

    public function visitorReject(Request $request, $id)
    {
        $visitor = Visitor::find($id);
        $visitor->approved = false;
        $visitor->update();

        return redirect()->back()->with('successvisitors', 'Inscripción rechazada correctamente');
    }

    public function visitorScan(Request $request, $custid)
    {
        $visitor = Visitor::where('custid', $custid)->first();

        $visitor->present = true;
        $visitor->update();

        // Código reservado para imprimir la etiqueta
        // QrCode::format('png')->generate('Embed me into an e-mail!'), 'QrCode.png', 'image/png')
        // $qr = QrCode::format('png');  //Will return a png image
        // dd($qr);
        // print($qr);

        // Código reservado para mandar mail al expositor con el que tenga reunión

        // {!! QrCode::size(500)->generate(route('organizer.visitor.track', ['custid' => \Auth::user()->custid])); !!}

        return view('organizer.visitor.scan', compact('visitor'));
    }

    public function visitorPrint(Request $request, $custid)
    {
        $visitor = Visitor::where('custid', $custid)->first();

        // Código reservado para imprimir la etiqueta
        // QrCode::format('png')->generate('Embed me into an e-mail!'), 'QrCode.png', 'image/png')
        // $qr = QrCode::format('png');  //Will return a png image
        // dd($qr);
        // print($qr);

        return redirect()->back();
    }

    public function visitorTrack(Request $request, $custid)
    {
        $visitor = Visitor::where('custid', $custid)->first();

        return view('organizer.visitor.track', compact('visitor'));
    }

    public function exhibitors()
    {
        $events = Event::get()->all();
        $exhibitors = User::whereHas('roles', function($q){
            $q->where('name', 'exhibitor');
        })->get();

        return view('organizer.events.exhibitors', compact('events', 'exhibitors'));
    }

    public function exhibitorsCreate(Request $request)
    {
        $events = $request->events;
        $exhibitors = $request->exhibitors;

        $users = User::whereIn('id', $exhibitors)->get();

        if ($events) {
            $events = Event::whereIn('id', $events)->get();

            foreach ($events as $event) {
                $roles[] = $event->custid;
            }
        }

        $roles[] = 'exhibitor';

        foreach ($users as $user) {
            $user->assignRole($roles);
        }

        return redirect()->back()->with('success', 'Expositor dado de alta correctamente');
    }

    public function talks()
    {
        $events = Event::get()->all();
        $talks = Talk::get()->all();
        $exhibitors = User::whereHas('roles', function($q){
            $q->where('name', 'exhibitor');
        })->get();

        return view('organizer.events.talks', compact('talks', 'exhibitors', 'events'));
    }

    public function talksCreate(Request $request)
    {
        if ($request->new_talk == 'on') {
            do {
                $custid = createCustomid();
            } while (Talk::where('custid', $custid)->first() <> null);

            $talk = new Talk([
                'custid' => $custid,
                'event_id' => $request->event,
                'exhibitor_id' => $request->exhibitor,
                'title' => $request->title
            ]);
            $talk->save();
        } else {
            foreach ($request->talks as $talk) {
                $talk = Talk::find($talk);
                
                $talk->exhibitor_id = $request->exhibitor;
                $talk->update();
            }
        }
        
    }

    // public function exhibitorsCreate(Request $request)
    // {
    //     $events = $request->events;
    //     $exhibitors = $request->exhibitors;

    //     $users = User::whereIn('id', $exhibitors)->get();

    //     if ($events) {
    //         $events = Event::whereIn('id', $events)->get();

    //         foreach ($events as $event) {
    //             $roles[] = $event->custid;
    //         }
    //     }

    //     $roles[] = 'exhibitor';

    //     foreach ($users as $user) {
    //         $user->assignRole($roles);
    //     }

    //     return redirect()->back()->with('success', 'Expositor dado de alta correctamente');
    // }
}
