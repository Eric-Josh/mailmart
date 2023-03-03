<?php

namespace App\Observers;

use App\Models\ContactList;

class ContactListObserver
{
    /**
     * Handle the ContactList "created" event.
     */
    public function created(ContactList $contactList): void
    {
        //
    }

    /**
     * Handle the ContactList "updated" event.
     */
    public function updated(ContactList $contactList): void
    {
        //
    }

    /**
     * Handle the ContactList "deleted" event.
     */
    public function deleted(ContactList $contactList): void
    {
        \App\Models\Subscriber::where('list_id', $contactList->id)->delete();
    }

    /**
     * Handle the ContactList "restored" event.
     */
    public function restored(ContactList $contactList): void
    {
        //
    }

    /**
     * Handle the ContactList "force deleted" event.
     */
    public function forceDeleted(ContactList $contactList): void
    {
        //
    }
}
