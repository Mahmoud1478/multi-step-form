<?php

namespace App\Http\Middleware;

use App\Enums\Step\StateEnum;
use App\Models\Step;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UncompletedStepMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,  $number): Response
    {

        $check = Step::where('user_id',auth()->id())
            ->where('state',StateEnum::UNCOMPLETED)
            ->where('number','>=',$number)
            ->exists();
        if (!$check){
            if ($request->ajax()){
                return \response()->json([
                    'message' => 'unauthraize'
                ],402);
            }
            else{
                return route('subjects.create');
            }
        }

        return $next($request);
    }
}
