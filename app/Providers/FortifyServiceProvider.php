<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Fortify::authenticateUsing(function (LoginRequest $request) {
        //     $user = User::where('username', $request->username)->first();

        //     if (
        //         $user &&
        //         \Hash::check($request->password, $user->password)
        //     ) {
        //         return $user;
        //     }
        // });
        $payinId = DB::table('payout_counter')->pluck('id');
        $count = count(DB::table('payout_counter')->get());
        foreach($payinId as $id){
            if($id * 4 + 1 <= $count){
                DB::table('payout_counter')->where('id', $id)->update(['status' => 1]);
                $pId = DB::table('payout_counter')->where('id', $id)->pluck('payinId');
                DB::table('payouts')->where('payinId', $pId)->update(['payoutEligibility' => 1]);
            }
        }

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
    }
}
