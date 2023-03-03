<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;
use App\Models\Subscriber;
use App\Models\Campaign;

class MailBlastJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $campaign;

    /**
     * Create a new job instance.
     */
    public function __construct($campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $lists = json_decode($this->campaign['list_id']);
        $recipients = [];

        foreach($lists as $list) {
            $subscribers = Subscriber::where('list_id', $list)->where('status','active')->get();

            foreach($subscribers as $subscriber) {
                array_push($recipients, (Object)[
                    'name'  => $subscriber->name,
                    'email' => $subscriber->email,
                    'list'  => $list
                ]);
            }
        }

        Campaign::where('id', $this->campaign->id)->update(['total_sent' => count($recipients)]);

        if(count($recipients) > 0) {
            $campaign = $this->campaign;
            $replyTo = $campaign['reply_to'] ? $campaign['reply_to'] : $campaign['from_email'];

            foreach($recipients as $recipient) {
                \Mail::send('mail-templates.temp1', ['campaign' => $campaign], function($message) use ($recipient, $campaign, $replyTo){
                    $message->to($recipient->email, $recipient->name)
                        ->from($campaign['from_email'], $campaign['from_name'])
                        ->replyTo([$replyTo, $campaign['from_name']])
                        ->subject($campaign['subject']);
                });
            }
        }
    }
}
