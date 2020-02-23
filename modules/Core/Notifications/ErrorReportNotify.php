<?php

namespace Modules\Core\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class ErrorReportNotify extends Notification
{
    use Queueable;

    protected $exception;
    protected $errorInfo;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($exception, $errorInfo)
    {
        $this->exception = $exception;
        $this->errorInfo = $errorInfo;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage);
        $mail->subject('Error at line '.$this->exception->getLine());
        foreach($this->errorInfo as $k => $info) {
            $mail->line($k." ".$info);
        }
        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toSlack($notifiable)
    {
        $url = \Request::fullUrl();
        return (new SlackMessage)
            ->error()
            ->content($this->exception->getMessage())
            ->attachment(function ($attachment) use ($url) {
                $attachment->title('Error at line '.$this->exception->getLine(), $url)
                    ->fields($this->errorInfo);
            });
    }
}
