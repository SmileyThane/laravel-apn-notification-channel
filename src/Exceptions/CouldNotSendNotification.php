<?php

namespace SmileyThane\ApnNotificationChannel\Exceptions;

use Exception;
use Pushok\ApnsResponseInterface;
use SmileyThane\ApnNotificationChannel\ApnsResponseCollection;

class CouldNotSendNotification extends Exception
{
    /**
     * @var \SmileyThane\ApnNotificationChannel\ApnsResponseCollection|\Pushok\ApnsResponseInterface[]
     */
    protected $responses;

    /**
     * Create an instance of exception attaching responses.
     *
     * @param  \SmileyThane\ApnNotificationChannel\ApnsResponseCollection|\Pushok\ApnsResponseInterface[]  $responses
     * @return $this
     */
    public static function withUnsuccessful(ApnsResponseCollection $responses)
    {
        $message = $responses->map(function (ApnsResponseInterface $response) {
            return $response->getErrorDescription()
                ?? $response->getReasonPhrase()
                ?? $response->getErrorReason();
        })->unique()->implode('; ');

        return (new static($message))
            ->setResponses($responses);
    }

    /**
     * Attach the responses.
     *
     * @param  \SmileyThane\ApnNotificationChannel\ApnsResponseCollection|\Pushok\ApnsResponseInterface[]  $responses
     * @return $this
     */
    protected function setResponses(ApnsResponseCollection $responses)
    {
        $this->responses = $responses;

        return $this;
    }

    /**
     * Get the responses collection.
     *
     * @return \SmileyThane\ApnNotificationChannel\ApnsResponseCollection|\Pushok\ApnsResponseInterface[]
     */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
     * Get a first response.
     *
     * @return \Pushok\ApnsResponseInterface
     * @deprecated Use getResponses() instead.
     */
    public function getResponse()
    {
        return $this->responses[0] ?? null;
    }
}
