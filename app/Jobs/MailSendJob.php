<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use romanzipp\QueueMonitor\Traits\IsMonitored;
use Mailgun\Mailgun;
use Mail;
use App\Models\Subscriber;
use App\Models\Campaign;
use App\Models\FailedMessage;
use Log;

class MailSendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, IsMonitored;

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
        // $perChunk = 50;

        foreach($lists as $list) {
            $subscribers = Subscriber::where('list_id', $list)->where('status','active')->get();

            foreach($subscribers as $subscriber) {
                array_push($recipients, (Object)[
                    'user'  => $subscriber->id,
                    'name'  => $subscriber->name,
                    'email' => $subscriber->email,
                    'list'  => $list
                ]);
            }
        }
        Campaign::where('id', $this->campaign->id)->update(['total_sent' => count($recipients)]);

        if(count($recipients) > 0) {
            $this->queueProgress(0);
            $campaign = $this->campaign;
            $mg = Mailgun::create(config('services.mailgun.secret'));

            // batch recipients
            // $counter_batch = 0;
            // $per_batch_landlord = [];
            // $per_batch = [];
            // $per_batch_with_info = [];

            // foreach($recipients as $key => $recipient) {
            //     if(count($recipients) > 1000 && $counter_batch == 1000) {
            //         array_push($per_batch_landlord, $per_batch);
            //         $per_batch = [];
            //         $counter_batch = 0;
            //     }
            //     array_push($per_batch, $recipient->email);
            //     $counter_batch += 1;
            // }
            // array_push($per_batch_landlord, $per_batch);


            // foreach($per_batch_landlord as $key => $recipient) {
            //     $token = bin2hex(random_bytes(12));
            //     $progress = round((($key + 1) / count($per_batch_landlord)) * 100);
            //     $this->queueProgress($progress);

            //     $url = url("/campaign/unsubscribe/{$recipient->user}/{$token}/{$campaign['id']}");
            //     $main_message = $campaign['message'];
            //     $main_message .= '<diper_batch_landlordv style="margin-top: 100px;"><a href="'.$url.'" target="_blank">Unsubscribe</a></div>';

            //     $mg->messages()->send(config('services.mailgun.domain'), [
            //         'from'    => $campaign['from_name'].' '.$campaign['from_email'],
            //         'to'      => $recipient->email,
            //         'subject' => $campaign['subject'],
            //         'html'    => $main_message
            //     ]);
            // }

            foreach($recipients as $key => $recipient) {
                $token = bin2hex(random_bytes(12));
                $progress = round((($key + 1) / count($recipients)) * 100);
                $this->queueProgress($progress);

                $url = url("/campaign/unsubscribe/{$recipient->user}/{$token}/{$campaign['id']}");
                $main_message = $campaign['message'];
                $main_message .= '<div style="margin-top: 100px;"><a href="'.$url.'" target="_blank">Unsubscribe</a></div>';

                $mg->messages()->send(config('services.mailgun.domain'), [
                    'from'    => $campaign['from_name'].' '.$campaign['from_email'],
                    'to'      => $recipient->email,
                    'subject' => $campaign['subject'],
                    'html'    => $main_message
                ]);
            }

            $this->queueProgress(100);
        }
    }
}
