<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Siswa as SiswaModel;
class Siswa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $req = $request->all();
      $allow = true;
      if (isset($req["nis"]) && isset($req["password"])) {
        $x = SiswaModel::where(["nis"=>$req["nis"],"password"=>$req["password"]]);
        if ($x->count() == 0) {
          $allow = false;
        }
        unset($req["nis"]);
        unset($req["password"]);
        $request->replace($req);
      }else {
        $allow = false;
      }
      if (! $request->expectsJson()) {
        if ($allow) {
          return $next($request);
        }
        return back()->withErrors(["msg"=>"Akses Ditolak"]);
      }else {
        if ($allow) {
          return $next($request);
        }
        return response()->json(["status"=>0,"msg"=>"Akses Ditolak"]);
      }
      // return response()->json($req);
    }
}
