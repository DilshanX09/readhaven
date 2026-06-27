<?php

/*
     Class for exploding and imploding strings.
     Provides methods to split a string into an array and join an array back into a string.
*/
class StringExploder

{
     private $text;
     private $output;

     /*
          Explodes the given text by the specified character.
          Returns an array of the exploded strings.
     */
     public function explode($text, $character)
     {
          $this->text = explode($character, $text);
     }

     /*
          Implodes the exploded text back into a string.
          Returns the imploded string.
     */
     public function implode($character)
     {
          $this->output = implode($character, $this->text);
     }

     /*
          Returns the imploded string.
     */
     public function getSplitedText()
     {
          return $this->output;
     }
}
