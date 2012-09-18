<?php 
class DoubanApp
{
    public static function getLatest()
    {
        self::analyzeRss('latest');
    }
    
    public static function getBook()
    {
        self::analyzeRss('book');
    }
    
    public static function getMovie()
    {
        self::analyzeRss('movie');
    }
    
    public static function getMusic()
    {
        self::analyzeRss('music');
    }
    
    private static function analyzeRss($pType)
    {
        //$f = App::getInstance('SaeFetchurl');
        //$content = $f->fetch('http://www.douban.com/feed/review/'.$pType);
        $content = file_get_contents('http://www.douban.com/feed/review/'.$pType);
        
        
        $xml = simplexml_load_string($content);
        $channel = $xml->channel;
        
        App::setTitle($channel->title);
        App::setVar('content', $channel->item);
        App::display('DoubanApp');
        unset($content, $xml, $channel);
    }
}