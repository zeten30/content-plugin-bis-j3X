<?php

final class myrBlockHandler {

  public static function handleBlock($item, $text, $matches) {

    for ($i = 0; $i < count($matches); $i++) {
      $match = $matches[$i];

      //Block - if prilohy
      if (preg_match('/if prilohy/', $match)) {
        if (isset($item->priloha_1) || isset($item->priloha_2)) {
          if (strlen($item->priloha_1) > 0 || strlen($item->priloha_2) > 0) {
            //If set priloha1/2 not empty -- clear block code 
            $text = self::removeBlockCode($text, $match);
          } else {
            //if empty -- delete whole block
            $text = self::clearBlock($text, $match);
          }
        }
      }
      // ****************
      // 
      //Block - if obrazek
      elseif (preg_match('/if obrazek/', $match)) {

        $pattern = '/\.JPG|\.PNG|\.GIF|\.jpg|\.png|\.gif/';

        if (isset($item->priloha_1) || isset($item->priloha_2)) {
          if ((strlen($item->priloha_1) > 0 && preg_match($pattern, $item->priloha_1)) || (strlen($item->priloha_2) > 0 && preg_match($pattern, $item->priloha_2))) {
            //If priloha1/2 is image 
            $text = self::removeBlockCode($text, $match);
          } else {
            //if not image -- delete whole block
            $text = self::clearBlock($text, $match);
          }
        }
      }
      // ****************
      // 
      //Block - if promenna
      elseif (preg_match('/if promenna=/', $match)) {
        $variable = substr($match, strpos($match, '=') + 1, strpos($match, ',') - strpos($match, '=') - 1);
        $parameter = trim(preg_replace('/---/', '', substr($match, strpos($match, ',') + 1)));

        if (isset($item->$variable)) {

          if ((string) $item->$variable == (string) $parameter) {
            //If variable=parameter 
            $text = self::removeBlockCode($text, $match);
          } else {
            //if not 
            $text = self::clearBlock($text, $match);
          }
        } else {
          $text = self::removeBlockCode($text, $match, myrUtils::errorBlock($match));
        }
      }
      // ****************          
      // 
      //Block - if_not promenna
      elseif (preg_match('/if_not promenna=/', $match)) {
        $variable = substr($match, strpos($match, '=') + 1, strpos($match, ',') - strpos($match, '=') - 1);
        $parameter = trim(preg_replace('/---/', '', substr($match, strpos($match, ',') + 1)));

        if (isset($item->$variable)) {

          if ((string) $item->$variable == (string) $parameter) {
            //If variable=parameter                        
            $text = self::clearBlock($text, $match);
          } else {
            //if not 
            $text = self::removeBlockCode($text, $match);
          }
        } else {
          $text = self::removeBlockCode($text, $match, myrUtils::errorBlock($match));
        }
      }
      // ****************
      //
            //Unknown block
      else {
        if (!preg_match('/------/', $match)) {
          $text = self::removeBlockCode($text, $match);
          $text = myrUtils::errorBlock($match) . $text;
        }
      }
    }

    return $text;
  }

  private static function clearBlock($text, $match) {
    $start_block_pos = strpos($text, $match);
    $end_block_pos = strpos($text, '------');

    $text = substr($text, 0, $start_block_pos) . substr($text, $end_block_pos + strlen('------'));

    return $text;
  }

  private static function removeBlockCode($text, $match, $err = '') {
    $text = preg_replace('/' . $match . '/', $err, $text, 1);
    $text = preg_replace('/------/', '', $text, 1);
    return $text;
  }

}