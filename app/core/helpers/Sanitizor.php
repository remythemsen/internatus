<?php

namespace TheWall\Core\Helpers;


class Sanitizor {
    public static function escapeHTML($input) {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}