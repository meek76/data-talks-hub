<?php
/**
 * Vimeo Responsive Shortcodes
 * Add to functions.php or mu-plugins/custom.php
 * 
 * Usage:
 * [vimeo_teaser] - displays teaser video from ACF field 'teaser_vimeo_id'
 * [vimeo_full] - displays full video from ACF field 'pelne_vimeo_id' with optional hash
 */

/**
 * Responsive Vimeo Teaser Shortcode
 * 
 * @usage [vimeo_teaser]
 * @usage [vimeo_teaser id="123456789"]
 */
add_shortcode('vimeo_teaser', function($atts) {
    $atts = shortcode_atts(['id' => ''], $atts);
    $video_id = $atts['id'] ?: get_field('teaser_vimeo_id');
    
    if (!$video_id) {
        return '<!-- Brak ID teaser video -->';
    }
    
    return sprintf(
        '<div class="vimeo-container vimeo-teaser" style="position: relative; width: 100%%; padding-bottom: 56.25%%; height: 0; overflow: hidden; border-radius: 8px;">
            <iframe 
                src="https://player.vimeo.com/video/%s" 
                style="position: absolute; top: 0; left: 0; width: 100%%; height: 100%%;" 
                frameborder="0" 
                allow="autoplay; fullscreen; picture-in-picture" 
                allowfullscreen>
            </iframe>
        </div>',
        esc_attr($video_id)
    );
});

/**
 * Responsive Vimeo Full Video Shortcode (with optional hash for unlisted videos)
 * 
 * @usage [vimeo_full]
 * @usage [vimeo_full id="987654321" hash="abc123def456"]
 */
add_shortcode('vimeo_full', function($atts) {
    $atts = shortcode_atts(['id' => '', 'hash' => ''], $atts);
    $video_id = $atts['id'] ?: get_field('pelne_vimeo_id');
    $video_hash = $atts['hash'] ?: get_field('pelne_vimeo_hash');
    
    if (!$video_id) {
        return '<!-- Brak ID pełnego video -->';
    }
    
    // Build URL with optional hash for unlisted videos
    $url = "https://player.vimeo.com/video/{$video_id}";
    if ($video_hash) {
        $url .= "?h={$video_hash}";
    }
    
    return sprintf(
        '<div class="vimeo-container vimeo-full" style="position: relative; width: 100%%; padding-bottom: 56.25%%; height: 0; overflow: hidden; border-radius: 8px;">
            <iframe 
                src="%s" 
                style="position: absolute; top: 0; left: 0; width: 100%%; height: 100%%;" 
                frameborder="0" 
                allow="autoplay; fullscreen; picture-in-picture" 
                allowfullscreen>
            </iframe>
        </div>',
        esc_url($url)
    );
});

// Optional: Add CSS class for easier styling
add_action('wp_head', function() {
    echo '<style>
        .vimeo-container {
            background: #000;
            margin: 20px 0;
        }
        .vimeo-container iframe {
            display: block;
        }
    </style>';
});
