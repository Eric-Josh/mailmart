<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ContactList;
use App\Models\Subscriber;
use App\Events\ListCreated;

class ListController extends Controller
{
    public function index(Request $request)
    {
        $list = ContactList::paginate(20);

        return Inertia::render('List/Index', ['list' => $list]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:contact_lists,name'
        ]);

        $list = ContactList::create(['name' => $data['name']]);

        if($request->file) {
            $data = (Object)[
                'list' => $list,
                'file' => $request->file
            ];
            event(new ListCreated($data));
        }

        return redirect()->route('list.index')
            ->with('message', __('User created successfully.'));
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

        $list->update(['name' => $request->name]);

        if($request->file) {
            $data = (Object)[
                'list' => $list,
                'file' => $request->file
            ];
            event(new ListCreated($data));
        }
    }

}
