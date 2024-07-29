<?php

namespace App\Services\Business;

use App\Models\Feedback;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Log;

class FeedbackService
{
    public function updateOrCreateFeedbacks(array $feedbacks)
    {
        foreach ($feedbacks as $feedback) {
            try {
                $product = Product::where('nmID', $feedback["productDetails"]['nmId'])->first();
                $productCategory = ProductCategory::where('external_cat_id', $feedback['subjectId'])->first();
                Feedback::updateOrCreate(
                    ['wb_id' => $feedback['id']],
                    [
                        'wb_id' => $feedback["id"],
                        'user_name' => $feedback["userName"],
                        'text' => $feedback["text"],
                        'product_valuation' => $feedback["productValuation"],
                        'matching_size' => $feedback["matchingSize"] ?? null,
                        'wb_created_at' => $feedback["createdDate"] ?? null,
                        'state' => $feedback["state"] ?? null,
                        'answer_text' => $feedback["answer"]["text"] ?? null,
                        'answer_state' => $feedback["answer"]["state"] ?? null,
                        'photo_link_full_size' => $feedback["photoLinks"]["fullSize"] ?? null,
                        'photo_link_mini_size' => $feedback["photoLinks"]["miniSize"] ?? null,
                        'video_link' => $feedback["video"]["link"] ?? null,
                        'was_viewed' => $feedback["wasViewed"] ?? null,
                        'is_able_supplier_feedback_valuation' => $feedback["isAbleSupplierFeedbackValuation"] ?? null,
                        'supplier_feedback_valuation' => $feedback["supplierFeedbackValuation"] ?? null,
                        'is_able_supplier_product_valuation' => $feedback["isAbleSupplierProductValuation"] ?? null,
                        'supplier_product_valuation' => $feedback["supplierProductValuation"] ?? null,
                        'is_able_return_product_orders' => $feedback["isAbleReturnProductOrders"] ?? null,
                        'return_product_orders_date' => $feedback["returnProductOrdersDate"] ?? null,
                        'bables' => $feedback["bables"] ?? null,
                        'product_id' => $product?->id,
                        'product_category_id' => $productCategory?->id,
                        'nm_id' => $feedback["productDetails"]["nmId"] ?? null,
                        'imt_id' => $feedback["productDetails"]["imtId"] ?? null,
                        'subject_id' => $feedback["subjectId"] ?? null,
                    ]
                );
            } catch (\Exception $e) {
                Log::error("Failed to create or update feedback", [
                    'data' => $feedback,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
