<?php
// ----------------------------------->
// Register Blocks function for including Blade Templates with bedrock Sage
// https://gist.github.com/nicooprat/2c1a642d102425d3131037e5dc156361
// ----------------------------------->
add_action('acf/init', function() {
    if(function_exists('acf_register_block')) {

        // Look into views/blocks
        $dir = new DirectoryIterator(locate_template("views/blocks/"));

        // Loop through found blocks
        foreach ($dir as $fileinfo) {

            if (!$fileinfo->isDot()) {

                $slug = str_replace('.blade.php', '', $fileinfo->getFilename());

                // Get infos from file
                $file_path = locate_template("views/blocks/${slug}.blade.php");

                $file_headers = get_file_data($file_path, [
                    'title' => 'Title',
                    'description' => 'Description',
                    'category' => 'Category',
                    'icon' => 'Icon',
                    'keywords' => 'Keywords',
                    'align' => 'Align'
                ]);

                // var_dump($file_headers);

                if( empty($file_headers['title']) ) {
                    die( _e('This block needs a title: ' . $file_path));
                }

                if( empty($file_headers['category']) ) {
                    die( _e('This block needs a category: ' . $file_path));
                }

                $align = false;

                if(!empty($file_headers['align']) ) {
                    $align = [$file_headers['align']];
                }

                // Register a new block
                $args = [
                    'name' => $slug,
                    'title' => $file_headers['title'],
                    'description' => $file_headers['description'],
                    'category' => $file_headers['category'],
                    'icon' => $file_headers['icon'],
                    'keywords' => explode(' ', $file_headers['keywords']),
                    'render_callback'  => 'my_acf_block_render_callback',
                    'supports' => [
                        // customize alignment toolbar
                        'align' => $align,
                    ],
                ];

                // var_dump($args);
                acf_register_block($args);
            }
        }
    }
});
