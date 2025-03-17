<?php

if ( ! defined( 'ABSPATH' ) ) exit;

// Add the popup HTML to the footer
add_action('admin_footer', function() {
    // Get the current admin screen
    $current_screen = get_current_screen();
    
    // Check if we're on the Elementor settings page
    if ( $current_screen && 'elementor_page_elementor-settings' === $current_screen->id ) {
        ?>
        <div id="soovex-documentation-popup" style="display: none;">
            <div class="soovex-documentation-content">
                <h2>📖 Soovex Custom CSS for Elementor Documentation</h2>
                <button type="button" class="soovex-close-popup" onclick="soovexCloseDocumentation()">×</button>
                <div class="soovex-documentation-body">
                    <h3>Getting Started</h3>
                    <p>The <strong>Soovex Custom CSS for Elementor</strong> plugin allows you to add custom CSS directly to pages, sections, columns, or widgets in Elementor. Follow these steps to get started:</p>
                    <ol style="list-style:decimal;">
                        <li><strong>Open Elementor Editor:</strong> Navigate to any page or post where you want to add custom CSS.</li>
                        <li><strong>Access Page Settings:</strong> Click on the gear icon in the bottom-left corner of the Elementor editor to open the <strong>Page Settings</strong>.</li>
                        <li><strong>Find Soovex Custom CSS Section:</strong> Go to the <strong>Advanced</strong> tab and scroll down to the <strong>Soovex Custom CSS</strong> section.</li>
                        <li><strong>Add Your CSS:</strong> Enter your custom CSS code in the provided field. The CSS will be scoped to the specific page or element.</li>
                    </ol>

                    <h3>Using Custom CSS for Elements</h3>
                    <p>You can also add custom CSS to individual elements like sections, columns, or widgets:</p>
                    <ol style="list-style:decimal;">
                        <li>Select the desired element (section, column, or widget).</li>
                        <li>Go to the <strong>Advanced</strong> tab in the Elementor panel.</li>
                        <li>Scroll down to the <strong>Soovex Custom CSS</strong> section.</li>
                        <li>Enter your CSS code. This CSS will only apply to the selected element.</li>
                    </ol>

                    <h3>Scoped CSS Explained</h3>
                    <p>To prevent conflicts with other styles, all CSS added via this plugin is automatically scoped:</p>
                    <ul style="list-style:disc;">
                        <li><strong>Page-Level CSS:</strong> Scoped to the current page using a unique body class (e.g., <code>body._soovex-page-123</code>).</li>
                        <li><strong>Element-Level CSS:</strong> Scoped to the specific element using a unique class (e.g., <code>._soovex-element-456</code>).</li>
                    </ul>
                    <p>This ensures that your custom styles only affect the intended page or element.</p>

                    <h3>Examples</h3>
                    <p>Here are some practical examples of how to use the plugin:</p>
                    <ul style="list-style:disc;">
                        <li><strong>Change Background Color of a Section:</strong>
                            <pre><code>
._soovex-element-456 {
    background-color: #f0f0f0;
}
                            </code></pre>
                        </li>
                        <li><strong>Customize Button Styling:</strong>
                            <pre><code>
._soovex-element-789 .elementor-button {
    color: #fff;
    background-color: #0073aa;
    border-radius: 5px;
}
                            </code></pre>
                        </li>
                        <li><strong>Hide an Element on Mobile Devices:</strong>
                            <pre><code>
@media (max-width: 768px) {
    ._soovex-element-101 {
        display: none;
    }
}
                            </code></pre>
                        </li>
                    </ul>

                    <h3>FAQs</h3>
                    <p><strong>Q: Why isn't my CSS working?</strong></p>
                    <ul style="list-style:disc;">
                        <li>Check if your user role has permission to use the plugin (configured in settings).</li>
                        <li>Verify that your CSS syntax is correct.</li>
                        <li>Ensure you're using valid selectors and targeting the correct element.</li>
                        <li>Clear your browser cache or test in incognito mode to rule out caching issues.</li>
                    </ul>

                    <p><strong>Q: Can I use SCSS or LESS?</strong></p>
                    <p>No, the plugin only supports plain CSS. However, you can write your SCSS/LESS code elsewhere, compile it into CSS, and paste the compiled CSS into the plugin.</p>

                    <p><strong>Q: Does this plugin minify CSS?</strong></p>
                    <p>Yes! You can enable CSS minification in the plugin settings under the <strong>Soovex Custom CSS</strong> tab. Minified CSS improves page load performance.</p>

                    <h3>Best Practices</h3>
                    <ul style="list-style:disc;">
                        <li>Use meaningful comments in your CSS to make it easier to maintain.</li>
                        <li>Avoid overwriting global styles unless necessary.</li>
                        <li>Test your CSS changes on different devices and screen sizes.</li>
                        <li>If you encounter conflicts, use more specific selectors or inspect the element to debug.</li>
                    </ul>

                    <h3>Support</h3>
                    <p>If you need further assistance or have feature requests, feel free to reach out:</p>
                    <ul style="list-style:disc;">
                        <li><a href="https://www.soovex.com/contact-us" target="_blank">Contact Us</a></li>
                        <!--<li><a href="https://www.soovex.com/docs/elementor-custom-css" target="_blank">Official Documentation</a></li>-->
                        <!--<li><a href="https://wordpress.org/support/plugin/elementor-custom-css-by-soovex" target="_blank">WordPress Support Forum</a></li>-->
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }
});
