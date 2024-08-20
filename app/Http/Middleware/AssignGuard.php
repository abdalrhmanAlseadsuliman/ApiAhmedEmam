<?php

// namespace App\Http\Middleware;

// use App\Traits\GeneralTrait;
// use Closure;
// use Illuminate\Http\Request;
// use Tymon\JWTAuth\Exceptions\JWTException;
// use Symfony\Component\HttpFoundation\Response;
// use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
// use Tymon\JWTAuth\Exceptions\TokenExpiredException;

// class AssignGuard extends BaseMiddleware
// {
//     use GeneralTrait;
//     /**
//      * Handle an incoming request.
//      *
//      * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
//      */
//     public function handle(Request $request, Closure $next , $guard = null): Response
//     {
//         if($guard != null){
//             auth()->shouldUse($guard);
//             $token= $request->header('auth-token');
//             $request->headers->set('auth-token', $token,true);
//             $request->headers->set('Authorization', 'Bearer ' . $token,true);
//             try {
//                $user = $this->auth->authenticate($request);
//             } catch (TokenExpiredException $e) {
//                 return $this->returnError('1000','token expired');
//             }catch (JWTException $e) {
//                 return $this->returnError('1000','token invalid');
//             }
//         }

//         return $next($request);
//     }
// }

namespace App\Http\Middleware;

use App\Traits\GeneralTrait;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Symfony\Component\HttpFoundation\Response;

class AssignGuard
{
    use GeneralTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null): Response
    {
        if ($guard != null) {
            auth()->shouldUse($guard);  // تعيين الـ Guard المحدد
            $token = $request->header('auth-token');
            $request->headers->set('auth-token', (string) $token, true);
            $request->headers->set('Authorization', 'Bearer '   .$token, true);
            try {
                // $user = JWTAuth::parseToken()->authenticate();
                $user = auth()->guard($guard)->user();
                if (!$user) {
                    return $this->returnError('1001', 'User not found');
                }
            } catch (TokenExpiredException $e) {
                return $this->returnError('1001', 'Token expired');
            } catch (JWTException $e) {
                return $this->returnError('1001 ', 'Token invalid');
            }
        }

        return $next($request);
    }
}
