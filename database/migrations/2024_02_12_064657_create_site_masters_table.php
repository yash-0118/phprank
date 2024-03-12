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
        Schema::create('site_masters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('site_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->enum('visibility', ['public', 'private', 'password'])->default('private');
            $table->string('password')->nullable();
            $table->string('title');
            $table->string('domain');
            $table->string('category');
            $table->string('meta_description',1000);
            $table->integer('score');
            $table->string('image');
            $table->integer('load_time');
            $table->integer('page_size');
            $table->integer('http_requests_count');
            $table->string('url');
            $table->json('headings');
            $table->json('keywords');
            $table->json('image_keywords');
            $table->string('seo_friendly');
            $table->string('notfound');
            $table->string('robot_txt');
            $table->string('no_index');
            $table->json('page_links');
            $table->string('language');
            $table->string('favicon');
            $table->string('text_compression');
            $table->json('http_requests');
            $table->json('image_format');
            $table->json("js_defer");
            $table->integer('dom_size');
            $table->string('doctype');
            $table->string('http_enc');
            $table->string('mix_content');
            $table->string('server');
            $table->string('htst');
            // $table->json('unsafe_links');
            $table->json('plaintext_email');
            $table->json('structured_data');
            $table->string('meta_viewport');
            $table->string('char_set');
            $table->json('sitemap');
            $table->json('social');
            $table->integer('content_length');
            $table->integer('ratio');
            $table->json('inline_css');
            // $table->boolean('deprecated')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_masters');
    }
};
