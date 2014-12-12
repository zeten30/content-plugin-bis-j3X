<?php

/*
 * Content plugin for displaying data from BIS
 * -------------------------------------------
 * (also contains myr client for other bis components or modules)
 *
 * @package		Joomla.Plugin
 * @subpackage	Content.bis
 */

// No direct access.
defined('_JEXEC') or die;

require_once dirname(__FILE__) . '/myr/myr.php';

class plgContentBis extends JPlugin {

  public function onContentPrepare($context, &$row, &$params, $page = 0) {
    if (is_object($row)) {
      return $this->_handleCode($row->text, $params, $page);
    }

    return $this->_handleCode($row, $params, $page);
  }

//Replace code {plg_bis <params>} with data formated to HTML
  private function _handleCode(&$text, &$params, $page) {
    $preg_matches = null;

    //Remove empty calls with no params '{plg_bis}'
    $text = preg_replace('/\{plg_bis[ ]{0,}\}/', '', $text);

    //Replace &amp; to &
    $text = preg_replace('/&amp;/', '&', $text);

    //Search other (good) calls with params '{plg_bis <params>}'
    preg_match_all('/\{plg_bis [A-Za-z0-9 =&]{0,}\}/', $text, $preg_matches);

    $text_matches = $preg_matches[0];

    $replaced_matches = $this->_replaceCode($text_matches, $this->params);


    //Replace all matches with data
    for ($i = 0; $i < count($replaced_matches); $i++) {
      $pattern = "/$text_matches[$i]/";
      $replace = "$replaced_matches[$i]";
      $text = preg_replace($pattern, $replace, $text);
    }

    //Replace & to &amp; but keep used escape characters!
    $text = preg_replace('/&([a-zA-Z0-9#]{0,6};)/', '___$1___', $text);
    $text = preg_replace('/&/', '&amp;', $text);
    $text = preg_replace('/___([a-zA-Z0-9#]{0,6};)___/', '&$1', $text);

    return true;
  }

  private function _replaceCode($arr_matches, $params) {

    $myr = new myr();

    for ($i = 0; $i < count($arr_matches); $i++) {
      //strip slashes ang plugin name
      $arr_matches[$i] = trim(preg_replace('/\{plg_bis/', '', $arr_matches[$i]));
      $arr_matches[$i] = preg_replace('/\}/', '', $arr_matches[$i]);

      //get data
      $data = $myr->doQuery($arr_matches[$i]);

      $replacement = $myr->displayFormattedResult($data);

      //put data in place
      $arr_matches[$i] = $replacement;
    }

    return $arr_matches;
  }

}