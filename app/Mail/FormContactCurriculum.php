<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FormContactCurriculum extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $email;
    /**
     * @var string
     */
    protected $mailSubject;
    /**
     * @var string
     */
    protected $message;

    /**
     * Create a new message instance.
     *
     * @param string $name
     * @param string $email
     * @param string $message
     * @param string $subject
     */
    public function __construct(string $name, string $email, string $message, string $subject = 'Sem Assunto')
    {
        $this->name = $name;
        $this->email = $email;
        $this->message = $message;
        $this->mailSubject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->to('michelbolzon@gmail.com')
            ->from(config('mail.from.address'), 'Contato do Curriculum')
            ->subject($this->mailSubject)
            ->view('mail.contact', [
            'subject' => $this->mailSubject,
            'email' => $this->email,
            'messageContact' => $this->message,
            'name' => $this->name
        ]);
    }
}
