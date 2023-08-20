@php
use LaraWelP\Foundation\Routing\WpRouteActionResolver;
@endphp
404 page

@if(!app()->environment('production') && isset(WpRouteActionResolver::$triedActions) && WpRouteActionResolver::$triedActions->totalActionsAttempted > 0)
<div>
    LaraWelP tried the following actions to resolve the request, before returning 404:
</div>

@php
dump(WpRouteActionResolver::$triedActions)
@endphp

This page is shown because app()->environment('production') is false and you can figure out how to respond to the request. <br />

In the output above you get a very helpful class that lists any tried actions in order (wither a controller and method either a view.
@endif
