<?php

namespace Tcds\Io\Ray\Infrastructure;

use Override;
use Tcds\Io\Ray\EventProcessor;
use Tcds\Io\Ray\EventStore;
use Tcds\Io\Ray\EventSubscriberMap;

readonly class SequentialEventProcessor implements EventProcessor
{
    /**
     * @param EventSubscriberMap<object> $subscribers
     */
    public function __construct(private EventSubscriberMap $subscribers)
    {
    }

    #[Override] public function process(EventStore $store): void
    {
        while ($event = $store->next()) {
            foreach ($this->subscribers->of($event->type) as $subscriber) {
                if (is_callable($subscriber)) {
                    $subscriber($event);
                }
            }
        }
    }
}
