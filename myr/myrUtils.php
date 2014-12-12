<?php

final class myrUtils {

    //Display variable error
    public static function errorVariable($var_name) {
        return '<span class="plg_bis_error">Chyba! Neznámá proměnná: <em>' . $var_name . '</em>.</span>';
    }

    //Display block error
    public static function errorBlock($block_name) {
        return '<span class="plg_bis_error">Chyba! Chyba syntaxe bloku: <em>' . $block_name . '</em>.</span>';
    }

    //Dump query URL - debuging
    public static function dumpUrl($url) {
        return "<div style=\"border: 1px solid #999; padding: 5px; background: #fd0; color: #002; margin-bottom: 20px;\">
            <h3 style=\"font-size: 150%; color: #600; margin: 0; padding: 0 0 5px 0;\">Ladící výstup</h3>
            <strong>BIS query url:</strong>
            <p><a href=\"$url\">$url</a></p>
            </div>";
    }

    //Fix newlines in code form DB
    public static function fixNewline($text) {
        $text = preg_replace('/\\\\n/', '', $text);
        return $text;
    }

}