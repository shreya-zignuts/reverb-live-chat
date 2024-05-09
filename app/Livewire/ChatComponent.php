<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Message;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Events\MessageSent;

class ChatComponent extends Component
{
    public $user;
    public $sender_id;
    public $receiver_id;
    public $message = '';
    public $messages = [];

    public function render()
    {
        return view('livewire.chat-component');
    }

    public function mount($user_id){

        $this->sender_id = auth()->user()->id;
        $this->receiver_id = $user_id;

        $messages = Message::where(function($query){
            $query->where('sender_id', $this->sender_id)
                ->where('receiver_id', $this->receiver_id);
        })->orWhere(function($query){
            $query->where('sender_id', $this->receiver_id)
                ->where('receiver_id', $this->sender_id);
        })->with('sender:id,name', 'receiver:id,name')->get();

        foreach ($messages as $message) {
            $this->addChatMessages($message);
        }

        // dd($this->messages);

        $this->user = User::whereId($user_id)->first();
    }

    public function sendMessage(){
        // dd($this->message);

        $chatMess = new Message;
        $chatMess->sender_id = $this->sender_id;
        $chatMess->receiver_id = $this->receiver_id;
        $chatMess->message = $this->message;
        $chatMess->save();

        $this->addChatMessages($chatMess);
        broadcast(new MessageSent($chatMess))->toOthers();
        
        $this->message = '';

    }

    #[ON('echo-private:chats.{sender_id},MessageSent')]
    public function listenMessage($event){
        // dd($event);
        $chatMess = Message::whereId($event['message']['id'])
        ->with('sender:id,name', 'receiver:id,name')
        ->first();
        // dd($chatMess);
        $this->addChatMessages($chatMess);
    }

    public function addChatMessages($message){
        $this->messages[] = [
            'id' => $message->id,
            'message' => $message->message,
            'sender' => $message->sender->name,
            'receiver' => $message->receiver->name
        ];
    }
}
