<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\MessageResource;
use App\Models\Message;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $messageList = $request->user()->getMessages();
        return MessageResource::collection($messageList);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'reciever_id' => 'required|integer|min:1',
            'text' => 'required|string|min:1|max:1024'
        ]);

        $data = [
            'sender_id' => $request->user()->id,
            'reciever_id' => $request->get('reciever_id'),
            'text' => $request->get('text')
        ];

        $message = Message::create($data);
        $message->save();

        return new MessageResource($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $message = Message::findOrFail($id);

        if ($request->user()->isInterlocutor($message)) {
            return new MessageResource($message);
        }

        return response()->json([
            'data' => [
                'interlocutar' => false
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'text' => 'required|string|min:1|max:1024'
        ]);

        $message = Message::findOrFail($id);

        if ($request->user()->isMessageSender($message)) {
            $newText = $request->get('text');
            $message->fill(['text' => $newText]);
            $message->save();
            return new MessageResource($message);
        }

        return response()->json([
            'data' => [
                'successful' => false
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $response = ['data' => ['successful' => false]];

        $message = Message::findOrFail($id);

        if ($request->user()->isMessageSender($message)) {
            $message->delete();
            $response['data']['successful'] = true;
        }

        return response()->json($response);
    }

    public function view(Request $request, $id)
    {
        $message = Message::findOrFail($id);

        if ($request->user()->isMessageReciever($message)) {
            $message->viewed = true;
            $message->save();
        }

        return response()->json([
            'data' => [
                'successful' => true
            ]
        ]);
    }
}
