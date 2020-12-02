<?php

Class ParseString
{
    /**
    *  Массив полученных строк
    */
    public $strings = [];

    
    /**
    * Исходная строка
    */
    public $string = "";

    
    /**
    * Массив плейсхолдеров и замен
    */
    public $parsed = [];


    public function __construct(String $string)
    {
        $this->string = $string;
    }


    /**
    * Получение списка сгенерированных строк
    */
    public function getStrings()
    {
        $i = 0;
        while(preg_match_all('/(\<([^<>]|<?R>)*\>)/', $this->string, $matches)) {
            foreach($matches[0] as $match) {
                $i++;
                $level = "#{$i}";
                array_push($this->parsed, [
                    'level' => $level,
                    'string' => $match,
                    'variations' => explode("::", str_replace(['<', '>'], '', $match))
                ]);
                $this->string = str_replace($match, $level, $this->string);
            }
        }

        $this->generateVariations($this->string);

        if(strpos($this->string, '<') || strpos($this->string, '>') || strpos($this->string, '::')) {
            throw new Exception("String syntax error");
            return;
        }

        return $this->strings;
    }

    
    /**
    * Получение одной строки из шаблона
    */
    private function generateVariations(String $string)
    {
        if(preg_match_all('/(#(\d)*)/', $string, $results)) {        
            foreach($results[0] as $match) {
                $variations = [];
                array_walk( $this->parsed, function($value, $key) use ($match, &$variations) {
                    if($value['level'] === $match) {
                        $variations = $value['variations'];
                        return;
                    }
                }, $counter);
        
                foreach($variations as $variation) {
                    $templated_string = str_replace($match, $variation, $string);
                    $this->generateVariations($templated_string);
                }
                return;
            }
        }

        array_push($this->strings, $string);
        return;
    }
}