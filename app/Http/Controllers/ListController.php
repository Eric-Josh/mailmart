<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ContactList;
use App\Models\Subscriber;
use App\Events\ListCreated;

class ListController extends Controller
{
    public function index()
    {
        $list = ContactList::latest()->paginate(20);

        return Inertia::render('List/Index', ['list' => $list]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:contact_lists,name'
        ]);

        $list = ContactList::create(['name' => $data['name']]);
        
        if($request->hasFile('file')) {
            $filename = bin2hex(random_bytes(12)).'.csv';
            $request->file->move(public_path('csv'), $filename);

            $data = (Object)[
                'list' => $list->id,
                'filename' => $filename
            ];
            event(new ListCreated($data));
        }

        return redirect()->route('list.index')
            ->with('message', __('List created.'));
    }

    public function show($id)
    {
        $list = ContactList::findOrFail($id);

        $subscribers = Subscriber::where('list_id', $id)->paginate(20);

        return Inertia::render('List/Show', ['list' => $subscribers]);
    }

    public function update(Request $request, $id)
    {
        $list = ContactList::findOrFail($id);

        $list->update(['name' => $request->input('name')]);

        if($request->hasFile('file')) {
            $filename = bin2hex(random_bytes(12)).'.csv';
            $request->file->move(public_path('csv'), $filename);

            $data = (Object)[
                'listId' => $id,
                'filename' => $filename
            ];
            event(new ListCreated($data));
        }

        return redirect()->route('list.index')
            ->with('message', __('List updated.'));
    }

    public function destroy($id)
    {
        $list = ContactList::findOrFail($id);

        $list->delete();

        return redirect()->route('list.index')
            ->with('message', __('List deleted.'));
    }
}
