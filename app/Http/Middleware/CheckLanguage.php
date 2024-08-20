<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // تعيين اللغة الافتراضية إلى العربية
        app()->setLocale('ar');

        // إذا كانت قيمة "lang" في الطلب هي "en"، قم بتعيين اللغة إلى الإنجليزية
        if ($request->lang && $request->lang == 'en') {
            app()->setLocale('en');
        }

        // متابعة تنفيذ الطلب
        return $next($request);
    }
}
