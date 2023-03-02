<?php

namespace App\Listeners;

use App\Events\ListCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use App\Models\Subscriber;

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
        $listId = $event->list->id;
        $file = $event->file;
        $filename = '';

        // add file to public path

        LazyCollection::make(function () {
            $handle = fopen(public_path($filename), 'r');

            while (($line = fgetcsv($handle, 4096)) !== false) {
                $dataString = implode(", ", $line);
                $row = explode(';', $dataString);
                yield $row;
            }

            fclose($handle);
        })
        ->skip(1)
        ->chunk(1000)
        ->each(function (LazyCollection $chunk) {
            $records = $chunk->map(function ($row) {
                return [
                    "list_id" => $listId,
                    "name" => $row[0],
                    "email" => $row[1],
                ];
            })->toArray();

            DB::table('subscribers')->insert($records);
        });
    }
}
