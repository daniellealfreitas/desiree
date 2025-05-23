<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index()
    {
        return view('events.index');
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        // Only admins can create events
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('events.index')
                ->with('error', 'Você não tem permissão para criar eventos.');
        }

        return view('events.create');
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        // Only admins can create events
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('events.index')
                ->with('error', 'Você não tem permissão para criar eventos.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'nullable|numeric|min:0',
            'capacity' => 'nullable|integer|min:1',
            'location' => 'nullable|string|max:255',

            'is_featured' => 'nullable|boolean',
        ]);

        $data = $request->except(['image', 'cover_image']);
        $data['created_by'] = Auth::id();
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = true;

        // Handle image uploads
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events/images', 'public');
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('events/covers', 'public');
        }

        $event = Event::create($data);

        return redirect()->route('events.show', $event->slug)
                         ->with('success', 'Evento criado com sucesso!');
    }

    /**
     * Display the specified event.
     */


public function show($slug)
{
    $event = Event::where('slug', $slug)->firstOrFail();

    // Detecção do banco de dados em uso
    $driver = DB::getDriverName();

    // Pega o dia da semana do evento
    $carbonDay = Carbon::parse($event->date)->dayOfWeek;

    if ($driver === 'mysql') {
        // MySQL: WEEKDAY() → segunda = 0 ... domingo = 6
        $dayOfWeek = $carbonDay === 0 ? 6 : $carbonDay - 1;
        $query = "WEEKDAY(`date`) = ?";
    } elseif ($driver === 'sqlite') {
        // SQLite: strftime('%w', date) → domingo = 0 ... sábado = 6
        $dayOfWeek = $carbonDay;
        $query = "strftime('%w', date) = ?";
    } else {
        abort(500, "Banco de dados não suportado: $driver");
    }

    $relatedEvents = Event::where('id', '!=', $event->id)
        ->where('is_active', true)
        ->whereRaw($query, [$dayOfWeek])
        ->whereDate('date', '>=', now())
        ->orderBy('date')
        ->limit(3)
        ->get();

    return view('events.show', compact('event', 'relatedEvents'));
}


    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        // Only admins can edit events
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('events.index')
                ->with('error', 'Você não tem permissão para editar eventos.');
        }

        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Event $event)
    {
        // Only admins can update events
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('events.index')
                ->with('error', 'Você não tem permissão para atualizar eventos.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'nullable|numeric|min:0',
            'capacity' => 'nullable|integer|min:1',
            'location' => 'nullable|string|max:255',

            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->except(['image', 'cover_image']);

        // Handle image uploads
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }

            $data['image'] = $request->file('image')->store('events/images', 'public');
        }

        if ($request->hasFile('cover_image')) {
            // Delete old cover image if exists
            if ($event->cover_image) {
                Storage::disk('public')->delete($event->cover_image);
            }

            $data['cover_image'] = $request->file('cover_image')->store('events/covers', 'public');
        }

        $event->update($data);

        return redirect()->route('events.show', $event->slug)
                         ->with('success', 'Evento atualizado com sucesso!');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event)
    {
        // Only admins can delete events
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('events.index')
                ->with('error', 'Você não tem permissão para excluir eventos.');
        }

        // Delete event images
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        if ($event->cover_image) {
            Storage::disk('public')->delete($event->cover_image);
        }

        $event->delete();

        return redirect()->route('events.index')
                         ->with('success', 'Evento excluído com sucesso!');
    }
}
