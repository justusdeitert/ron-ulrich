<?php
// https://gist.github.com/nicooprat/2c1a642d102425d3131037e5dc156361
// The Bedrock Sage Way to generate blade blocks
// ---------------------->
function my_acf_block_render_callback( $block ) {
    $slug = str_replace('acf/', '', $block['name']);
    $block['slug'] = $slug;
    $block['classes'] = implode(' ', [$block['slug'], $block['className'], $block['align']]);
    echo \App\template("blocks/${slug}", ['block' => $block]);
}
