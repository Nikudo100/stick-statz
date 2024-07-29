<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->string('wb_id');
            $table->string('user_name');
            $table->string('matching_size')
                ->comment('Соответствие заявленного размера реальному. - для безразмерных товаров. ок - соответствует размеру. smaller - маломерит. bigger - большемерит')
                ->nullable();
            $table->text('text');
            $table->integer('product_valuation')->comment('Оценка');
            $table->string('wb_created_at')->nullable();
            $table->string('state')->nullable();
            $table->string('answer_text')->nullable();
            $table->string('answer_state')
                ->comment('none - новый. wbRu - отображается на сайте. reviewRequired - ответ проходит проверку. rejected - ответ отклонён')
                ->nullable();
            $table->string('photo_link_full_size')->nullable();
            $table->string('photo_link_mini_size')->nullable();
            $table->string('video_link')->nullable();
            $table->boolean('was_viewed')->nullable();
            $table->boolean('is_able_supplier_feedback_valuation')
                ->comment('Доступна ли продавцу оценка отзыва')
                ->nullable();
            $table->integer('supplier_feedback_valuation')
                ->comment('Оценка отзыва, оставленная продавцом')
                ->nullable();
            $table->boolean('is_able_supplier_product_valuation')
                ->comment('Доступна ли продавцу оценка товара')
                ->nullable();
            $table->integer('supplier_product_valuation')
                ->comment('Оценка товара, оставленная продавцом')
                ->nullable();
            $table->boolean('is_able_return_product_orders')
                ->comment('Доступна ли товару опция возврата')
                ->nullable();
            $table->string('return_product_orders_date')
                ->comment('Дата и время, когда на запрос возврата был получен ответ со статус-кодом 200')
                ->nullable();
            $table->string('bables')
                ->comment('Список тегов покупателя')
                ->nullable();
            $table->text('answer')->nullable();
            $table->boolean('auto_answer')->default(false);
            $table->foreignId('template_answer_feedback_id')->nullable();
            $table->foreignId('product_id');
            $table->foreignId('product_category_id')->nullable();
            $table->integer('nm_id')->nullable();
            $table->integer('imt_id')->nullable();
            $table->integer('subject_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
