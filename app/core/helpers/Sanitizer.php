<?php

namespace Internatus\Core\Helpers;


class Sanitizer {
    public static function escapeHTML($input) {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}