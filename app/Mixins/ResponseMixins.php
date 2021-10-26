<?php

namespace App\Mixins;

class ResponseMixins
{
    public function successJson() {
        return function($data, $message) {
            return [
                'data' => $data,
                'success' => true,
                'message' => $message
            ];
        };
    }

    public function errorJson() {
        return function($data, $message) {
            return [
                'errors' => $data,
                'success' => false,
                'message' => $message
            ];
        };
    }
}
