<?php

namespace Corals\Modules\Payment\AuthorizeNet\Job;


use Corals\Modules\Payment\AuthorizeNet\Exception\AuthorizeNetWebhookFailed;
use Corals\Modules\Payment\Common\Models\WebhookCall;
use Corals\Modules\Subscriptions\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HandleSubscriptionDeleted implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \Corals\Modules\Payment\Common\Models\WebhookCall
     */
    public $webhookCall;

    /**
     * HandleInvoiceCreated constructor.
     * @param WebhookCall $webhookCall
     */
    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }


    public function handle()
    {
        logger('Invoice Created job, webhook_call: ' . $this->webhookCall->id);

        try {

            if ($this->webhookCall->processed) {
                throw AuthorizeNetWebhookFailed::processedCall($this->webhookCall);
            }

            $payload = $this->webhookCall->payload;
            if ($payload) {

                $subscription_reference = $payload['id'];
                $subscription = Subscription::where('subscription_reference', $subscription_reference)->first();

                if (!$subscription) {
                    throw AuthorizeNetWebhookFailed::invalidAuthorizeNetSubscription($this->webhookCall);
                }
                \Actions::do_action('pre_webhook_cancel_subscription', $subscription);

                $subscription->setStatus('canceled');
                $subscription->markAsCancelled();


                $this->webhookCall->markAsProcessed();
            } else {
                throw AuthorizeNetWebhookFailed::invalidAuthorizeNetPayload($this->webhookCall);
            }
        } catch (\Exception $exception) {
            log_exception($exception);
            $this->webhookCall->saveException($exception);
            throw $exception;
        }
    }
}
