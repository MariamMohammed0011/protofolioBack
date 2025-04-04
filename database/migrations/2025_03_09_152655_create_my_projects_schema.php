<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up()
    {



        // Create the projects table
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_title');
            $table->string('img_path')->nullable();
           $table->timestamps();
        });

        // Create the categories table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Create the pivot table for projects and categories
        Schema::create('category_project', function (Blueprint $table) {
            $table->foreignId('project_id')
                  ->constrained('projects')
                  ->cascadeOnDelete();
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->cascadeOnDelete();
            $table->primary(['project_id', 'category_id']);
        });



        Schema::create('project_user', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['project_id', 'user_id']); // Prevent duplicate entries
        });
    }

    
    
    
    public function down()
    {
        Schema::dropIfExists('project_user');
        Schema::dropIfExists('category_project');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('users');
    }
};