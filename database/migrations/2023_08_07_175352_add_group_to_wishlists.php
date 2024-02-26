<?php

use App\Models\Wishlist;
use App\Models\WishlistGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wishlists', function (Blueprint $table) {
            $table->foreignId('wishlist_group_id')->nullable()->constrained();
        });

        $this->seedWishlist();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropColumn('wishlist_group_id');
        });
    }

    private function seedWishlist()
    {
        $wishlists_user = Wishlist::all()->groupBy('user_id');

        foreach($wishlists_user as $wishlists) {
            foreach ($wishlists as $wishlist) {
                DB::table('wishlist_groups')->insertOrIgnore([
                    'id' => $wishlist->user_id,
                    'name' => 'Todos',
                    'user_id' => $wishlist->user_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $wishlist->wishlist_group_id = $wishlist->user_id;
                $wishlist->save();
            }
        }
    }
};
