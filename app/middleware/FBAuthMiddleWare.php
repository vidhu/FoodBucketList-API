<?php

/**
 * FBAuthMiddleWare checks to see if a user is authenticated or not
 *
 * @author vidhu
 */
class FBAuthMiddleWare {

    /**
     *
     * @var \Facebook\Facebook
     */
    protected $fb;

    public function __construct($app) {
        $this->fb = $app->getContainer()['fb'];
    }

    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next) {
        if (isset($_GET['accessToken'])) {
            $accessToken = $_GET['accessToken'];
            $this->fb->setDefaultAccessToken($accessToken);
        } else {
            /* @var $helper FacebookJavaScriptHelper */
            $helper = $this->fb->getJavaScriptHelper();
            $accessToken = $helper->getAccessToken();
        }
        if (!isset($accessToken)) {
            die("Not logged in or request expired");
        }

        try {
            /* @var $fbresponse FacebookResponse */
            $fbresponse = $this->fb->get('/me?fields=id', $accessToken);
            $user = $fbresponse->getGraphUser();
        } catch (Exception $e) {
            die($e->getMessage());
        }
        $next->setArgument('userid', $user->getId());
        $response = $next($request, $response);

        return $response;
    }

}
