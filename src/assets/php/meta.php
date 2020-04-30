<?php

class MetaBuilder
{

    private $metaTags = [
        /*
        "meta" => [
            [
                "name" => "",
                "content" => "" 
            ]
        ],
        "base" => [
            [
                "href" => "",
                "target" => ""
            ]

        ],
        "link" => [
            [
                "rel" => [],
                "type" => [],
                "href" => [],
                "sizes" => []
            ]
        ],
        "title" => "",
        "script" => [
            [
                "code" => "",
                "async" => false
            ]
        ]
        */];

    private $linkTag = [
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
        ]
    ];

    /**
     * Constructor of the MetaBuilder Class
     * @access public
     * @param array $metaArr ArrayObject of all meta tags
     * @return void
     */
    function _construct(array $metaArr)
    {
        foreach ($metaArr as $key => $metaData) {
            if ($this->metaTags[$key]) {
                $this->metaTags[$key] = $metaData;
            }
        }
    }

    /**
     * Pushes new metatags in the private MetaTag Array
     * @access public
     * @param array ...$meta exploded input array
     * @return void
     */
    function addTag(...$meta)
    {
        array_push($this->metaTags, $meta);
    }

    /**
     * Sets the tags on the webpage
     * @access public 
     * @uses MetaBuilder::buildMeta
     * @uses MetaBuilder::buildBase
     * @uses MetaBuilder::buildLink
     * @uses MetaBuilder::buildTitle
     * @uses MetaBuilder::buildScript
     * @param void
     * @return void
     */
    function setTags()
    {
        print('<head>');
        print('<meta charset="UTF-8">');
        print('<meta name="viewport" content="width=device-width, initial-scale=1.0">');

        
        foreach ($this->metaTags as $key => $tags) {
            switch ($key) {
                case "meta":
                    foreach ($tags as $skey => $tag) {
                        if (!isset($tag['href']) && !isset($tag['content'])) break;
                        print $this->buildMeta($tag['href'], $tag['content']);
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
                    print $this->buildTitle($tags['title']);
                    break;
                case "script":
                    foreach ($tags as $skey => $tag) {
                        print $this->buildScript($tag['code'], isset($tag['async']));
                    }
                    break;
                default:
                    break;
            }
        }
        print('</head>');
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
        return "<meta href='{$href}' content='{$content}'>";
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
        if(!strlen($href) || !strlen($target)) return false;
        return "<base href='{$href}' target='{$target}'>";
    }

    /**
     * Build the LinkString with rel, href, corssorigin, type, sizes
     * @access private 
     * @param string $rel
     * @param string $href
     * @param string $crossorigin optional
     * @param string $type optional
     * @param string size optional
     * @return string|bool builded link tag
     */
    private function buildLink(string $rel, string $href, string $crossorigin = "", string $type = "", string $sizes = "")
    {
        $ret = "<link";

        if (!strlen($rel) || !in_array($rel, $this->linkTag['rel'])) return false;
        $ret .= " rel='{$rel}'";

        if (!strlen($href)/* || !filter_var($href, FILTER_VALIDATE_URL)*/) return false;
        $ret .= " href='{$href}'";

        if (strlen($crossorigin) > 0 && in_array($crossorigin, $this->linkTag['crossorigin'])) {
            $crossorigin ? $ret .= " crossorigin='{$crossorigin}'" : "";
        }
        
        if (strlen($type) > 0) {
            preg_match('/^(\w+)\/(.+)$/i', $type, $matches);
            if (in_array($matches[1], $this->linkTag['type'])) $type ? $ret .= " type='{$type}'" : "";
        }

        if (strlen($sizes) > 0 && preg_match('/^(?!\D)\d+x\d+(?!.)/i', $sizes)) {
            $sizes ? $ret .= " sizes='{$sizes}'" : "";
        }
        
        $ret .= "></link>";

        return $ret;
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
        return "<title>{$title}</title>";
    }

    /**
     * Build the ScriptString with href and target
     * @access private 
     * @param string $src
     * @param bool $async optional
     * @return string|bool builded script tag
     */
    private function buildScript(string $src, bool $async = false)
    {
        if (!strlen($src)) return false;
        return "<script src='{$src}' async ='{$async}'></script>";
    }

    /**
     * Checks if variable is set, if not return empty string
     * @internal
     * @access private
     * @param mixed $var
     */
    private function setOrNull($var) {
        return $var ? $var : "";
    }

}
