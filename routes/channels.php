<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
| ここでは、あなたのすべてのイベント放送チャンネルを登録することができます
| アプリケーションがサポートします。 指定されたチャネル認証コールバックは
| 認証されたユーザーがチャネルをリッスンできるかどうかを確認するために使用されます。
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('task-added', function() {
    return true;
});
