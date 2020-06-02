<?php

$jsFiles = [
    '../assets/js/_variables.js',
    '../assets/js/components/checkbox.js',
    '../assets/js/components/input.js',
    '../assets/js/components/mainBox.js',
    '../assets/js/utils/validation.js'
];

foreach ($jsFiles as $key => $value) {
    print("<script src='{$value}'></script>");
}

print("
    <script>
        $(function() {
            $('[data-toggle=\"tooltip\"]').tooltip()
        })
    </script>
");
