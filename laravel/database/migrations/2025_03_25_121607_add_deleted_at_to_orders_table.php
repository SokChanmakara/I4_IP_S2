<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->softDeletes(); // Adds a deleted_at column
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->softDeletes(); // Adds a deleted_at column
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->softDeletes(); // Adds a deleted_at column
        });

        Schema::table('products', function (Blueprint $table) {
            $table->softDeletes(); // Adds a deleted_at column
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->softDeletes(); // Adds a deleted_at column
        });

        Schema::table('order_product', function (Blueprint $table) {
            $table->softDeletes(); // Adds a deleted_at column
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->softDeletes(); // Adds a deleted_at column
        });

        Schema::table('wishlists', function (Blueprint $table) {
            $table->softDeletes(); // Adds a deleted_at column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if(Schema::hasColumn('orders','deleted_at')){
                $table->dropSoftDeletes();
            }
        });

        Schema::table('customers', function (Blueprint $table) {
            if(Schema::hasColumn('customers','deleted_at')){
                $table->dropSoftDeletes();
            }
        });

        Schema::table('categories', function (Blueprint $table) {
            if(Schema::hasColumn('categories','deleted_at')){
                $table->dropSoftDeletes();
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if(Schema::hasColumn('products','deleted_at')){
                $table->dropSoftDeletes();
            }
        });

        Schema::table('carts', function (Blueprint $table) {
            if(Schema::hasColumn('carts','deleted_at')){
                $table->dropSoftDeletes();
            }
        });

        Schema::table('order_product', function (Blueprint $table) {
            if(Schema::hasColumn('order_product','deleted_at')){
                $table->dropSoftDeletes();
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            if(Schema::hasColumn('payments','deleted_at')){
                $table->dropSoftDeletes();
            }
        });

        Schema::table('wishlists', function (Blueprint $table) {
            if(Schema::hasColumn('payments','deleted_at')){
                $table->dropSoftDeletes();
            }
        });
    }
};