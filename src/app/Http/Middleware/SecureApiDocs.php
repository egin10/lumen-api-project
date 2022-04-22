<?php
namespace App\Http\Middleware;
use Closure;
use Symfony\Component\HttpFoundation\Cookie;
class SecureApiDocs
{
    public function handle($request, Closure $next)
    {
      // for local, dont add any authentication
      if (env('APP_ENV') === 'local') {
        return $next($request);
      }
      $token = $request->get('token');
      if (!$token) {
          // try to load the token from referer
          $query = array();
          parse_str(
              parse_url($request->header('referer'), PHP_URL_QUERY),
              $query
          );
          if (isset($query['token'])) {
            $token = $query['token'];
          }
      }
      // we will match it against the `SWAGGER_DOCS_TOKEN` environment variable
      if ($token === env('SWAGGER_DOCS_TOKEN')) {
        return $next($request);
      } else {
        return abort(403);
      }
    }
}