<?php

namespace App\Orchid\Screens;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;





class EmailSenderScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */public function query(): array
{
    return [
        'subject' => date('F').' Campaign News',
    ];
}


    public function name(): ?string
    {
        return "Почта!";
    }


    public function description(): ?string
{
    return "Отправка сообщеий по почте клиентам!.";
}

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            Button::make('Отправить')
                ->icon('paper-plane')
                ->method('sendMessage')
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('subject')
                    ->title('Заголовок')
                    ->required()
                    ->placeholder('Cюда вводить!'),

                Relation::make('users.')
                    ->title('Введите почту клиента!')
                    ->multiple()
                    ->required()
                    ->placeholder('СЮДА ПОЧТУ ЛОХА, КОТОРМУ ХОТИТЕ ЗАСРАТЬ ПОЧТУ!')
                    ->help('То кому вы будете отправлять свой ТУПОЙ СПАМ!!!!')
                    ->fromModel(User::class,'name','email'),

                Quill::make('content')
                    ->title('Content')
                    ->required()
                    ->placeholder('Место для пасты')
                    ->help('Сюда писать ПАСТУ!.')

            ])
        ];
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'subject' => 'required|min:6|max:50',
            'users'   => 'required',
            'content' => 'required|min:10'
        ]);

        Mail::raw($request->get('content'), function (Message $message) use ($request) {
            $message->from('sample@email.com');
            $message->subject($request->get('subject'));

            foreach ($request->get('users') as $email) {
                $message->to($email);
            }
        });


        Alert::info('Ваше паста успешна улетела ЛОХУ!');
    }

}
