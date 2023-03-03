<?php

namespace App\Listeners;

use App\Events\ListCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Facades\File;
use App\Models\Subscriber;
use App\Models\ContactList;
use Log;

class AddSubscribers
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ListCreated $event): void
    {
        $listId = $event->list->listId;
        $file = $event->list->filename;

        // Add to DB
        LazyCollection::make(function () use ($file, $listId) {
            $handle = fopen(public_path('csv/'.$file), 'r');

            while (($line = fgetcsv($handle, 4096)) !== false) {
                $dataString = implode(",", $line);
                $row = explode(';', $dataString);
                yield $row;
            }

            fclose($handle);
        })
        // ->skip(1)
        ->chunk(1000)
        ->each(function (LazyCollection $chunk) use ($listId) {
            $records = $chunk->map(function ($row) use ($listId) {
                $row = implode(',',$row);
                $row = explode(',',$row);
                
                return [
                    "name" => $row[0],
                    "email" => $row[1],
                    "list_id" => $listId,
                    "created_at" => now(),
                    "updated_at" => now(),
                ];
            })->toArray();

            DB::table('subscribers')->insert($records);
        });

        $subscribers = Subscriber::where('list_id', $listId)->count();

        ContactList::where('id', $listId)->update(['subscribers' => $subscribers]);

        // delete files
        if (File::exists(public_path('csv/'.$file))) {
            File::delete(public_path('csv/'.$file));
        }
    }
}
