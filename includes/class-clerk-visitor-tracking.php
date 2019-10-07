<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Clerk_Visitor_Tracking {
	/**
	 * Clerk_Visitor_Tracking constructor.
	 */
    protected $logger;
	public function __construct() {
		$this->initHooks();
        require_once(__DIR__ . '/class-clerk-logger.php');
        $this->logger = new ClerkLogger();
	}

	/**
	 * Init hooks
	 */
	private function initHooks() {
		add_action( 'wp_footer', [ $this, 'add_tracking' ] );
	}

	/**
	 * Include tracking
	 */
	public function add_tracking() {

        try {

            $options = get_option('clerk_options');

            //Default to true
            if (!isset($options['collect_emails'])) {
                $options['collect_emails'] = true;
            }

            ?>
            <!-- Start of Clerk.io E-commerce Personalisation tool - www.clerk.io -->
            <script type="text/javascript">
                (function(w,d){
                    var e=d.createElement('script');e.type='text/javascript';e.async=true;
                    e.src=(d.location.protocol=='https:'?'https':'http')+'://cdn.clerk.io/clerk.js';
                    var s=d.getElementsByTagName('script')[0];s.parentNode.insertBefore(e,s);
                    w.__clerk_q=w.__clerk_q||[];w.Clerk=w.Clerk||function(){w.__clerk_q.push(arguments)};
                })(window,document);

                Clerk('config', {
                    key: '<?php echo $options['public_key']; ?>',
                    collect_email: <?php echo $options['collect_emails'] ? 'true' : 'false'; ?>
                });
            </script>
            <!-- End of Clerk.io E-commerce Personalisation tool - www.clerk.io -->
            <?php

        } catch (Exception $e) {

            $this->logger->error('ERROR add_tracking', ['error' => $e->getMessage()]);

        }

	}
}

new Clerk_Visitor_Tracking();