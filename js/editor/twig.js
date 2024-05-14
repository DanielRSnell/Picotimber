const timberstrap = true;
    
    function filterPreviewHTML(input) {

    // Fix stretched links
    input = input.replace(/stretched-link/g, "");

    if (lc_editor_post_type == "lc_dynamic_template") {

        // Wrap lc_ shortcodes
        input = input.replace(/\[lc_([^\]]+)\]/g, "<lc-dynamic-element hidden>[$&");
        input = input.replace(/\[\/lc_([^\]]+)\]/g, "$&</lc-dynamic-element>");

        // Wrap [twig] shortcode block
        input = input.replace(/\[twig\]([\s\S]*?)\[\/twig\]/g, "<lc-dynamic-element hidden>[twig]$1[/twig]</lc-dynamic-element>");

        // console.log(input, "Dynamic template prepared for preview");
    } else {
        console.log('Filtered HTML: ', input);
        // console.log(input, "Not a dynamic template");
                // input = input.replace(/\[twig\]([\s\S]*?)\[\/twig\]/g, "<div class='live-shortcode'>[twig]$1[/twig]</div>");

    }

    return input;
}