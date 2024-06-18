<?php

namespace App\Notifications\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Order;

class OrderUpdated extends Notification
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     *
     * @param Order $order
     */
    public function __construct( Order $order )
    {
        $this->order = $order;
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

        if ($notifiable->hasRole('customer')) {
            return ['mail', 'database'];
        } else {
            return ['database'];
        }
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if ( $this->order->status == 'processing' ) {
            return (new MailMessage)
                ->subject(trans('email.order_processing.subject', ['site_name' => setting('app.name'), 'order_date' => $this->order->created_at->format('F d, Y')]))
                ->markdown('emails.order.updated', [
                        'url' => route('ch_order_view', $this->order->id),
                        'status' => 'processing'
                    ]);
        } else {
            return (new MailMessage)
                ->subject(trans('email.order_completed.subject', ['site_name' => setting('site_name'), 'order_date' => $this->order->created_at->format('F d, Y')]))
                ->markdown('emails.order.updated', [
                    'url' => route('ch_order_view', $this->order->id),
                    'status' => 'completed'
                ]);
        }

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
            'id' => $this->order->id,
            'status' => $this->order->status
        ];
    }
}
