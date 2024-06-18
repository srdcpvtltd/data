<?php

namespace App\Notifications\Order;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MessageAdded extends Notification
{
    use Queueable;

    public $order, $user, $route;

    /**
     * Create a new notification instance.
     *
     * @param Order $order
     * @param User $user
     * @param $route
     */
    public function __construct(Order $order, User $user, $route)
    {
        $this->order    = $order;
        $this->user     = $user;
        $this->route    = $route;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($this->order->user_id == 0) {
            return ['mail'];
        }

        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(trans('email.message_received.subject', ['sender' => $this->user->name, 'order_id' => $this->order->id]))
            ->markdown('emails.message_received', [
                'receiver' => $notifiable->name,
                'sender' => $this->user->name,
                'order_id' => $this->order->id,
                'url' => $this->route
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message_id' => $this->order->messages()->latest('id')->first()->id,
            'order_id' => $this->order->id,
        ];
    }
}
