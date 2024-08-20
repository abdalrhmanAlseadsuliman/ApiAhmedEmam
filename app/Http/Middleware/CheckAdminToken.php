<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
// use PHPUnit\Event\Code\Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
class CheckAdminToken
{
    use GeneralTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = null;
        try {

            $user = JWTAuth::parseToken()->authenticate();

            // الحصول على المستخدم الحالي من التوكن
            // if (! $user = JWTAuth::parseToken()->authenticate()) {
            //     $user = JWTAuth::parseToken()->authenticate();
            //     // return $this->returnError('404','user_not_found');
            //     // return response()->json(['user_not_found'], 404);
            // }
        } catch (JWTException $e) {
            // معالجة الأخطاء المتعلقة بالتوكن
            // $e->getStatusCode()
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return $this->returnError('e300','token_expired');
                // return response()->json(['token_expired'], );
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return $this->returnError('e3001','token_invalid');
                // return response()->json(['token_invalid'], );
            } else {
                return $this->returnError('e3005','token absent');
                // return response()->json(['token_absent'], );
            }
        } catch (Throwable $e) {
            // إدارة الاستثناءات العامة (Throwable)
            if ($e instanceof  \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return $this->returnError('e300','TokenInvalidException');
            } else if ($e instanceof  \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return $this->returnError('e300','TokenExpiredException');
            } else {
                return $this->returnError('e300','Token Not found');

            }
        }

        // في حال كان التوكن صالحًا، يمكن استخدام $user للقيام بأي عمليات أخرى
        // return response()->json(compact('user'));

        if(!$user)
            return $this->returnError('User not found','e3001');
        return $next($request);

    }
}
