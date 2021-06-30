<?php
class FormSanitizer {
    public static function sanitizeFormString($inputText) {
        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ", "", $inputText);
        $inputText = strtolower($inputText);
        $inputText = ucfirst($inputText);
        return $inputText;
    }

    public static function sanitizeFormPassword($inputText) {
        $inputText = strip_tags($inputText);
        return $inputText;
    }

    public static function sanitizeFormEmail($inputText) {
	    $inputText = filter_var($inputText,FILTER_VALIDATE_EMAIL);
        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ", "", $inputText);
        return $inputText;
    }

    public static function sanitizeProfileId($inputText){
        $inputText = strtolower($inputText);
        $inputText = str_replace(" ", "", $inputText);
        return $inputText;
    }
}
