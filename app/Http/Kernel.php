<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
// ... global middleware ...

protected $middlewareGroups = [
// ... middleware groups ...
];

protected $middlewareAliases = [
'auth' => \App\Http\Middleware\Authenticate::class,
'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
'can' => \Illuminate\Auth\Middleware\Authorize::class,
'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

// This is the line that matters
'role' => \App\Http\Middleware\CheckRole::class,
];
}
```

**2. `app/Http/Middleware/CheckRole.php`**
```php
<?php

namespace App\Http\Middleware; // <-- Check this namespace

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole // <-- Check this class name
{
public function handle(Request $request, Closure $next, ...$roles): Response
{
if (!Auth::check()) {
return redirect('login');
}
$user = Auth::user();
foreach ($roles as $role) {
if ($user->hasRole($role)) {
return $next($request);
}
}
abort(403, 'UNAUTHORIZED ACTION.');
}
}
```

**3. `routes/web.php`**
```php
// Example of correct usage
Route::middleware('role:admin')->group(function () {
// ...
});