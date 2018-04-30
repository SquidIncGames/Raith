<?php

namespace Raith\Model;

class DiscordModel{
    public const WEBHOOK = 'https://discordapp.com/api/webhooks/440528543491948564/C2z5HN2keRL6eKuhxcv78vlO9QVRxooCgRE347wzArjOQYOdDvCNDlmawO1wLXA_jS64';

    public static function send(string $message): bool{
        $post = json_encode([ 'content' => $message ]);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, static::WEBHOOK);
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
}