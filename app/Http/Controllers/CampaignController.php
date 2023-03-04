<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ContactList;
use App\Models\Campaign;
use App\Jobs\MailBlastJob;
use App\Models\Subscriber;

class CampaignController extends Controller
{
    public function index()
    {
        $campaign = Campaign::latest()->paginate(20);

        return Inertia::render('Campaign/Index', ['campaign' => $campaign]);
    }

    public function create()
    {
        $list = ContactList::all();

        return Inertia::render('Campaign/Create', ['list' => $list]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required|string',
            'from_name' => 'required|string',
            'message' => 'required',
        ]);

        if(count($request->list) === 0) {
            return redirect()->route('campaign.index')
                ->with('message', __('No list selected.'));
        }

        $campaign = Campaign::create([
            'subject'   => $data['subject'],
            'from_name' => $data['from_name'],
            'from_email'=> env('MAIL_FROM_ADDRESS'),
            'reply_to'  => $request->reply_to,
            'message'   => $data['message'],
            'list_id'   => json_encode($request->list)
        ]);
        
        dispatch(new MailBlastJob($campaign))->delay(5);

        return redirect()->route('campaign.index')
            ->with('message', __('Campaign is now sending to list.'));
    }

    public function show($id)
    {
        $campaign = Campaign::findOrFail($id);

        return Inertia::render('Campaign/Show', ['campaign' => $campaign]);
    }

    public function update(Request $request, $id)
    {
        
    }

    public function destroy($id)
    {
        $campaign = Campaign::findOrFail($id);

        $campaign->delete();

        return redirect()->route('campaign.index')
            ->with('message', __('Campaign deleted.'));
    }

    public function unsubscribe(Request $request, $id, $token, $campaignId)
    {
        $subscriber = Subscriber::where('id', $id)->first();
        $subscribers = Subscriber::where('email', $subscriber->email)->get();

        foreach($subscribers as $subscriber) {
            if($subscriber->status == 'active'){
                $subscriber->update(['status' => 'unsubscribe']);
    
                $contactList = ContactList::find($subscriber->list_id);
                $contactList->update(['unsubscribed' => $contactList->unsubscribed + 1]);
    
                $campaign = Campaign::findOrFail($campaignId);
                $campaign->update(['total_unsubscribed' => $campaign->total_unsubscribed + 1]);
            }
        }

        return Inertia::render('Campaign/Unsubscribe');
    }
}
