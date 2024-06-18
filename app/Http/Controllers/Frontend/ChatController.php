<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Order;
use App\Models\Role;
use App\Notifications\Order\MessageAdded;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;
use MediaUploader;
use Symfony\Component\Mailer\Exception\TransportException;

class ChatController extends Controller
{
    public function index($order_id)
    {
        $order = Order::find($order_id);

        return response()->json(['messages' => $order->messages], 200);
    }

    public function store($order_id)
    {
        $order = \Auth::user()->orders()->findOrFail($order_id);
        $usersWithAdminRole = Role::where('name', 'administrator')->first()->users()->get();

        $message = $order->messages()->create([
            'content' => \request('content'),
            'type' => 'message',
            'user_id' => auth()->user()->getAuthIdentifier(),
            'to_id' => $usersWithAdminRole->first()->id,
        ]);

        if (\request()->has('attachments') && sizeof(\request()->input('attachments')) > 0) {
            $message->syncMedia(\request()->input('attachments'), 'attachments');
        }

        try {
            \Notification::send($usersWithAdminRole, new MessageAdded($order, \Auth::user(), route('ch-admin.order.show', $order->id)));
        } catch (Swift_TransportException|TransportException|Exception $exception) {}

        return response()->json(['messages' => $order->messages], 201);
    }

    public function uploadAttachment(): JsonResponse
    {
        $file = \request()->file('file');

        try {
            $media = MediaUploader::fromSource($file)
                ->toDestination('local', (string)'local/' . date('Ym'))
//                ->setAllowedExtensions($allowed_type)
                ->upload();
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 422);
        }

        return response()->json($media->id, 200);
    }

    public function deleteAttachment(): JsonResponse
    {
        $media = Media::find(\request('id'));
        if (!isset($media->id)) {
            return response()->json('Unable to delete file.', 422);
        } else {
            $id = $media->id;

            $media->delete();

            return response()->json($id, 200);
        }
    }
}
