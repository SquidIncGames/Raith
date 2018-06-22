<?php

namespace Raith\Model\Custom;

use Krutush\Path;

class DiscordModel{
    public static function send(string $webhook, string $message): bool{
        $post = json_encode([ 'content' => $message ]);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://discordapp.com/api/webhooks/'.$webhook);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        $output = json_decode(curl_exec($curl), true);
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) != 204) {
            throw new \Exception($output['message']);
        }
        curl_close($curl);
        return true;
    }

    public static function getWebhook(string $key): string{
        $hooks = include(Path::get('CFG').'/Discord.php');
        if(!array_key_exists($key, $hooks))
            throw new \Exception('Key not exists');

        return $hooks[$key];
    }

    public static function historique(string $message): bool{
        return static::send(static::getWebhook('historique'), $message);
    }

    public static function inscription(string $message): bool{
        return static::send(static::getWebhook('inscription'), $message);
    }
}