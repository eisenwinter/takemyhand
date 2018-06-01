<?php
/**
 *
 *  _____     _       _____     _____           _
 * |_   _|___| |_ ___|     |_ _|  |  |___ ___ _| |
 *  | | | .'| '_| -_| | | | | |     | .'|   | . |
 *  |_| |__,|_,_|___|_|_|_|_  |__|__|__,|_|_|___|
 *                        |___|
 *
 * TakeMyHand  Boilerplate-Nano-Framework v0.0.3
 * @category   ViewEngine
 * @package    TakeMyHand.ViewEngine
 * @author     Jan Caspar, <jan@subkonstrukt.at>
 * @copyright  2018 Jan Caspar
 * @license    https://opensource.org/licenses/MIT MIT
 * @version    v0.0.3
 * @since      v0.0.1
 *
 * This is a very, very simple Regex View Engine,
 * it does not supporting nesting trough multiple levels
 *
 */

namespace TakeMyHand\ViewEngine;


use TakeMyHand\IRootEmitter;

class RegexViewEngine implements IViewEngine
{
    private $each_matcher = '/@each(?:\.(?<enumerable>[a-zA-Z0-9-_\.]+))+\s*(?<body>.*?)@endeach{1}/s';
    private $current_matcher = '/@~(?<raw>\^)?\.(?<prop>[a-zA-Z0-9-_\.]+)/';

    private $json_matcher = '/@>.(?<prop>[a-zA-Z0-9-_\.]+)/';

    private $master_matcher = '/@master\[(?<view>.+)\]/';

    private $section_matcher = '/@section\[(?<section>.+)\]/';
    private $section_extractor = '/@begin(?:\[(?<section>[a-zA-Z0-9-_]+))\]+\s*(?<body>.*?)@end{1}/s';

    private $content_matcher = '/@content\[\]/';
    private $property_matcher = '/@(?<raw>\^)?\.(?<prop>[a-zA-Z0-9-_\.]+)/';
    private $emitter;

    private $content;
    private $requires_master = false;
    private $master;

    private $data;

    private $known_sections = array();

    public function __construct(IRootEmitter $emitter)
    {
        $this->emitter = $emitter;
    }


    function render(string $view, $data) : ?string {
        $view_path = $_ENV['TMH_APP_DIR'].DIRECTORY_SEPARATOR.$view.'.html';
        $this->data = $data;
        if($this->file_exists($view_path)){
            $this->process_master_page($view_path);
            $this->process_each();
            $this->process_properties();
            $this->process_json();
            $this->process_sections();
            $this->render_source();
            return $this->content;
        }else{
			throw new \Exception("View could not be located: " . $view_path);
        }
    }

    private function file_exists($view) : bool{
        return is_readable($view);
    }

    private function process_sections(){
        if($this->requires_master){
            $this->content = preg_replace_callback($this->section_extractor,function($match){
                $this->known_sections[$match['section']] = $match['body'];
                return '';
            },$this->content);
            $this->master = preg_replace_callback($this->section_matcher,function($match){
                return (isset($this->known_sections[$match['section']])) ? $this->known_sections[$match['section']] : '';
            },$this->master);
            $this->master = preg_replace($this->section_matcher,'',$this->master);
        }

    }

    private function render_source(){
        if($this->requires_master){
            $this->content = preg_replace($this->content_matcher,$this->content,$this->master);
        }
    }

    private function get_property_value($base, $prop){
        if(is_array($base)){
            $prop_value = null;
            $delimited = explode('.',$prop);
            if(count($delimited) > 1){
                $cur = $base[$delimited[0]];
                $i = 1;
                while($i < (count($delimited))){
                    $cur = $cur[$delimited[$i]];
                    $i++;
                }
                $prop_value = $cur;
            }else{
                $prop_value = $base[$delimited[0]];
            }
            return $prop_value;
        }else {
            $prop_value = null;
            $delimited = explode('.', $prop);
            if (count($delimited) > 1) {
                $cur = $base->{$delimited[0]};
                $i = 1;
                while ($i < (count($delimited))) {
                    $cur = $cur->{$delimited[$i]};
                    $i++;
                }

                $prop_value = $cur;
            } else {
                $prop_value = $base->{$delimited[0]};
            }
            return $prop_value;
        }
    }

    private function process_properties(){
        $this->content = preg_replace_callback($this->property_matcher,function($match){
            $ret = $this->get_property_value($this->data,$match['prop']) ?? '[?]';
            if (empty($match['raw'])) {
                $ret = htmlentities($ret);
            }
            return $ret;
        },$this->content);
    }


    private function process_each(){
        $this->content = preg_replace_callback($this->each_matcher, function($match) {
            $body = $match['body'];
            $prop = $this->get_property_value($this->data, $match['enumerable']);
            if(is_array($prop) && count($prop) > 0){
                $replacement = '';
                foreach($prop as $p){
                    $replacement .= preg_replace_callback($this->current_matcher, function($match) use ($p){
                        $ret =  $this->get_property_value($p,$match['prop']);
                        if (empty($match['raw'])) {
                            $ret = htmlentities($ret);
                        }
                        return $ret;
                    },$body);
                }

                return $replacement;
            }else{
                return '';
            }

            },$this->content);
    }

    private function process_master_page($view_path){
        $this->content = file_get_contents($view_path);
        $this->content = preg_replace_callback($this->master_matcher, function($match){
            $master_location = $_ENV['TMH_APP_DIR'].DIRECTORY_SEPARATOR.$match['view'].'.html';
            if(is_readable($master_location)){
                $this->master = file_get_contents($master_location);
                $this->requires_master = true;
                return '';
            }
            return "<strong>Couldn't locate master:</strong> " . $master_location;
        },$this->content);
        if($this->requires_master){
            preg_match_all($this->section_matcher, $this->master,$this->known_sections);
        }
    }

    private function process_json(){
        $this->content = preg_replace_callback($this->json_matcher,function($match){
            $ret = $this->get_property_value($this->data,$match['prop']);
            $json = json_encode($ret);
            return $json;
        },$this->content);
    }


}