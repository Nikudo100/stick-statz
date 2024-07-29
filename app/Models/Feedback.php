<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    public $table = 'feedbacks';

    protected $fillable = [
        'wb_id',
        'user_name',
        'text',
        'matching_size',
        'product_valuation',
        'wb_created_at',
        'state',
        'answer_text',
        'answer_state',
        'photo_link_full_size',
        'photo_link_mini_size',
        'video_link',
        'was_viewed',
        'is_able_supplier_feedback_valuation',
        'supplier_feedback_valuation',
        'is_able_supplier_product_valuation',
        'supplier_product_valuation',
        'is_able_return_product_orders',
        'return_product_orders_date',
        'bables',
        'answer',
        'auto_answer',
        'template_answer_feedback_id',
        'product_id',
        'product_category_id',
        'nm_id',
        'imt_id',
        'subject_id',
    ];

    function product()
    {
        return $this->belongsTo(Product::class);
    }

    function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    function templateAnswerFeedback()
    {
        return $this->belongsTo(TemplateAnswerFeedback::class);
    }
}
