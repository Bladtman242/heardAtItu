<?php

class Page {

    private static $title;
    private static $titleSeparator = "";
    private static $siteTitle = "";
    private static $header = "";

    public static function setTitle($title) {
        Page::$title = $title;
    }
    
    public static function getTitle() {
        return Page::$title;
    }
    
    public static function setTitleSeparator($titleSeparator) {
        Page::$titleSeparator = $titleSeparator;
    }
    
    public static function getTitleSeparator() {
        return Page::$titleSeparator;
    }
    
    public static function setSiteTitle($siteTitle) {
        Page::$siteTitle = $siteTitle;
    }
    
    public static function getSiteTitle() {
        return Page::$siteTitle;
    }
    
    public static function getFullTitle() {
        return (Page::$title ? Page::$title.Page::$titleSeparator : "").Page::$siteTitle;
    }
    
    public static function setHeader($header) {
        Page::$header = $header;
    }
    
    public static function getHeader() {
        return Page::$header;
    }

}

?>