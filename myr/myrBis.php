<?php

final class myrBis {

//Build XML object with result data
  public static function get_result($url, $user = null, $password = null, $params = null) {
    $data = self::get_bis_data($url, $user, $password, $params);
    if ($data == FALSE)
      return FALSE;

    $xml = @simplexml_load_string($data);
    if (!$xml) {
      return FALSE;
    }
    if ($xml['error']) {
      return FALSE;
    }

    return $xml->row;
  }

  //Read raw data from bis
  private function get_bis_data($url, $user, $password, $params) {
    if (!$user) {
      $data = @file_get_contents($url);
    } else {
      $auth = array('user' => $user, 'password' => $password);
      if (isset($params)) {
        $params = array_merge($params, $auth);
      } else {
        $params = $auth;
      }
      $post = http_build_query($params);
      $http = array('http' => array('method' => 'POST', 'content' => $post, 'timeout' => 6000));
      $ctx = stream_context_create($http);
      $data = @file_get_contents($url, FILE_TEXT, $ctx);
    }
    if (!$data) {
      return FALSE;
    }
    return $data;
  }

}
