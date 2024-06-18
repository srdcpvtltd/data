<?php

namespace App\Http\Livewire\Admin\Message;

use App\Models\Media;
use App\Models\Order;
use App\Notifications\Order\MessageAdded;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Symfony\Component\Mailer\Exception\TransportException;

class ChatBox extends Component
{
    public $order = null;

    public $message = null;

    public array $attachments = [];

    public array $attachmentIds = [];

    public $receiver_id = null;

    public $receiverAvatar = '';
    public $senderAvatar = '';

    protected $listeners = [
        'refreshMessages' => '$refresh',
        'mediaDeleted' => 'removeAttachmentIds',
        'setAttachmentIds' => 'setAttachmentIds',
    ];

    public function mount()
    {
        $orderId = request()->route()->parameter('order_id');

        // if chat is open
        if ($orderId) {
            $this->order = Order::findOrFail($orderId);
            $this->order->messages()->update(['seen_at' => now()]);

            $this->receiver_id = auth()->user()->getAuthIdentifier();
            $this->receiverAvatar = get_gravatar($this->order->user->email);
            $this->senderAvatar = get_gravatar(auth()->user()->email);
        }
    }

    public function sendMessage()
    {
        $message = $this->order->messages()->create([
            'content' => $this->message,
            'type' => 'message',
            'user_id' => auth()->user()->getAuthIdentifier(),
            'to_id' => $this->order->user_id
        ]);

        if (count($this->attachmentIds) > 0) {
            $message->syncMedia($this->attachmentIds, 'attachments');
        }

        try {
            $this->order->user->notify(new MessageAdded($this->order, Auth::user(), route('ch_order_view', $this->order->id)));
        } catch (Swift_TransportException|TransportException|Exception $exception) {}

        $this->message = '';
        $this->attachmentIds = [];
        $this->attachments = [];

        $this->emit('refreshMessages');
        $this->dispatchBrowserEvent('scrollToBottom');
    }

    public function scrollToBottom(): void
    {
        $this->emit('scrollToBottom');
    }

    public function openChat($id)
    {
        $this->order = Order::find($id);

        return redirect()->to(route('ch-admin.order.messages', ['order_id' => $id]));
    }

    public function setAttachmentIds($id): void
    {
        $this->attachmentIds[$id] = $id;

        $this->attachments = Media::whereIn('id', $this->attachmentIds)->get()->toArray();
    }

    public function removeAttachmentIds($id): void
    {
        unset($this->attachmentIds[$id]);

        $this->attachments = Media::whereIn('id', $this->attachmentIds)->get()->toArray();
    }

    public function deleteMedia($id): void
    {
        $media = Media::findOrFail($id);
        if ($media->media_meta) {
            foreach ($media->media_meta['sizes'] as $key => $value) {
                unlink($value['path']);
            }
        }

        $media->delete();

        $this->emit('mediaDeleted', $id);
    }

    public function render()
    {
        $orders = Order::with(['messages'])->orderByDesc(function ($query) {
            $query->select('created_at')
            ->from('comments')
            ->whereColumn('object_id', 'orders.id')
            ->orderByDesc('comments.created_at')
            ->limit(1);
        })->get();


        if ($this->order) {
            $orders = $orders->sortByDesc(function ($order) {
                return $order->id === $this->order->id ? 1 : 0;
            });
        }

        return view('livewire.admin.message.chat-box', [
            'messages' => $this->order->messages ?? [],
            'orders' => $orders
        ]);
    }
}
