<?php

// define url
class Core
{
    // App core class
    // create url & load controllers
    // URL method -/controller/method/params

    protected $currentController = "Pages";
    protected $currentMethod = "index";
    protected $params = [];

    // public function __construct()
    // {
    //     $url = $this->getURL();
    //     // print_r($url);
    //     // exit;

    //     // check the first value of URL in controllers
    //     if (isset($url[0])) { // pages/ Category
    //         // echo 'URL: ' . $url[0];
    //         // die;
    //         // ucwords means change lowercase to uppercase
    //         if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
    //             $this->currentController = ucwords($url[0]); // Pages
    //             unset($url[0]);
    //         }
    //     }

    //     require_once('../app/controllers/' . $this->currentController . '.php');

    //     // create new object
    //     $this->currentController = new $this->currentController;
    //     // $Pages = new Pages();
    //     // $Category = new Category();

    //     // Check there is any method in controller
    //     // Middleware check (AFTER controller instantiated)
    //     // Middleware check AFTER controller instantiation
    //     if (method_exists($this->currentController, 'middleware')) {
    //         $middlewares = $this->currentController->middleware(); // could be array or associative array

    //         // If $middlewares is associative (method-specific)
    //         if (is_array($middlewares) && array_keys($middlewares) !== range(0, count($middlewares) - 1)) {
    //             // method-based
    //             if (isset($middlewares[$this->currentMethod])) {
    //                 foreach ($middlewares[$this->currentMethod] as $middleware) {
    //                     $middlewareFile = '../app/middleware/' . $middleware . '.php';
    //                     if (file_exists($middlewareFile)) {
    //                         require_once $middlewareFile;
    //                         $middlewareClass = new $middleware;
    //                         $middlewareClass->handle();
    //                     }
    //                 }
    //             }
    //         } else {
    //             // global middleware (simple array)
    //             foreach ($middlewares as $middleware) {
    //                 $middlewareFile = '../app/middleware/' . $middleware . '.php';
    //                 if (file_exists($middlewareFile)) {
    //                     require_once $middlewareFile;
    //                     $middlewareClass = new $middleware;
    //                     $middlewareClass->handle();
    //                 }
    //             }
    //         }
    //     }



    //     if (isset($url[1])) {
    //         if (method_exists($this->currentController, $url[1])) {
    //             // echo 'Method: ' . $url[1];
    //             // die;
    //             $this->currentMethod = $url[1];
    //             unset($url[1]);
    //         }
    //         // print_r($this->currentMethod);
    //     }

    //     // Get params
    //     $this->params = $url ? array_values($url) : [];
    //     // print_r($this->params);
    //     // exit();

    //     call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    //     // print_r($this->params);
    //     // print_r($url);
    //     // exit();
    //     // echo $this->currentMethod;
    // }


public function __construct()
{
    $url = $this->getURL();

    // Step 1: Load controller
    if (isset($url[0]) && file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
        $this->currentController = ucwords($url[0]);
        unset($url[0]);
    }

    require_once('../app/controllers/' . $this->currentController . '.php');
    $this->currentController = new $this->currentController;

    // Step 2: Load method
    if (isset($url[1]) && method_exists($this->currentController, $url[1])) {
        $this->currentMethod = $url[1];
        unset($url[1]);
    }

    // ✅ Step 3: Middleware check (after method is known)
    if (method_exists($this->currentController, 'middleware')) {
        $middlewares = $this->currentController->middleware();

        $method = $this->currentMethod;

        // Support wildcard '*' and method-specific middlewares
        $middlewareList = [];

        if (isset($middlewares['*'])) {
            $middlewareList = array_merge($middlewareList, $middlewares['*']);
        }

        if (isset($middlewares[$method])) {
            $middlewareList = array_merge($middlewareList, $middlewares[$method]);
        }

        foreach ($middlewareList as $middleware) {
            $middlewareFile = '../app/middleware/' . $middleware . '.php';
            if (file_exists($middlewareFile)) {
                require_once $middlewareFile;
                $middlewareInstance = new $middleware;
                $middlewareInstance->handle();
            }
        }
    }

    // Step 4: Get params
    $this->params = $url ? array_values($url) : [];

    // Step 5: Dispatch controller method
    call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
}
    public function getURL()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            // FILTER_SANITIZE_URL filter removes all illegal URL characters from a string.
            // This filter allows all letters, digits and $-_.+!*'(),{}|\\^~[]`"><#%;/?:@&=
            $url = filter_var($url, FILTER_SANITIZE_URL); //remove illegal
            $url = explode('/', $url); //Break a string into an array
            return $url;
        }
    }
}
