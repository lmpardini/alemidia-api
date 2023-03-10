<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;

class MailService
{
    public static function sendMailNovoCadastro($password, User $user)
    {

        Mail::send('sendmail', ['data' => ['password' => $password, 'nome' => $user->nome, 'usuario' => $user->usuario]], function($message) use ($user)
        {
            $message->to($user->email)->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'))->subject('Sua senha de acesso!');
        });
    }

    public static function sendMailRedefinicaoSenha($password, User $user)
    {

        Mail::send('redefinicao_senha_mail', ['data' => ['password' => $password, 'nome' => $user->nome, 'usuario' => $user->usuario]], function($message) use ($user)
        {
            $message->to($user->email)->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME'))->subject('Redefinição de senha');
        });
    }

}
