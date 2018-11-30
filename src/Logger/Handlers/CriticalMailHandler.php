<?php
declare(strict_types=1);

namespace Somsip\BlcKnd\Logger\Handlers;

use Somsip\BlcKnd\Mail\CriticalError;

use Carbon\Carbon;

use Monolog\Logger;
use Monolog\Handler\MailHandler;

use Mail;
use Storage;

/**
 * CriticalMailHandler uses the Laravel Mail facade to send the emails
 *
 * @author Mark White mark@somsip.com>
 * @copyright 2018 Somsip.com
 * @package Somsip\BlcKnd
 */
class CriticalMailHandler extends MailHandler
{
    const THROTTLE_MINUTES = 5;

    /**
     * The email addresses to which the message will be sent
     * @var array
     */
    protected $to;

    /**
     * The subject of the email
     * @var string
     */
    protected $subject;

    /**
     * @param integer      $level          The minimum logging level at which this handler will be triggered
     * @param boolean      $bubble         Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct($level = Logger::CRITICAL, $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->to = (array) config('blc_knd.critical');
        $this->subject = sprintf(
            '%s %s %s: CRITICAL ERROR encountered',
            config('app.name'),
            app()->environment(),
            getLocalIp()
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function send($content, array $records)
    {
        if ($this->isThrottled()) {
            return;
        }

        Mail::to($this->to)
            ->send(new CriticalError($this->subject, $content));
    }

    /**
     * Ensures we don't send excessive emails
     *
     * @return bool
     */
    private function isThrottled(): bool
    {
        $filename = 'email_throttle_' . md5($this->subject);
        if (Storage::has($filename)
            && Storage::lastModified($filename) > now()->subMinutes(self::THROTTLE_MINUTES)->timestamp
        ) {
            return true;
        }
        
        Storage::put($filename, $this->subject);
        return false;
    }
}
