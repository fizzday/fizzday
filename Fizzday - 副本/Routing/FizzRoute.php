<?php
namespace Fizzday\Routing;

/**
 * @method static Router get(string $route, Callable $callback)
 * @method static Router post(string $route, Callable $callback)
 * @method static Router put(string $route, Callable $callback)
 * @method static Router delete(string $route, Callable $callback)
 * @method static Router options(string $route, Callable $callback)
 * @method static Router head(string $route, Callable $callback)
 * @method static Router group(string $route, Callable $callback)
 */
class FizzRoute
{

    public static $routes = array();

    public static $methods = array();

    public static $callbacks = array();

    public static $patterns = array(
        ':any' => '[^/]+',
        ':num' => '[0-9]+',
        ':all' => '.*'
    );

    public static $error_callback;

    public static $baseroute = '';

    public static $autoView = [];

    /**
     * Defines a route w/ callback and method
     */
    public static function __callstatic($method, $params)
    {
        $uri = empty($params[0])?trim(self::$baseroute, '/').'/':self::$baseroute.trim($params[0], '/');
        $callback = $params[1];

        if ( $method == 'any' ) {
            self::pushToArray($uri, 'get', $callback);
            self::pushToArray($uri, 'post', $callback);
        } elseif ($method == 'group') {
            // Track current baseroute
            $curBaseroute = self::$baseroute;
            // Build new baseroute string
            self::$baseroute = $curBaseroute.trim($params[0], '/').'/';
            // Call the callable
            call_user_func($params[1]);

            // Restore original baseroute
            self::$baseroute = $curBaseroute;
        } else {
            self::pushToArray($uri, $method, $callback);
        }
    }

    /**
     * Push route items to class arrays
     */
    public static function pushToArray($uri, $method, $callback)
    {
        array_push(self::$routes, $uri);
        array_push(self::$methods, strtoupper($method));
        array_push(self::$callbacks, $callback);
    }

    /**
     * Defines callback if route is not found
     */
    public static function error($callback)
    {
        self::$error_callback = $callback;
    }

    /**
     * Runs the callback for the given request
     * $after: Processor After. It will process the value returned by Controller.
     * Example: View@process
     */
    public static function dispatch()
    {
        $uri = self::detect_uri();
        $method = $_SERVER['REQUEST_METHOD'];

        $searches = array_keys(static::$patterns);
        $replaces = array_values(static::$patterns);

        $found_route = false;

        // 如果是已定义的路由,非正则匹配到的路由(check if route is defined without regex)
        if (in_array($uri, self::$routes)) {
            $route_pos = array_keys(self::$routes, $uri);
            foreach ($route_pos as $route) {
                if (self::$methods[$route] == $method) {
                    $found_route = true;

                    //if route is not an object
                    if(!is_object(self::$callbacks[$route])){
                        // 分配控制器
                        self::matchController($route);
                    } else {
                        //call closure
                        call_user_func(self::$callbacks[$route]);
                    }
                }
            }
        } else {
            // check if defined with regex
            $pos = 0;
            foreach (self::$routes as $route) {
                if (strpos($route, ':') !== false) {
                    $route = str_replace($searches, $replaces, $route);
                }

                if (preg_match('#^' . $route . '$#', $uri, $matched)) {
                    if (self::$methods[$pos] == $method) {
                        $found_route = true;

                        array_shift($matched); //remove $matched[0] as [1] is the first parameter.

                        if(!is_object(self::$callbacks[$pos])){
                            // 分配控制器
                            self::matchController($pos, $matched);
                        } else {
                            call_user_func_array(self::$callbacks[$pos], $matched);
                        }
                    }
                }
                $pos++;
            }
        }

        // run the error callback if the route was not found
        if ($found_route == false) {
            if (!self::$error_callback) {
                self::$error_callback = function() {
                    header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
                    echo '404';
                };
            }
            call_user_func(self::$error_callback);
        }
    }

    // detect true URI, inspired by CodeIgniter 2
    private static function detect_uri()
    {
        $uri = $_SERVER['REQUEST_URI'];
        if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0) {
            $uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
        } elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0) {
            $uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
        }
        if ($uri == '/' || empty($uri)) {
            return '/';
        }
        $uri = parse_url($uri, PHP_URL_PATH);
        return str_replace(array('//', '../'), '/', trim($uri, '/'));
    }

    private static function matchController($index, $param=[])
    {
        //grab all parts based on a / separator
        $parts = explode('/',self::$callbacks[$index]);
        //collect the last index of the array
        $last = end($parts);
        //grab the controller name and method call
        $segments = explode('@',$last);

        // 保存对应的 class 和 function, 方便自动识别view目录
        static::$autoView['class'] = $segments[0];
        static::$autoView['function'] = $segments[1];

        //instanitate controller
        $controller = new $segments[0]();
        //call function
        $controller->$segments[1]($param);
    }
}