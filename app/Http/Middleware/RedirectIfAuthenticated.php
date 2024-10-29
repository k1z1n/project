<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentRoute = $request->route()->getName();

        // Если пользователь не авторизован
        if (!auth()->check()) {
            // Разрешить доступ к странице логина
            if ($currentRoute === 'login') {
                return $next($request);
            }
            if ($currentRoute === 'login.store') {
                return $next($request);
            }

            // Перенаправить неавторизованного пользователя на страницу логина
            return redirect()->route('login');
        }

        // Пользователь авторизован
        $user = auth()->user();

        // Если пользователь авторизован и находится на странице логина, перенаправить в зависимости от роли
        if ($currentRoute === 'login') {
            return $this->redirectToRoleMainPage($user);
        }

        // Исключаем маршрут 'logout' из проверки ролей
        if ($currentRoute === 'logout') {
            return $next($request);
        }

        // Проверка роли пользователя и доступных маршрутов
        if (!$this->isUserAllowedOnRoute($user, $currentRoute)) {
            return $this->redirectToRoleMainPage($user);
        }

        return $next($request);
    }

    /**
     * Проверка, может ли пользователь получить доступ к текущему маршруту
     *
     * @param  \App\Models\User  $user
     * @param  string  $route
     * @return bool
     */
    protected function isUserAllowedOnRoute($user, $route): bool
    {
        switch ($user->role) {
            case 'student':
                return str_starts_with($route, 'student.');
            case 'teacher':
                return str_starts_with($route, 'teacher.');
            case 'admin':
                return str_starts_with($route, 'admin.');
            case 'superadmin':
                return true; // superadmin может посещать любые маршруты
        }

        return false;
    }

    /**
     * Перенаправление пользователя на основную страницу в зависимости от его роли
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectToRoleMainPage($user)
    {
        switch ($user->role) {
            case 'student':
                return redirect()->route('student.main');
            case 'teacher':
                return redirect()->route('teacher.list');
            case 'admin':
                return redirect()->route('admin.list');
        }
    }
}
