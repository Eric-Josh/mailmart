<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use romanzipp\QueueMonitor\Traits\IsMonitored;
use Mail;
use App\Models\Subscriber;
use App\Models\Campaign;
use App\Models\FailedMessage;
use Log;

class MailBlastJob implements ShouldQueue
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
            $replyTo = $campaign['reply_to'] ? $campaign['reply_to'] : $campaign['from_email'];
            $token = bin2hex(random_bytes(12));

            foreach($recipients as $key => $recipient) {
                $progress = round((($key + 1) / count($recipients)) * 100);
                $this->queueProgress($progress);

                $url = url("/campaign/unsubscribe/{$recipient->user}/{$token}/{$campaign['id']}");

                try {
                    \Mail::send('mail-templates.temp1', ['campaign' => $campaign, 'url' => $url], 
                        function($message) use ($recipient, $campaign, $replyTo) {
                        $message->to($recipient->email, $recipient->name)
                            ->from($campaign['from_email'], $campaign['from_name'])
                            ->subject($campaign['subject']);
                    });
                } catch (Exception $e) {
                    //throw $th;
                    Log::info(str_repeat("=",100));
                    Log::info('Campaign: '.$campaign['subject']);
                    Log::info('Recipient: '.$recipient->email);
                    Log::info(strval( $e ));
                    Log::info(str_repeat("=",100));

                    $currentCampaign = Campaign::find($campaign['id']);

                    $currentCampaign->update(['total_failed' => $currentCampaign->total_failed + 1]);

                    FailedMessage::create([
                        'campaign_id'   => $campaign['id'],
                        'recipient'     => $recipient->email,
                        'description'   => strval( $th->getMessage() )
                    ]);
                }
            }

            $this->queueProgress(100);
        }
    }

    public function progressCooldown(): int
    {
        return 10; // Wait 10 seconds between each progress update
    }
}
