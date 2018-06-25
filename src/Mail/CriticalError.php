<?php
declare(strict_types=1);

namespace Somsip\BlcKnd\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CriticalError extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    public $subject;

    /**
     * @var string
     */
    public $content;

    /**
     * Create a new message instance.
     *
     * @param string $subject
     * @param string $content
     */
    public function __construct(string $subject, string $content)
    {
        $this->content = $content;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this->subject($this->subject)
            ->view('emails.error');
    }
}
