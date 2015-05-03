<?php

class CLIMessage {

    public static function show($message, $foreground_color = null)
    {
        $colors['success'] = '0;32';
        $colors['fail'] = '0;31';
        $colors['info'] = '1;33';

        $colored_string = "";

        if (isset($colors[$foreground_color])) {
            $colored_string .= "\033[" . $colors[$foreground_color] . "m";
        }

        $colored_string .=  $message . "\033[0m\n";

        echo $colored_string;
    }
}
?>