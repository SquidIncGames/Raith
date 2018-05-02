<?php

namespace Raith\Model;

class DiscordModel{
    public const HISTORIQUE = 'https://discordapp.com/api/webhooks/440528543491948564/C2z5HN2keRL6eKuhxcv78vlO9QVRxooCgRE347wzArjOQYOdDvCNDlmawO1wLXA_jS64';
    public const INSCRIPTION = 'https://discordapp.com/api/webhooks/441304863461343242/6JjW1cjOQfE69ERlCm2Dr6VEntAA65Q7JQIzdXEALD37qqv-RkAyGRgVe45lZGvMKeca';

    public static function send(string $webhook, string $message): bool{
        $post = json_encode([ 'content' => $message ]);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $webhook);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        $output = json_decode(curl_exec($curl), true);
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) != 204) {
            throw new Exception($output['message']);
        }
        curl_close($curl);
        return true;
    }

    public static function historique(string $message): bool{
        return static::send(static::HISTORIQUE, $message);
    }

    public static function inscription(string $message): bool{
        return static::send(static::INSCRIPTION, $message);
    }
}