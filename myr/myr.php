<?php

/*
 * BIS MYR library 
 * -------------------------------------------
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.parameter');

//Require classes
require_once dirname(__FILE__) . '/myrResult.php';
require_once dirname(__FILE__) . '/myrBis.php';
require_once dirname(__FILE__) . '/myrUtils.php';
require_once dirname(__FILE__) . '/myrTemplate.php';
require_once dirname(__FILE__) . '/myrTemplateData.php';
require_once dirname(__FILE__) . '/myrVarHandler.php';
require_once dirname(__FILE__) . '/myrBlockHandler.php';

//Master MYR class
class myr {

    public $params = array();

    //Construct set up class
    public function __construct() {
        //Get Content plugin BIS - parameters
        $plugin = JPluginHelper::getPlugin('content', 'bis');
        $this->params = json_decode($plugin->params);
    }

    //Do myr query
    public function doQuery($query) {

        if ($this->params == false) {
            echo "Content plugin - BIS musí být povolen!";
            return new myrResult('', false);
        }

        //build query url
        $url = $this->buildQueryURL($query);
        //get result from BIS
        $result = myrBis::get_result($url, $this->params->bis_user, $this->params->bis_password);


        if ($this->params->show_bis_query == 1) {
            echo myrUtils::dumpUrl($url);
        }

        return new myrResult($this->getQueryType($query), $result);
    }

    //Display result
    public function displayFormattedResult(myrResult $result, $template = null) {
        if ($this->params == false) {
            return "Content plugin - plg_bis musí být povolen!";
        }
        return myrTemplate::formatResult($result, $this->params, $template);
    }

    //Build query url from plugin params and code
    private function buildQueryURL($query) {
        $url = $this->params->bis_url;
        $url.='?' . $query;

        return $url;
    }

    //Determine query type - based on query parameter
    private function getQueryType($query) {
        $ret = '';

        //Determine query type from query params
        if (preg_match('/query=zc/', $query)) {
            $ret = 'zc';
        }

        if (preg_match('/query=akce/', $query)) {
            $ret = 'akce';
            if (preg_match('/id=/', $query)) {
                $ret = 'akce-detail';
            }
            if (preg_match('/filter=vik/', $query)) {
                $ret = 'akce-vik';
            }
            if (preg_match('/filter=tabor/', $query)) {
                $ret = 'akce-tabor';
            }
            if (preg_match('/filter=klub/', $query)) {
                $ret = 'akce-klub';
            }
            if (preg_match('/filter=vikekostan/', $query)) {
                $ret = 'akce-vikekostan';
            }
            if (preg_match('/filter=ekostan/', $query)) {
                $ret = 'akce-ekostan';
            }
        }

        if (preg_match('/query=tabor/', $query)) {
            $ret = 'tabor';
        }

        if (preg_match('/query=qal/', $query)) {
            $ret = 'qal';
        }

        if (preg_match('/query=ucastnici/', $query)) {
            $ret = 'ucastnici';
        }

        return $ret;
    }

}
