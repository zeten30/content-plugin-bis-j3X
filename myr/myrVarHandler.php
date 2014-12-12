<?php

class myrVarHandler {

  //Handle variables - display BIS vars or special format for dates, web, e-mail, links or display attachments
  public static function handleVariable($name, $item, $params, $link_detail, $result_type) {

    if (!preg_match('/_plg_bis/', $name)) {
      if (isset($item->$name)) {
        $value = (string) $item->$name;
      } else {
        //Error if bad variable
        $value = myrUtils::errorVariable($name);
      }
    } else {
      $value = self::handleCustomVar($name, $item, $params, $link_detail, $result_type);
      //Error if bad variable
      if ($value === false) {
        $value = myrUtils::errorVariable($name);
      }
    }

    return $value;
  }

  private static function handleCustomVar($name, $item, $parameters, $link_detail, $result_type) {
    $ret = false;

    //dates
    if ($name == 'od_plg_bis' || $name == 'do_plg_bis') {

      $ret = ' ';

      $name == 'od_plg_bis' ? $value = (string) $item->od : '';
      $name == 'do_plg_bis' ? $value = (string) $item->do : '';

      if (preg_match('/-/', $value)) {
        List($year, $month, $day) = Explode("-", $value);

        if (substr($day, 0, 1) == '0') {
          $day = substr($day, 1);
        }

        if (substr($month, 0, 1) == '0') {
          $month = substr($month, 1);
        }

        $ret = $day . '.' . $month . '.' . $year;
      } else {
        $ret = 'neomezeno';
      }
    }

    //web + www
    if ($name == 'web_plg_bis' || $name == 'www_plg_bis') {
      $name == 'web_plg_bis' ? $value = (string) $item->web : ' ';
      $name == 'www_plg_bis' ? $value = (string) $item->www : ' ';

      $ret = '<a href="http://' . $value . '">' . $value . '</a>';
    }

    //kontakt_email
    if ($name == 'kontakt_email_plg_bis' || $name == 'email_plg_bis') {
      $name == 'kontakt_email_plg_bis' ? $value = (string) $item->kontakt_email : ' ';
      $name == 'email_plg_bis' ? $value = (string) $item->email : ' ';

      $value = preg_replace('/\@/', '&#64;', $value);
      $ret = '<a href="mailto:' . $value . '">' . $value . '</a>';
    }

    //vek
    if ($name == 'vek_od_plg_bis' || $name == 'vek_do_plg_bis') {
      $name == 'vek_od_plg_bis' ? $value = (string) $item->vek_od : ' ';
      $name == 'vek_do_plg_bis' ? $value = (string) $item->vek_do : ' ';

      if ($value == '') {
        $value = 'neomezeno';
      }
      $ret = $value;
    }

    //even_odd_plg_bis
    if ($name == 'even_odd_plg_bis') {
      $ret = $parameters->get('even_odd');
    }

    //od_do_plg_bis
    if ($name == 'od_do_plg_bis') {
      $od = (string) $item->od;
      $do = (string) $item->do;

      if (preg_match('/-/', $od)) {
        List($year, $month, $day) = Explode("-", $od);
        $start_day = $day;
        $from_month = $month;
        $from_year = $year;

        if (substr($from_month, 0, 1) == '0') {
          $from_month = substr($from_month, 1);
        }

        if (substr($start_day, 0, 1) == '0') {
          $start_day = substr($start_day, 1);
        }
      }

      if (preg_match('/-/', $do)) {
        List($year, $month, $day) = Explode("-", $do);
        $until_day = $day;
        $until_month = $month;
        $until_year = $year;

        if (substr($until_month, 0, 1) == '0') {
          $until_month = substr($until_month, 1);
        }

        if (substr($until_day, 0, 1) == '0') {
          $until_day = substr($until_day, 1);
        }
      }

      if (isset($from_year) && isset($until_year)) {

        if ($from_year == $until_year && $from_month == $until_month) {
          $ret = "$start_day-$until_day.$until_month.$until_year";
        } elseif ($from_year == $until_year && $from_month != $until_month) {
          $ret = "$start_day.$from_month - $until_day.$until_month.$until_year";
        } else {
          $ret = "$start_day.$from_month.$from_year - $until_day.$until_month.$until_year";
        }
      } elseif (isset($from_year) && !isset($until_year)) {
        $ret = "$start_day.$from_month.$from_year - neomezeno";
      } elseif (!isset($from_year) && isset($until_year)) {
        $ret = "neuvedeno - $until_day.$until_month.$until_year";
      } elseif (!isset($from_year) && !isset($until_year)) {
        $ret = "neomezeno";
      }
    }

    //vek_plg_bis
    if ($name == 'vek_plg_bis') {
      $od = (string) $item->vek_od;
      $do = (string) $item->vek_do;

      if (strlen($do) > 0 && strlen($od) > 0) {
        $ret = "od $od do $do let";
      }

      if (strlen($do) == 0 && strlen($od) > 0) {
        $ret = "od $od let";
      }

      if (strlen($do) > 0 && strlen($od) == 0) {
        $ret = "do $do let";
      }

      if (strlen($do) == 0 && strlen($od) == 0) {
        $ret = "neomezeno";
      }
    }

    //staz_priloh_plg_bis - download attachment links
    if (preg_match('/staz_priloh_plg_bis/', $name)) {
      $ret = "";

      //Handle params
      $split = preg_split('/=/', $name);
      if (isset($split[1])) {
        $params = preg_split('/,/', $split[1]);
      }

      isset($params[0]) ? $priloha = $params[0] : $priloha = 0;

      $url = $parameters->get('attachment_url');

      if ($priloha == 0) {

        if (isset($item->priloha_1) && strlen($item->priloha_1) > 0) {
          $ret.='<a href="' . $url . $item->priloha_1 . '">' . $item->priloha_1 . '</a><br />';
        }
        if (isset($item->priloha_2) && strlen($item->priloha_2) > 0) {
          $ret.='<a href="' . $url . $item->priloha_2 . '">' . $item->priloha_2 . '</a> ';
        }
      }

      if ($priloha == 1) {
        if (isset($item->priloha_1) && strlen($item->priloha_1) > 0) {
          $ret.='<a href="' . $url . $item->priloha_1 . '">' . $item->priloha_1 . '</a><br />';
        }
      }

      if ($priloha == 2) {
        if (isset($item->priloha_2) && strlen($item->priloha_2) > 0) {
          $ret.='<a href="' . $url . $item->priloha_2 . '">' . $item->priloha_2 . '</a> ';
        }
      }
    }

    //obrazek
    if (preg_match('/obrazek_plg_bis/', $name)) {
      $ret = "";
      $url = $parameters->get('attachment_url');

      //Handle params
      $split = preg_split('/=/', $name);
      if (isset($split[1])) {
        $params = preg_split('/,/', $split[1]);
      }

      isset($params[0]) ? $width = $params[0] : $width = '';
      isset($params[1]) ? $display = $params[1] : $display = 0;
      isset($params[2]) ? $class = $params[2] : $class = '';

      $img_attr = '';

      if ($width != '') {
        $img_attr.=' width="' . $width . '"';
      }

      if ($class != '') {
        $img_attr.=' class="' . $class . '"';
      }

      $priloha1 = (string) $item->priloha_1;
      $priloha2 = (string) $item->priloha_2;


      //Choose view - display param
      if ($display == 0) {
        if (strlen($priloha1) > 0 && preg_match('/.*\.[JPG|PNG|GIF|jpg|png|gif]/', $priloha1)) {
          $ret.='<img src="' . $url . $priloha1 . '" alt="' . $priloha1 . '" ' . $img_attr . ' /> ';
        }
        if (strlen($priloha2) > 0 && preg_match('/.*\.[JPG|PNG|GIF|jpg|png|gif]/', $priloha2)) {
          $ret.='<img src="' . $url . $priloha2 . '" alt="' . $priloha2 . '" ' . $img_attr . ' /> ';
        }
      }

      if ($display == 1) {
        if (strlen($priloha1) > 0 && preg_match('/.*\.[JPG|PNG|GIF|jpg|png|gif]/', $priloha1)) {
          $ret.='<img src="' . $url . $priloha1 . '" alt="' . $priloha1 . '" ' . $img_attr . ' /> ';
        }
      }

      if ($display == 2) {
        if (strlen($priloha2) > 0 && preg_match('/.*\.[JPG|PNG|GIF|jpg|png|gif]/', $priloha2)) {
          $ret.='<img src="' . $url . $priloha2 . '" alt="' . $priloha2 . '" ' . $img_attr . ' /> ';
        }
      }
    }

    //ikona programu
    if (preg_match('/ikona_prg_plg_bis/', $name)) {
      $ret = "";
      $url = JURI::base() . 'plugins/content/bis/images/';

      //Handle params
      $split = preg_split('/=/', $name);
      if (isset($split[1])) {
        $params = preg_split('/,/', $split[1]);
      }

      isset($params[0]) ? $width = $params[0] : $width = 32;
      isset($params[1]) ? $color = $params[1] : $color = 'svetla';
      isset($params[2]) ? $class = $params[2] : $class = '';

      if (!($color == 'svetla' || $color == 'tmava' || $color == 'bront')) {
        $color = 'svetla';
      }

      $img_attr = '';

      if ($width != '') {
        $img_attr.=' width="' . $width . '"';
      }

      if ($class != '') {
        $img_attr.=' class="' . $class . '"';
      }

      $program = 'nic';

      if (isset($item->program_id)) {
        $program = (string) $item->program_id;
        $program_desc = "Program " . $item->program;
      }

      if (strlen($program) == 0) {
        $program = 'nic';
      }

      $icon_type = $parameters->get('icon-type');

      $ret.='<img src="' . $url . $program . '_' . $color . '.' . $icon_type . '" 
                alt="' . $program_desc . '" 
                title="' . $program_desc . '" '
              . $img_attr . ' /> ';
    }


    //link_priloha_plg_bis - return URL of attachment
    if (preg_match('/link_priloha_plg_bis/', $name)) {
      $ret = " ";

      //Handle params
      $split = preg_split('/=/', $name);
      if (isset($split[1])) {
        $params = preg_split('/,/', $split[1]);
      }

      isset($params[0]) ? $priloha = $params[0] : $priloha = 1;

      $url = $parameters->get('attachment_url');

      if ($priloha == 1) {
        if (isset($item->priloha_1) && strlen($item->priloha_1) > 0) {
          $ret.=$url . $item->priloha_1;
        }
      }

      if ($priloha == 2) {
        if (isset($item->priloha_2) && strlen($item->priloha_2) > 0) {
          $ret.=$url . $item->priloha_2;
        }
      }
    }

    //ikona programu - link URL
    if (preg_match('/ikona_prg_link_plg_bis/', $name)) {
      $ret = "";
      $url = JURI::base() . 'plugins/content/bis/images/';

      //Handle params
      $split = preg_split('/=/', $name);
      if (isset($split[1])) {
        $params = preg_split('/,/', $split[1]);
      }

      isset($params[0]) ? $color = $params[0] : $color = 'svetla';

      if (!($color == 'svetla' || $color == 'tmava')) {
        $color = 'svetla';
      }

      if (isset($item->program_id)) {
        $program = (string) $item->program_id;
      }

      if (strlen($program) == 0) {
        $program = 'nic';
      }

      $icon_type = $parameters->get('icon-type');

      $ret.=$url . $program . '_' . $color . '.' . $icon_type;
    }


    //URL link to detail
    if (preg_match('/link_detail_plg_bis/', $name)) {

      if ($result_type == 'akce-vik' || $result_type == 'akce-klub' || $result_type == 'akce-tabor' || $result_type == 'akce-ekostan' || $result_type == 'akce-vikeko') {

        if ($result_type == 'akce-vik' && isset($link_detail['detail-url-vik'])) {
          if (strlen($link_detail['detail-url-vik']) > 0) {
            $url = $link_detail['detail-url-vik'];
          } else {
            $url = $link_detail['detail-url'];
          }
        } elseif ($result_type == 'akce-tabor' && isset($link_detail['detail-url-tabor'])) {
          if (strlen($link_detail['detail-url-tabor']) > 0) {
            $url = $link_detail['detail-url-tabor'];
          } else {
            $url = $link_detail['detail-url'];
          }
        } elseif ($result_type == 'akce-klub' && isset($link_detail['detail-url-klub'])) {
          if (strlen($link_detail['detail-url-klub']) > 0) {
            $url = $link_detail['detail-url-klub'];
          } else {
            $url = $link_detail['detail-url'];
          }
        } elseif ($result_type == 'akce-ekostan' && isset($link_detail['detail-url-ekostan'])) {
          if (strlen($link_detail['detail-url-ekostan']) > 0) {
            $url = $link_detail['detail-url-ekostan'];
          } else {
            $url = $link_detail['detail-url'];
          }
        } elseif ($result_type == 'akce-vikekostan' && isset($link_detail['detail-url-vikeko'])) {
          if (strlen($link_detail['detail-url-vikeko']) > 0) {
            $url = $link_detail['detail-url-vikeko'];
          } else {
            $url = $link_detail['detail-url'];
          }
        } else {
          $url = $link_detail['detail-url'];
        }
      } else {
        $url = $link_detail['detail-url'];
      }

      $ret = $url . "$item->id";
    }

    //formatted text - detect html or replace linebreaks
    if ($name == 'text_plg_bis') {

      $value = (string) $item->text;

      $value_stripped = strip_tags($value);

      if ($value == $value_stripped) {
        //Format as HTML
        $ret = "<p>" . str_replace("\n", "<br />", $value) . "</p>";
      } else {
        $ret = $value;
      }
    }


    //intro text - remove html, shorten text to value specified by param 
    if (preg_match('/intro_text_plg_bis/', $name)) {
      $value = (string) $item->text;
      $value_stripped = str_replace("\n\n", "\n", strip_tags($value));
      $length = 250;

      //Handle params
      $split = preg_split('/=/', $name);
      if (isset($split[1])) {
        $params = preg_split('/,/', $split[1]);
      }

      if (isset($params[0])) {
        $length = $params[0];
      }

      if (strlen($value_stripped) > ($length - 10)) {
        $value_stripped = substr($value_stripped, 0, strpos($value_stripped, ' ', (int) $length - 10));
      }

      $ret = "<p>" . str_replace("\n", "<br />", $value_stripped) . " ...</p>";
    }

    //Return value
    return $ret;
  }

}
