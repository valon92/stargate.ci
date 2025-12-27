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
        // Add subscriber_id column if it doesn't exist
        if (!Schema::hasColumn('community_posts', 'subscriber_id')) {
            Schema::table('community_posts', function (Blueprint $table) {
                // First, try to map user_id to subscriber_id if user_id exists
                if (Schema::hasColumn('community_posts', 'user_id')) {
                    // Add subscriber_id as nullable first
                    $table->foreignId('subscriber_id')->nullable()->after('id');
                } else {
                    $table->foreignId('subscriber_id')->nullable()->after('id')->constrained('subscribers')->onDelete('cascade');
                }
            });
            
            // Migrate data from user_id to subscriber_id if needed
            if (Schema::hasColumn('community_posts', 'user_id')) {
                DB::statement("
                    UPDATE community_posts 
                    SET subscriber_id = (
                        SELECT subscribers.id 
                        FROM subscribers 
                        INNER JOIN users ON users.email = subscribers.email 
                        WHERE users.id = community_posts.user_id 
                        LIMIT 1
                    )
                    WHERE subscriber_id IS NULL
                ");
            }
            
            // Make subscriber_id not nullable after migration
            Schema::table('community_posts', function (Blueprint $table) {
                $table->foreignId('subscriber_id')->nullable(false)->change();
            });
        }
        
        // Rename columns if needed (SQLite doesn't support renameColumn, so we'll skip this)
        // Instead, we'll use the existing column names in the model
        
        // Add missing columns if they don't exist
        Schema::table('community_posts', function (Blueprint $table) {
            if (!Schema::hasColumn('community_posts', 'views_count') && Schema::hasColumn('community_posts', 'view_count')) {
                // SQLite doesn't support rename, so we'll use view_count in model
            } else if (!Schema::hasColumn('community_posts', 'views_count')) {
                $table->integer('views_count')->default(0);
            }
            
            if (!Schema::hasColumn('community_posts', 'likes_count') && Schema::hasColumn('community_posts', 'like_count')) {
                // Use like_count in model
            } else if (!Schema::hasColumn('community_posts', 'likes_count')) {
                $table->integer('likes_count')->default(0);
            }
            
            if (!Schema::hasColumn('community_posts', 'comments_count') && Schema::hasColumn('community_posts', 'comment_count')) {
                // Use comment_count in model
            } else if (!Schema::hasColumn('community_posts', 'comments_count')) {
                $table->integer('comments_count')->default(0);
            }
            
            if (!Schema::hasColumn('community_posts', 'shares_count') && Schema::hasColumn('community_posts', 'share_count')) {
                // Use share_count in model
            } else if (!Schema::hasColumn('community_posts', 'shares_count')) {
                $table->integer('shares_count')->default(0);
            }
            
            // Add category column if it doesn't exist
            if (!Schema::hasColumn('community_posts', 'category') && Schema::hasColumn('community_posts', 'category_id')) {
                // We'll map category_id to category in the model
            } else if (!Schema::hasColumn('community_posts', 'category')) {
                $table->string('category')->default('general');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('community_posts', function (Blueprint $table) {
            if (Schema::hasColumn('community_posts', 'subscriber_id')) {
                $table->dropForeign(['subscriber_id']);
                $table->dropColumn('subscriber_id');
            }
        });
    }
};
