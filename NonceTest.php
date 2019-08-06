<?php

use NoncesManager\Configuration;
use NoncesManager\NonceManager;
use NoncesManager\Nonces\Types\FieldType;
use NoncesManager\Nonces\Types\UrlType;
use NoncesManager\Nonces\Verification\Verifier;


/**
 * Class NonceTest
 *
 * test Class for the Nonce Manager
 */
class NonceTest
{

    private const PLUGIN_DOMAIN = 'wp-nonce-test';

    /**
     * The post ID to be protected and reusable
     *
     * @var int
     **/
    private $postID = 1;

    /**
     * The lifetime in seconds to be protected and reusable
     *
     * Seconds: only an int value
     *
     * Above use Wordpress Default Time interval Constants
     * Minutes: MINUTE_IN_SECONDS * int amount
     * Hours: HOUR_IN_SECONDS * int amount
     * Days: DAY_IN_SECONDS * int amount
     *
     * @var int
     */
    private $lifetime = 30;

    /**
     * @var NonceManager
     */
    private $NonceManager;

    /**
     * test method
     **/
    public function build(): void
    {
        $this->buildNonceManager();

        add_action('template_redirect', array($this, 'validate'));
    }

    /**
     * Example: Build an new NonceManager without Dependency Injection
     *
     * @return void
     */
    private function buildNonceManager(): void {
        // Configuration for the NonceManager.
        $configuration = new Configuration(
            'display-post-' . $this->postID,
            '_wp_nonce_test',
            $this->lifetime
        );

        $this->NonceManager = NonceManager::build($configuration);
    }

    /**
     * Example: Create an new NonceManager with Dependency Injection
     *
     * @return void
     */
    private function createNonceManager(): void {
        // Configuration for the NonceManager.
        $configuration = new Configuration(
            'display-post-' . $this->postID,
            '_wp_nonce_test',
            $this->lifetime
        );

        // Create an new NonceManager
        $fieldType = new FieldType($configuration);
        $urlType = new UrlType($configuration);
        $verifier = new Verifier($configuration);

        $this->NonceManager = NonceManager::create($configuration, $fieldType, $urlType, $verifier);
    }

    /**
     * Show the test form
     *
     * @return void
     **/
    private function showForm(): void
    {

        get_header();

        $field = $this->NonceManager->Field();
        $url = $this->NonceManager->Url()

        ?>

        <section id="primary" class="content-area">
            <main id="main" class="site-main">
                <div id="nonce-primary" class="nonce-test entry">
                    <header class="entry-header">
                        <h1>
                            <?php esc_html_e('Quick View this Post', 'wp-nonce-client'); ?>
                        </h1>
                    </header>

                    <div class="entry-content">
                        <p>
                            <?php esc_html_e(sprintf('You can view this post only %d seconds.', $url->getLifetime()), self::PLUGIN_DOMAIN); ?>
                        </p>

                        <form method="post" action="<?php echo esc_url(get_permalink($this->postID)); ?>">
                            <?php $field->generate(false, true); ?>
                            <button><?php esc_html_e('View Post', self::PLUGIN_DOMAIN); ?></button>
                        </form>
                    </div>

                    <footer class="entry-footer">
                        <hr/>

                        <p>
                            <?php esc_html_e('Use the link to view the post with nonce:', self::PLUGIN_DOMAIN); ?>

                            <a href="<?php echo esc_url($url->generate(get_permalink($this->postID))); ?>">
                                <?php esc_html_e('View Post', self::PLUGIN_DOMAIN); ?>
                            </a>

                            <style>
                                /** quick & dirty css */
                                .entry .entry-footer a {
                                    color: #0073aa;
                                }

                                .entry .entry-footer a:hover {
                                    color: #111;
                                }
                            </style>
                        </p>
                    </footer>
                </div>
            </main>
        </section>

        <?php

        get_footer();
    }

    /**
     * Validate the created nonce
     *
     * @return void
     **/
    public function validate(): void
    {
        // check if our post or not
        if (!is_single($this->postID)) return;

        $verifier = $this->NonceManager->Verifier();

        if (!$verifier->verify()) {
            // Production Example: Show Message NonceManager has expired
            // $this->NonceManager->Verify()->displayHasExpired();

            // Only for Debugging
            $this->showForm();
            exit;
        }
    }
}