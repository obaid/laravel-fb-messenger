<?php
/**
 * User: casperlai
 * Date: 2016/8/31
 * Time: 上午1:46
 */

namespace Casperlaitw\LaravelFbMessenger\Contracts;

use Casperlaitw\LaravelFbMessenger\Collections\ReceiveMessageCollection;
use Casperlaitw\LaravelFbMessenger\Contracts\Messages\Message;
use Casperlaitw\LaravelFbMessenger\Exceptions\NotCreateBotException;
use Casperlaitw\LaravelFbMessenger\Messages\Deletable;
use Casperlaitw\LaravelFbMessenger\Messages\ReceiveMessage;

/**
 * Class BaseHandler
 * @package Casperlaitw\LaravelFbMessenger\Contracts
 */
abstract class BaseHandler implements HandlerInterface
{
    /**
     * @var Bot
     */
    protected $bot;

    /**
     * Create bot to send API
     *
     * @param $token
     *
     * @return $this
     */
    public function createBot($token)
    {
        $this->bot = new Bot($token);
        return $this;
    }

    /**
     * Set token for the bot instance
     * 
     * @param $token
     * 
     * @return null
     */
    public function setToken($token) {
        $this->bot->setToken($token);
    }

    /**
     * Send message to api
     *
     * @param Message $message
     *
     * @return array
     * @throws \Casperlaitw\LaravelFbMessenger\Exceptions\NotCreateBotException
     */
    public function send(Message $message)
    {
        if ($this->bot === null) {
            throw new NotCreateBotException;
        }
        $arguments = [$message];
        if (in_array(Deletable::class, class_uses($message))) {
            $arguments[] = $message->getCurlType();
        }
        return call_user_func_array([$this->bot, 'send'], $arguments);
    }

    /**
     * Handle the chatbot message
     *
     * @param ReceiveMessage $message
     *
     * @return mixed
     */
    abstract public function handle(ReceiveMessage $message);
}
