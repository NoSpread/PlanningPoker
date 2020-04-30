<?php

class HeadBuilder
{

    private $headTags = [
        "meta" => [
            // [
            //     "name" => "",
            //     "content" => "" 
            // ]
        ],
        "base" => [
            // [
            //     "href" => "",
            //     "target" => ""
            // ]
        ],
        "link" => [
            // [
            //     "rel" => "",
            //     "type" => "",
            //     "href" => "",
            //     "crossorigin" => "",
            //     "sizes" => ""
            // ]
        ],
        "title" => "",
        "script" => [
            // [
            //     "src" => "",
            //     "async" => false
            // ]
        ]
    ];

    private $globalTags = [
        "rel" => [
            "alternate",
            "author",
            "dns-prefetch",
            "help",
            "icon",
            "license",
            "next",
            "pingback",
            "preconnect",
            "prefetch",
            "preload",
            "prerender",
            "prev",
            "search",
            "stylesheet"
        ],
        "crossorigin" => [
            "anonymous",
            "use-credentials"
        ],
        "type" => [
            "application",
            "audio",
            "font",
            "example",
            "image",
            "message",
            "model",
            "multipart",
            "text",
            "video"
        ],
        "target" => [
            "_blank",
            "_self",
            "_parent",
            "_top"
        ],
        "async" => [
            "true",
            "false"
        ]
    ];

    /**
     * Constructor of the HeadBuilder Class
     * @access public
     * @param array $headArr ArrayObject of all head tags
     * @return void
     */
    function __construct(array $headArr)
    {
        foreach ($headArr as $key => $headData) {
            if (isset($this->headTags[$key])) {
                $this->headTags[$key] = $headData;
            }
        }
    }

    /**
     * Sets the tags on the webpage
     * @access public 
     * @uses HeadBuilder::buildMeta
     * @uses HeadBuilder::buildBase
     * @uses HeadBuilder::buildLink
     * @uses HeadBuilder::buildTitle
     * @uses HeadBuilder::buildScript
     * @param void
     * @return void
     */
    function setTags()
    {
        print('<head>' . PHP_EOL);
        print('<meta charset="UTF-8">' . PHP_EOL);
        print('<meta name="viewport" content="width=device-width, initial-scale=1.0">' . PHP_EOL);

        foreach ($this->headTags as $key => $tags) {
            switch ($key) {
                case "meta":
                    foreach ($tags as $skey => $tag) {
                        if (!isset($tag['href']) && !isset($tag['content'])) break;
                        print $this->buildMeta($tag['name'], $tag['content']);
                    }
                    break;
                case "base":
                    foreach ($tags as $skey => $tag) {
                        if (!isset($tag['href'])) break;
                        print $this->buildBase($tag['href'], isset($tag['target']) ? $tag['target'] : "_blank");;
                    }
                    break;
                case "link":
                    foreach ($tags as $skey => $tag) {
                        if(!isset($tag['rel']) && !isset($tag['href'])) break;
                        print $this->buildLink($tag['rel'], $tag['href'], $this->setOrNull($tag['crossorigin']), $this->setOrNull($tag['type']), $this->setOrNull($tag['sizes']));
                    }
                    break;
                case "title":
                    print $this->buildTitle($tags);
                    break;
                case "script":
                    foreach ($tags as $skey => $tag) {
                        print $this->buildScript($tag['src'], $this->setOrNull($tag['type']), $this->setOrNull($tag['integrity']), $this->setOrNull($tag['crossorigin']), $this->setOrNull($tag['async']));
                    }
                    break;
                default:
                    break;
            }
        }
        print('</head>' . PHP_EOL);
    }

    /**
     * Build the MetaString with href and content
     * @access private 
     * @param string $href href
     * @param string $content parameter
     * @return string|bool builded meta tag
     */
    private function buildMeta(string $href, string $content)
    {
        if(!strlen($href) || !strlen($content)) return false;
        return "<meta href='{$href}' content='{$content}'>" . PHP_EOL;
    }

    /**
     * Build the BaseString with href and target
     * @access private 
     * @param string $href href
     * @param string $target parameter
     * @return string builded base tag
     */
    private function buildBase(string $href, string $target)
    {
        if(!strlen($href) || !strlen($target) ||!in_array($target, $this->globalTags['target'])) return false;
        return "<base href='{$href}' target='{$target}'>" . PHP_EOL;
    }

    /**
     * Build the LinkString with rel, href, corssorigin, type, sizes
     * @access private 
     * @param string $rel
     * @param string $href
     * @param string $crossorigin optional
     * @param string $type optional
     * @param string $sizes optional
     * @return string|bool builded link tag
     */
    private function buildLink(string $rel, string $href, string $crossorigin = "", string $type = "", string $sizes = "")
    {
        $ret = "<link";

        if (!strlen($rel) || !in_array($rel, $this->globalTags['rel'])) return false;
        $ret .= " rel='{$rel}'";

        if (!strlen($href)/* || !filter_var($href, FILTER_VALIDATE_URL)*/) return false;
        $ret .= " href='{$href}'";

        if (strlen($crossorigin) > 0 && in_array($crossorigin, $this->globalTags['crossorigin'])) {
            $ret .= " crossorigin='{$crossorigin}'";
        }
        
        if (strlen($type) > 0) {
            preg_match('/^(\w+)\/(.+)$/i', $type, $matches);
            if (in_array($matches[1], $this->globalTags['type'])) {
                $ret .= " type='{$type}'";
            }
        }

        if (strlen($sizes) > 0 && preg_match('/^(?!\D)\d+x\d+(?!.)/i', $sizes)) {
            $ret .= " sizes='{$sizes}'";
        }
        
        $ret .= "></link>";

        return $ret . PHP_EOL;
    }

    /**
     * Build the TitleString with a title
     * @access private 
     * @param string $title
     * @return string|bool builded title tag
     */
    private function buildTitle(string $title)
    {
        if (!strlen($title)) return false;
        return "<title>{$title}</title>" . PHP_EOL;
    }

    /**
     * Build the ScriptString with href and target
     * @access private 
     * @param string $src
     * @param string $type optional
     * @param string integrity optional
     * @param string crossorigin optional
     * @param string $async optional
     * @return string|bool builded script tag
     */
    private function buildScript(string $src, string $type = "", string $integrity = "", string $crossorigin = "", string $async = "")
    {
        $ret = "<script";
        if (!strlen($src)) return false;
        $ret .= " src='{$src}'";

        if (strlen($type) > 0) {
            preg_match('/^(\w+)\/(.+)$/i', $type, $matches);
            if (in_array($matches[1], $this->globalTags['type'])) {
                $ret .= " type='{$type}'";
            }
        }

        if (strlen($integrity) > 0) {
            $ret .= " integrity='{$integrity}'";
        }

        if (strlen($crossorigin) > 0 && in_array($crossorigin, $this->globalTags['crossorigin'])) {
            $ret .= " crossorigin='{$crossorigin}'";
        }

        if (strlen($async) > 0 && in_array($async, $this->globalTags['async'])) {
            $ret .= " async='{$async}'";
        }

        $ret .= "></script>";

        return $ret . PHP_EOL;
    }

    /**
     * Checks if variable is set, if not return empty string
     * @internal
     * @access private
     * @param mixed $var
     */
    private function setOrNull(&$var)
    {
        return isset($var) ? $var : "";
    }
}