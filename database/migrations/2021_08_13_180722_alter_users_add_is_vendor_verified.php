<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersAddIsVendorVerified extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('users', 'is_vendor_verified')) {
            Schema::table('users', function(Blueprint $table){
                $table->boolean('is_vendor_verified')->default(0);
            });
        }

        if(config("features.marketplace")){
            foreach(\App\Models\User::where('is_vendor', 2)->get() as $vendor){
                if($verification = \App\Models\Verification::where('user_id', $vendor->id)->first()){
                    if($verification->status == "Verified"){
                        $vendor->is_vendor_verified = true;
                        $vendor->update();
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('users', 'is_vendor_verified')){
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_vendor_verified');
            });
        }
    }
}
