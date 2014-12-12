<?php

final class myrTemplate {

  //Format result to html
  public static function formatResult($result, $params, $template_data) {
    $return_text = "<p>Žádná data pro tento dotaz!</p>";

    //If no custom template:
    if ($template_data == null) {
      $template_data = new myrTemplateData;

      if ($result->type == 'akce-vik' || $result->type == 'akce-klub' || $result->type == 'akce-tabor' || $result->type == 'akce-ekostan' || $result->type == 'akce-vikekostan') {
        $rtype = 'akce';
      } else {
        $rtype = $result->type;
      }

      $template_data->head = $params->get($rtype . '-head');
      $template_data->item = $params->get($rtype . '-item');
      $template_data->foot = $params->get($rtype . '-foot');

      //Set up links
      $links['detail-url'] = $params->get('detail-url');
      $links['detail-url-vik'] = $params->get('detail-url-vik');
      $links['detail-url-tabor'] = $params->get('detail-url-tabor');
      $links['detail-url-klub'] = $params->get('detail-url-klub');
      $links['detail-url-eko'] = $params->get('detail-url-eko');
      $links['detail-url-vikeko'] = $params->get('detail-url-vikeko');

      $template_data->link_detail = $links;
    }


    if ($result->data != false) {
      $return_text = '';
      //Master DIV
      $return_text.='<div class="' . $params->get('css_class') . '">' . "\n";

      //Head
      $return_text.=self::formatHead($template_data);

      //Item
      $i = 1;
      foreach ($result->data as $item) {
        if ($i == 1) {
          $params->set('even_odd', 'even');
        }
        if ($i == 2) {
          $params->set('even_odd', 'odd');
        }
        $return_text.=self::formatItem($item, $params, $template_data, $result->type) . "\n";
        if ($i == 1) {
          $i = 2;
        } else if ($i == 2) {
          $i = 1;
        }
      }

      //Foot
      $return_text.=self::formatFoot($template_data);

      //Master DIV
      $return_text.='</div>' . "\n";
    }

    return $return_text;
  }

  //Get head for format function
  private static function formatHead($template_data) {
    return myrUtils::fixNewline($template_data->head) . "\n";
  }

  //Get foot for format function
  private static function formatFoot($template_data) {
    return myrUtils::fixNewline($template_data->foot) . "\n";
  }

  //Get item for format function
  private static function formatItem($item, $params, $template_data, $result_type) {
    $preg_matches = null;
    $template = $template_data->item;

    //Search for blocks - marked ---operation name(=params)--- <code ...> ------'

    preg_match_all('/---[a-zA-Z0-9ěščřžýáíéúůĚŠČŘŽÝÁÍÉÚŮ_=, ]{0,}---/', $template, $preg_matches);
    $block_matches = $preg_matches[0];

    //Update template
    $template = myrBlockHandler::handleBlock($item, $template, $block_matches);


    //Search for variables - marked ##<name>##'
    preg_match_all('/##[a-z0-9_=,\-]{0,}##/', $template, $preg_matches);
    $var_matches = $preg_matches[0];

    //Replace variables with their values or error notice
    foreach ($var_matches as $variable) {
      $var_unescaped = preg_replace('/##/', '', $variable);
      $formatted_var = myrVarHandler::handleVariable($var_unescaped, $item, $params, $template_data->link_detail, $result_type);
      //Update template
      $template = preg_replace('/' . $variable . '/', $formatted_var, $template);
    }

    //Return final HTML code
    return myrUtils::fixNewline($template) . "\n";
  }

}
